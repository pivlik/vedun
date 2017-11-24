<?php

set_time_limit(0);

require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule('iblock');
require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/prolog.php');

$requiredModules = array('estate');
foreach ($requiredModules as $requiredModule) {
    CModule::IncludeModule($requiredModule);
}

use Bitrix\Estate as Estate;

// Типы квартир по комнатам
$roomTypes = Estate\EstateRefFlatTypesTable::getAssoc(array(), 'IMPORT_ID');

$io = CBXVirtualIo::GetInstance();

$strError = '';
$importSuccess = false;

while (true) {
    if (!isset($_REQUEST['URL_DATA_FILE'])) {
        break;
    }

    $URL_DATA_FILE = trim($_REQUEST['URL_DATA_FILE']);
    $URL_DATA_FILE = trim(str_replace('\\', '/', $URL_DATA_FILE) , '/');
    $FILE_NAME = rel2abs($_SERVER['DOCUMENT_ROOT'], '/'.$URL_DATA_FILE);
    if ((strlen($FILE_NAME) > 1)
        && ($FILE_NAME === '/'.$URL_DATA_FILE)
        && $io->FileExists($_SERVER['DOCUMENT_ROOT'] . $FILE_NAME)
        && ($APPLICATION->GetFileAccessPermission($FILE_NAME) >= 'W')
    ) {
        $DATA_FILE_NAME = $_SERVER['DOCUMENT_ROOT'] . $FILE_NAME;
    }
    if (empty($DATA_FILE_NAME)) {
        $strError = 'Файл с данными не загружен или не выбран<br>';
        break;
    }

    // Корпуса
    $buildings = Estate\EstateBuildingTable::getAssoc(array(), 'IMPORT_ID', 'ID');

    // Секции
    //$sections = Estate\EstateSectionTable::getAssoc(array(), 'IMPORT_ID', 'ID');

    // Этажи
    $floors = Estate\EstateFloorTable::getAssoc(array(), 'IMPORT_ID', 'ID');

    // Квартиры
    $flats = Estate\EstateFlatTable::getAssoc(array(), 'IMPORT_ID', 'ID');

    $updateCnt = 0;
    $addCnt    = 0;

    if (!@$f = fopen($DATA_FILE_NAME, 'r')) {
        break;
    }
    $i = 0;
    while ($data = fgetcsv($f, 1000, ';')) {
        if (++$i === 1) {
            continue;
        }

        $buildingId = (int) $data[0];
        if (!$buildingId) {
            continue;
        }
        $sectionName = (int) $data[1];
        $floorName   = (int) $data[2];
        $number      = (int) $data[3];

        $floorId    = $buildingId . '_' . $floorName;
        $importId   = $buildingId . '_' . $floorName . '_' . $number;

        $type = $data[4];
        if($data[10]=='студия'){
            $type = '1ккв(с)';
        }
        if (!isset($roomTypes[$type])) {
            $strError .= 'Не найден тип квариры ' . $type . '<br>';
            continue;
        }
        $typeId = $roomTypes[$type]['ID'];
        $rooms  = $roomTypes[$type]['ROOMS'];

        $areaTotal  = trim(str_replace(',', '.', $data[5]));
        $areaLiving = trim(str_replace(',', '.', $data[7]));

        $priceMeter = preg_replace('!([^0-9.,])!si', '', $data[12]);
        $priceTotal = preg_replace('!([^0-9.,])!si', '', $data[13]);
        $priceMeter = (int) trim(str_replace(',', '.', $priceMeter));
        $priceTotal = (int) trim(str_replace(',', '.', $priceTotal));

        $status = (int) $data[14] === 1
                  ? Estate\EstateFlatTable::IN_SALE_STATUS
                  : Estate\EstateFlatTable::NOT_IN_SALE_STATUS;

        if (empty($buildings[$buildingId])) {
            $res = Estate\EstateBuildingTable::Add(array(
                'NAME'      => $buildingId,
                'ACTIVE'    => 'Y',
                'IMPORT_ID' => $buildingId,
                'PARENT'    => 1,
            ));
            $buildings[$buildingId] = $res->getId();
        }

        // if (empty($sections[$sectionId])) {
        //     $res = Estate\EstateSectionTable::Add(array(
        //         'NAME'      => $data[1],
        //         'PARENT'    => $buildings[$buildingId],
        //         'ACTIVE'    => 'Y',
        //         'IMPORT_ID' => $sectionId,
        //     ));
        //     $sections[$sectionId] = $res->getId();
        // }

        if (empty($floors[$floorId])) {
            $res = Estate\EstateFloorTable::Add(array(
                'NAME'      => $floorName,
                'PARENT'    => $buildings[$buildingId],
                'ACTIVE'    => 'Y',
                'IMPORT_ID' => $floorId,
            ));
            $floors[$floorId] = $res->getId();
        }

        $arRes = array(
            'IMPORT_ID'   => $importId,
            'NAME'        => $number,
            'STATUS'      => $status,
            'PRICE_TOTAL' => $priceTotal,
            'PRICE_METER' => $priceMeter,
            'PARENT'      => $floors[$floorId],
            'ROOMS'       => $rooms,
            'TYPE'        => $typeId,
            'AREA_TOTAL'  => $areaTotal,
            'AREA_LIVING' => $areaLiving,
            'SECTION'     => $sectionName,
        );
        $planoplan = trim($data[15]);
        if(!empty($planoplan)) {
            $arRes['PLANOPLAN'] = $planoplan;
        }

		if (isset($flats[$importId])) {
			// Обновляем
			$id = $flats[$importId];
			Estate\EstateFlatTable::update($id, $arRes);
			unset($flats[$importId]);
			++$updateCnt;
		} else {
			// Добавляем
			$res = Estate\EstateFlatTable::add($arRes);
			$id = $res->getId();
			++$addCnt;
		}
    }
    fclose($f);

    // Изменение статуса квартир которых не было в импорте на "Продано"
    if (!empty($flats)) {
        foreach ($flats as $flatId) {
            Estate\EstateFlatTable::update($flatId, array(
                'STATUS' => Estate\EstateFlatTable::NOT_IN_SALE_STATUS
            ));
        }
    }
    $importSuccess = true;

    $f = fopen($_SERVER['DOCUMENT_ROOT'] . '/upload/import.log', 'a+');
    $log = date('d.m.Y H:i:s') . ': Очередь ' .  $queue . PHP_EOL
        . 'Добавлено квартир: ' . $addCnt . PHP_EOL
        . 'Обновлено квартир: ' . $updateCnt . PHP_EOL;
    fwrite($f, $log);
    fclose($f);

    // Обязательный break, иначе уйдем в бесконечный цикл
    break;
}

function utfStr($str) {
    $str = iconv('WINDOWS-1251', 'UTF-8', $str);
    $str = mb_strtolower($str, 'UTF-8');
    return trim($str);
}

$APPLICATION->SetTitle('Импорт квартир');
require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

CAdminMessage::ShowMessage($strError);
if ($importSuccess) {
    echo CAdminMessage::ShowMessage(array(
        "TYPE"    => "PROGRESS",
        "MESSAGE" => "Импорт прошел успешно",
        "HTML"    => true,
    ));
}
?>
<form method="POST" action="<?= $sDocPath ?>?lang=<?= LANG ?>" ENCTYPE="multipart/form-data" name="dataload" id="dataload">
    <table>
        <tr>
            <td width="40%">Файл CSV:</td>
            <td width="60%">
                <input type="text" name="URL_DATA_FILE" value="<?echo htmlspecialcharsbx($URL_DATA_FILE); ?>" size="30">
                <input type="button" value="Открыть" OnClick="BtnClick()">
                <?CAdminFileDialog::ShowScript(array(
                    'event'        => 'BtnClick',
                    'arResultDest' => array(
                        'FORM_NAME'         => 'dataload',
                        'FORM_ELEMENT_NAME' => 'URL_DATA_FILE',
                    ),
                    'arPath' => array(
                        'SITE' => SITE_ID,
                        'PATH' => '/' . COption::GetOptionString('main', 'upload_dir', 'upload'),
                    ),
                    'select'           => 'F', // F - file only, D - folder only
                    'operation'        => 'O', // O - open, S - save
                    'showUploadTab'    => true,
                    'showAddToMenuTab' => false,
                    'fileFilter'       => 'csv',
                    'allowAllFiles'    => true,
                    'SaveConfig'       => true,
                ));
                ?>
            </td>
        </tr>
    </table>

    <input type="submit" value="Продолжить" name="submit_btn" class="adm-btn-save">

</form>

<?require ($DOCUMENT_ROOT."/bitrix/modules/main/include/epilog_admin.php");

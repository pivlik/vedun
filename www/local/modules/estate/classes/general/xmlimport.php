<?php

use Bitrix\Estate as Estate;

class XmlImport
{
    const BUILD_OBJECT_ID = 1;  //ID Объекта

    protected $_nodeNames = array(
        'id_корпуса'                 => 'building',
        'id_очереди'                 => 'queue',
        'id_секции'                  => 'section',
        'id_этажа'                   => 'floor',
        'Номер'                      => 'number',
        'Статус'                     => 'status',
        'КоличествоКомнат'           => 'rooms',
        'БазоваяЦена'                => 'price_total',
        'ЦенаПри100ПроцентнойОплате' => 'price',
        'ОбщаяПлощадь'               => 'area_total',
        'ЖилаяПлощадь'               => 'area_living',
        'ПлощадьКухни'               => 'area_kitchen',
        'ПлощадьЛоджии'              => 'area_balkon',
        'ПлощадьКомнаты1'            => 'area_room_1',
        'ПлощадьКомнаты2'            => 'area_room_2',
        'Студия'                     => 'is_studio',
    );

    protected $_statuses = array(
        'Свободно' => 1,
        'Не для продажи' => 3,
        'Продано' => 3,
    );

    protected $_requiredFields = array(
        'building', 'section', 'floor', 'number', 'status'
    );


    protected $_buildings = array();
    protected $_sections = array();
    protected $_floors = array();
    protected $_flatTypes = array();


    protected $_logFile = 'import.log';

    protected $_log = array();

    public function import($fileName)
    {
        if (!is_file($fileName)) {
            $this->_log[] = 'Не найден файл импорта';
            return;
        }
        if (!$xml = $this->_openXml($fileName)) {
            return;
        }

        if (!$items = $this->_parseXml($xml)) {
            return;
        }

        $this->_importData($items);

        Estate\EstateFlatTable::clearComponentsCache();
    }

    protected function _openXml($fileName)
    {
        libxml_use_internal_errors(true);

        if (!$xml = simplexml_load_file($fileName)) {
            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                $this->_log[] = $this->_xmlErrorToString($error);
            }
            libxml_clear_errors();
        }
        return $xml;
    }

    protected function _parseXml(SimpleXMLElement $xml)
    {
        if (empty($xml->Объект)) {
            $this->_log[] = 'Не найден узел "Объект"';
            return false;
        }

        $items = array();
        foreach ($xml->Объект as $node) {
            $item = array();
            foreach ($node->Ссылка->Свойство as $param) {
                $this->_parseNode($item, $param);
            }
            foreach ($node->Свойство as $param) {
                $this->_parseNode($item, $param);
            }

            foreach ($this->_requiredFields as $name) {
                if (empty($item[$name])) {
                    $this->_log[] = 'Пустое поле ' . $name
                        . ' в узле нпп=' . $node['Нпп'];
                    continue;
                }
            }
            $item['section_id'] = $item['building'] . '_' .$item['section'];
            $item['floor_id'] = $item['building'] . '_' .$item['section'] . '_' . $item['floor'];
            $item['number_id'] = $item['building'] . '_' .$item['section'] . '_' . $item['floor']. '_' . $item['number'];
            $item['status'] = isset($this->_statuses[$item['status']])
                ? $this->_statuses[$item['status']]
                : Estate\EstateFlatTable::NOT_IN_SALE_STATUS;

            $items[] = $item;
        }
        return $items;
    }

    protected function _importData($data)
    {
        // Собираем IMPORT_ID сущностей
        $importIds = array();
        foreach ($data as $item) {
            $importIds[] = $item['number_id'];
        }

        $existsFlats = Estate\EstateFlatTable::getAssoc(
            array(
                'filter' => array(
                    'IMPORT_ID' => $importIds,
                ),
            ),
            'IMPORT_ID',
            'ID'
        );


        // Типы квартир по комнатам. 0 - студия
        $roomsTypes = Estate\EstateRefFlatTypesTable::getAssoc(
            array(),
            'ROOMS',
            'ID'
        );

        foreach ($data as $item) {
            $buildId = $this->_getBuildingId($item['building']);
            $sectionId = $this->_getSectionId($item['section_id'], $item['section'], $buildId);
            $floorId = $this->_getFloorId($item['floor_id'], $item['floor'], $buildId, $sectionId); //Делаем привязку к корпусу


            // Однокомнатная без кухни = студия
            $roomsType = $item['is_studio'] == 1 ? 0 : $item['rooms'];

            //$type = $this->_getTypeId($item['type'], $item['rooms']);




            // Костыль: цена при 100% оплате всегда равна базовой
            $item['price'] = $item['price_total'];

            $flat = array(
                'PARENT'       => $floorId,
                'IMPORT_ID'    => $item['number_id'],
                'NAME'         => $item['number'],
                'PRICE_TOTAL'  => $item['price_total'],
                'PRICE'        => $item['price'],
                'AREA_TOTAL'   => $item['area_total'],
                'AREA_LIVING'  => $item['area_living'],
                'AREA_KITCHEN' => $item['area_kitchen'],
                'AREA_BALKON'  => $item['area_balkon'],
                'AREA_ROOM_1'  => $item['area_room_1'],
                'AREA_ROOM_2'  => $item['area_room_2'],
                'ROOMS'        => $item['rooms'],
                'TYPE'         => $roomsTypes[$roomsType],
                'STATUS'       => $item['status'],
            );
            if (isset($existsFlats[$item['number_id']])) {
                // Обновление
                $ID = $existsFlats[$item['number_id']];
                $res = Estate\EstateFlatTable::Update($ID, $flat);
                if (!$res->isSuccess()) {
                    $this->_log[] = 'Ошибка при обновлении квартиры ID ' . $ID;
                    $this->_log[] = implode('\r\n' , $res->getErrorMessages());
                    continue;
                }
                $this->_log[] = 'Обновлена квартира ID ' . $ID;
            } else {
                // Добавление
                $res = Estate\EstateFlatTable::Add($flat);
                if (!$res->isSuccess()) {
                    $this->_log[] = 'Ошибка при добавлении квартиры IMPORT_ID '
                        . $flat['IMPORT_ID'] . ': ';
                    $this->_log[] = implode('\r\n' , $res->getErrorMessages());
                    continue;
                }
                $ID = $res->getId();
                $this->_log[] = 'Добавлена квартира ID ' . $ID;

                // Добавляем свойства "Наличие сауны"
//                if ($item['hasSauna'] == "истина") {
//                    $res = Estate\EstateFlatFeaturesTable::Add(array(
//                        'FLAT'    => $ID,
//                        'FEATURE' => self::HAS_SAUNA_OPTION,
//                    ));
//                    if (!$res->isSuccess()) {
//                        $this->_log[] = 'Ошибка при добавлении свойства Наличие сауны ID = '
//                            . $ID . ': ';
//                        $this->_log[] = implode('\r\n' , $res->getErrorMessages());
//                        continue;
//                    }
//                }
            }
        }
    }

    protected function _getTypeId($type, $rooms) {
        if (empty($this->_flatTypes)) {
            $this->_flatTypes = Estate\EstateRefFlatTypesTable::getAssoc(
                array(),
                'NAME',
                'ID'
            );
        }

        if (isset($this->_flatTypes[$type])) {
            return $this->_flatTypes[$type];
        }

        $ins = array(
            'NAME' => $type,
            'ROOMS' => $rooms,
        );
        $res = Estate\EstateRefFlatTypesTable::Add($ins);
        if (!$res->isSuccess()) {
            $this->_log[] = 'Не удалось добавить тип квартиры ' . $type;
            $this->_log[] = implode('\r\n' , $res->getErrorMessages());
        }
        $ID = $res->getId();
        $this->_log[] = 'Добавлен новый тип квартиры ' . $type;

        $this->_flatTypes[$type] = $ID;

        return $ID;
    }

    protected function _getBuildingId($buildingId) {
        if (empty($this->_buildings)) {
            $this->_buildings = Estate\EstateBuildingTable::getAssoc(
                array(),
                'IMPORT_ID',
                'ID'
            );
        }

        if (isset($this->_buildings[$buildingId])) {
            return $this->_buildings[$buildingId];
        }

        $ins = array(
            'NAME' => $buildingId,
            'ACTIVE' => 'Y',
            'PARENT' => XmlImport::BUILD_OBJECT_ID,
            'IMPORT_ID' => $buildingId,
        );
        $res = Estate\EstateBuildingTable::Add($ins);
        if (!$res->isSuccess()) {
            $this->_log[] = 'Не удалось добавить корпус ' . $buildingId;
            $this->_log[] = implode('\r\n' , $res->getErrorMessages());
        }
        $ID = $res->getId();
        $this->_log[] = 'Добавлен новый корпус ' . $buildingId;

        $this->_buildings[$buildingId] = $ID;

        return $ID;
    }

    protected function _getSectionId($sectionId, $section, $buildingId) {
        if (empty($this->_sections)) {
            $this->_sections = Estate\EstateSectionTable::getAssoc(
                array(),
                'IMPORT_ID',
                'ID'
            );
        }

        if (isset($this->_sections[$sectionId])) {
            return $this->_sections[$sectionId];
        }

        $ins = array(
            'NAME' => $section,
            'ACTIVE' => 'Y',
            'PARENT' => $buildingId,
            'IMPORT_ID' => $sectionId,
        );
        $res = Estate\EstateSectionTable::Add($ins);
        if (!$res->isSuccess()) {
            $this->_log[] = 'Не удалось добавить секцию ' . $sectionId;
            $this->_log[] = implode('\r\n' , $res->getErrorMessages());
        }
        $ID = $res->getId();
        $this->_log[] = 'Добавлен новый секцию ' . $sectionId;

        $this->_sections[$sectionId] = $ID;

        return $ID;
    }

    protected function _getFloorId($floorId, $floor, $buildingId, $sectionId) {
        if (empty($this->_floors)) {
            $this->_floors = Estate\EstateFloorTable::getAssoc(
                array(),
                'IMPORT_ID',
                'ID'
            );
        }

        if (isset($this->_floors[$floorId])) {
            return $this->_floors[$floorId];
        }


        $ins = array(
            'NAME' => $floor,
            'ACTIVE' => 'Y',
            'PARENT' => $buildingId,
            'PARENT_SECTION' => $sectionId,
            'IMPORT_ID' => $floorId,
        );
        $res = Estate\EstateFloorTable::Add($ins);
        if (!$res->isSuccess()) {
            $this->_log[] = 'Не удалось добавить этаж ' . $floorId;
            $this->_log[] = implode('\r\n' , $res->getErrorMessages());
        }
        $ID = $res->getId();
        $this->_log[] = 'Добавлен новый этаж ' . $floorId;

        $this->_floors[$floorId] = $ID;

        return $ID;
    }

    protected function _parseNode(&$item, $node)
    {
        $nodeName = (string) $node['Имя'];
        if (!isset($this->_nodeNames[$nodeName])) {
            return false;
        }
        $item[$this->_nodeNames[$nodeName]] = (string) $node->Значение;
    }

    protected function _xmlErrorToString($error)
    {
        $str = '';
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $str .= "Warning {$error->code}: ";
                break;
            case LIBXML_ERR_ERROR:
                $str .= "Error {$error->code}: ";
                break;
            case LIBXML_ERR_FATAL:
                $str .= "Fatal Error {$error->code}: ";
                break;
        }

        $str .= trim($error->message) . PHP_EOL
            . "Line: {$error->line}" . PHP_EOL
            . "Column: {$error->column}";

        return $str;
    }

    public function getLog()
    {
        return $this->_log;
    }

    public function saveLog()
    {
        if (!@$f = fopen($this->_logFile, 'a+')) {
            echo 'Не удалось открыть файл лога ' . $this->_logFile . ' для записи';
            return;
        }
        $log = PHP_EOL . '=====' . PHP_EOL . date('d.m.Y H:i:s') . PHP_EOL
            . implode(PHP_EOL, $this->_log)
            . PHP_EOL . PHP_EOL;

        if (!@fwrite($f, $log)) {
            echo 'Не удалось произвести запись в файл ' . $this->_logFile;
        }
        fclose($f);
    }
}
<?php
define("ADMIN_MODULE_NAME", "kelnik.counters");
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile(__DIR__ . '/menu.php');

if (!$USER->IsAdmin()) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

if (!CModule::IncludeModule(ADMIN_MODULE_NAME)) {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
$counterFilePath = $_SERVER['DOCUMENT_ROOT'] . '/counters.inc';
$counterJSFilePath = $_SERVER['DOCUMENT_ROOT'] . '/scripts/app/counters.js';

$APPLICATION->SetTitle(GetMessage('KELNIK_COUNTERS_ADMIN_MENU_TITLE'));


// form
$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage('KELNIK_COUNTERS_ADMIN_ENTITY_TITLE'), "ICON" => "ad_contract_edit", "TITLE" => GetMessage('KELNIK_COUNTERS_ADMIN_ENTITY_TITLE'))
);
$tabControl = new CAdminTabControl("form_element_kelnik_counters", $aTabs);

$is_create_form = false;
$is_update_form = true;
$isEditMode = true;

$errors = array();

// save action
if ((strlen($save) > 0 || strlen($apply) > 0) && $REQUEST_METHOD == "POST" && check_bitrix_sessid()) {
    $countersValue = $_POST['COUNTERS'];
    file_put_contents($counterFilePath, $countersValue);

    $jsCounter = "define('app/counters', ['jquery'], function($) {" . PHP_EOL . PHP_EOL;
    $jsCounter .= $countersValue . PHP_EOL . PHP_EOL;
    $jsCounter .= "    return {};" . PHP_EOL;
    $jsCounter .= "});";

    file_put_contents($counterJSFilePath, $jsCounter);

}

$counters = @file_get_contents($counterFilePath);
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
if (!empty($errors)) {
    CAdminMessage::ShowMessage(join("\n", $errors));
}
?>
    <form name="form1" method="POST" action="<?= $APPLICATION->GetCurPage() ?>">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
        <?
        $tabControl->Begin();

        $tabControl->BeginNextTab();
        ?>
        <tr>
            <td>
                <textarea style="width: 98%; min-height: 500px" name="COUNTERS"><?= $counters ?></textarea>
            </td>
        </tr>


        <?
        $disable = true;
        if ($isEditMode)
            $disable = false;
        $tabControl->Buttons(array("disabled" => $disable, "back_url" => "index.php?lang=" . LANGUAGE_ID));
        $tabControl->End();
        ?>
    </form>
<?

if ($_REQUEST["mode"] == "list") {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin_js.php");
} else {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
}

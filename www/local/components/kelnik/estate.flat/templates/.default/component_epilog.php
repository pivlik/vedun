<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php $APPLICATION->IncludeComponent("kelnik:estate.recommend", ".default",
    array('FLAT_ID' => $arResult['FLAT']['ID']),
    false
); ?>
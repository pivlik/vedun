<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div data-height="200" data-arrows="false" data-width="100%" class="b-info__list js-mobile-slider">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <?
            $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="b-info__item" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                <img src="<?= $item['PREVIEW_PICTURE']['SRC']; ?>" class="b-info__img">
                <div class="b-info__item-ttl"><?= $item['NAME']; ?></div>
                <div class="b-info__item-txt"><?= $item['DETAIL_TEXT']; ?></div>
            </div>
        <? endforeach; ?>
    </div>
<? endif; ?>

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="l-index-gallery">
        <div class="b-gallery j-gallery">
            <div data-width="100%" data-height="720" data-maxheight="90%" data-arrows="false" data-nav="false"
                 data-loop="true" data-keyboard="true" data-fit="cover" class="b-gallery__base">
                <? foreach ($arResult['ITEMS'] as $item): ?>
                    <?
                    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <a href="<?= $item['DETAIL_PICTURE']['SRC']; ?>" data-caption="<?= $item['NAME']; ?>"
                       id="<?= $this->GetEditAreaId($item['ID']); ?>"></a>
                <? endforeach; ?>
            </div>
            <a href="javascript:;" class="j-gallery__prev b-gallery__arrow b-gallery__arrow_show_prev">Показать
                предыдущий слайд</a><a href="javascript:;"
                                       class="j-gallery__next b-gallery__arrow b-gallery__arrow_show_next">Показать
                следущий слайд</a></div>
    </div>
<? endif; ?>

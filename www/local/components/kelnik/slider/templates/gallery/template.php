<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="l-gallery">
        <div class="b-gallery b-gallery_theme_right-text j-gallery">
            <div data-width="100%" data-height="700" data-arrows="false" data-nav="false" data-loop="true"
                 data-keyboard="true" data-fit="cover" class="b-gallery__base">
                <? foreach ($arResult['ITEMS'] as $item): ?>
                    <?
                    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <div class="b-promo" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                        <div style="background-image: url('<?= $item['DETAIL_PICTURE']['SRC']; ?>')"
                             class="b-promo__img"></div>
                        <div class="b-promo__cnt">
                            <div class="b-promo__sup">Особенности комлекса</div>
                            <div class="b-promo__ttl"><?= html_entity_decode($item['NAME']); ?></div>
                            <div class="b-promo__txt">
                                <p class="hyphenate"><?= $item['DETAIL_TEXT']; ?></p>
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
            <a href="javascript:;" class="j-gallery__prev b-gallery__arrow b-gallery__arrow_show_prev">Показать
                предыдущий слайд</a><a href="javascript:;"
                                       class="j-gallery__next b-gallery__arrow b-gallery__arrow_show_next">Показать
                следущий слайд</a></div>
    </div>
<? endif; ?>

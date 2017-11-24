<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="b-reviews">
        <div class="b-reviews__ttl-wrap">
            <h2 class="b-reviews__ttl">Отзывы<br> покупателей</h2>
        </div>
        <div class="b-reviews__cnt">
            <div class="b-gallery j-gallery b-gallery_theme_arrow-none">
                <div data-width="100%" data-height="400px" data-arrows="false" data-nav="false" data-loop="true"
                     data-keyboard="true" data-fit="cover" class="b-gallery__base">
                    <? foreach ($arResult['ITEMS'] as $item): ?>
                        <?
                        $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        ?>
                        <div class="b-gallery-reviews" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                            <div class="b-gallery-reviews__cite">
                                <blockquote class="hyphenate"><?= $item['PREVIEW_TEXT']; ?>
                                    <!--cite=name-->
                                </blockquote>
                            </div>
                            <div class="b-gallery-reviews__cnt">
                                <div class="b-gallery-reviews__img-wrap"><img
                                        src="<?= $item['PREVIEW_PICTURE']['SRC']; ?>" class="b-gallery-reviews__img">
                                </div>
                                <div class="b-gallery-reviews__cnt-wrap">
                                    <div class="b-gallery-reviews__name"><?= $item['NAME']; ?></div>
                                    <? if ($item['PROPERTIES']['SUB_TITLE']['VALUE']): ?>
                                        <div
                                            class="b-gallery-reviews__who"><?= $item['PROPERTIES']['SUB_TITLE']['VALUE']; ?></div>
                                    <? endif; ?>
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
    </div>
    </div>
<? endif; ?>

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="b-gallery j-gallery b-gallery_theme_advantages">
        <div data-width="100%" data-height="500" data-arrows="false" data-nav="false" data-loop="true"
             data-keyboard="true" data-fit="cover" class="b-gallery__base">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <?
                $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div data-img="<?= $item['PREVIEW_PICTURE']['SRC']; ?>" class="b-gallery-advantages"
                     id="<?= $this->GetEditAreaId($item['ID']); ?>">
                    <div class="b-gallery-advantages__wrap">
                        <div class="b-gallery-advantages__cnt">
                            <div
                                class="b-gallery-advantages__sup"><?= $item['PROPERTIES']['SUB_TITLE']['VALUE']; ?></div>
                            <div class="b-gallery-advantages__ttl"><?= html_entity_decode($item['NAME']); ?></div>
                            <? if ($item['PROPERTIES']['LINK']['VALUE']): ?>
                                <div class="b-gallery-advantages__btn">
                                    <a href="<?= $item['PROPERTIES']['LINK']['VALUE']; ?>"
                                       class="b-btn b-btn_theme_simple b-btn_width_auto">
                                        <?= !empty($item['PROPERTIES']['LINK_TEXT']['VALUE']) ? $item['PROPERTIES']['LINK_TEXT']['VALUE'] : 'Подробная информация'; ?>
                                    </a>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
        <a href="javascript:;" class="j-gallery__prev b-gallery__arrow b-gallery__arrow_show_prev">Показать предыдущий
            слайд</a><a href="javascript:;" class="j-gallery__next b-gallery__arrow b-gallery__arrow_show_next">Показать
            следущий слайд</a></div>
<? endif; ?>

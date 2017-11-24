<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="l-list-news j-masonry">
    <!-- ajax-news-items -->
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="l-list-news__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <article class="b-list-news">
                <div class="b-list-news__cnt">
                    <header>
                        <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                            <h2 class="b-list-news__ttl"><?= $arItem['NAME'] ?></h2></a>
                        <?php if ($arItem['ESTATE_INFO']['DETAIL_PICTURE']): ?>
                            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><img
                                    src="<?= $arItem['ESTATE_INFO']['DETAIL_PICTURE'] ?>"
                                    alt="<?= $arItem['NAME'] ?>"></a>
                        <?php endif; ?>
                    </header>
                    <div>
                        Город, адрес: <? if ($arItem['ESTATE_INFO']['CITY_NAME']) {
                            echo $arItem['ESTATE_INFO']['CITY_NAME'] . ", ";
                        } ?><?= $arItem['ESTATE_INFO']['PROPERTY_ADDRESS_VALUE'] ?>
                    </div>
                    <div>
                        Район, метро: <?= implode(", ", $arItem['ESTATE_INFO']['Raion_Metro']) ?>
                    </div>
                    <div>
                        Кол-во активных корпусов: <?= count($arItem['ESTATE_INFO']['BUILDINGS']) ?>
                    </div>
                    <div>
                        Срок сдачи:
                        <?php if ($arItem['ESTATE_INFO']['TOTAL_READY']): ?>
                            СДАН
                        <? else: ?>
                            <?= implode(" - ", $arItem['ESTATE_INFO']['READY_DATES']) ?>
                        <? endif; ?>

                    </div>
                    <div>
                        Описание: <?= $arItem['DETAIL_TEXT'] ?>
                    </div>
                </div>
            </article>
        </div>
    <? endforeach; ?>
    <!-- ajax-news-items -->
</div>
<?php if ($arResult['NAV_RESULT']->NavPageCount > 1): ?>
    <div class="l-news__btn">
        <a href="?PAGEN_1=<?= $arResult['NAV_RESULT']->NavPageNomer + 1 ?>" data-target="#news"
           class="b-btn b-btn_theme_orange b-btn_height_small j-next">Показать еще</a>
    </div>
<?php endif; ?>


<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="l-list-news j-masonry">
    <!-- ajax-news-items -->
    <?php foreach ($arResult["ITEMS"] as $arItem): ?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        $sectionName = 'Новость';
        if ($arItem['PROPERTIES']['IS_ACTION']['VALUE'] === 'Y') {
            $sectionName = 'Акция';
        }
        ?>
        <div class="l-list-news__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <article
                class="b-list-news <?= $arItem['PROPERTIES']['IS_ACTION']['VALUE'] === 'Y' ? ' b-list-news_theme_action' : '' ?>">
                <header>
                    <?php if ($arItem['PREVIEW_PICTURE']): ?>
                        <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><img src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>"
                                                                         alt="<?= $arItem['NAME'] ?>"></a>
                    <?php endif; ?>
                </header>
                <div class="b-list-news__cnt"><span class="b-list-news__section"><?= $sectionName; ?></span>
                    <time datetime="<?= $arItem['DATE'] ?>"
                          class="b-list-news__time"><?= $arItem['DISPLAY_ACTIVE_FROM'] ?></time>
                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                        <h2 class="b-list-news__ttl"><?= $arItem['NAME'] ?></h2></a>
                    <div class="b-list-news__txt hyphenate">
                        <p><?= $arItem['PREVIEW_TEXT'] ?></p>
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


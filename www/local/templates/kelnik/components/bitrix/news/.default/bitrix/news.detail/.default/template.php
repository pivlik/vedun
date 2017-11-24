<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$sectionName = 'новость';
if ($arResult['PROPERTIES']['IS_ACTION']['VALUE'] === 'Y') {
    $sectionName = 'акция';
}
?>
<div class="l-news">
    <div class="l-news__wrap">
        <article class="b-news">
            <header>
                <span
                    class="b-news__section<?= $arResult['PROPERTIES']['IS_ACTION']['VALUE'] === 'Y' ? ' b-news__section_theme_action' : ''; ?>"><?=$sectionName;?></span>
                <time datetime="<?= $arResult['DATE'] ?>"><?= $arResult['DISPLAY_ACTIVE_FROM'] ?></time>
                <h1><?= $arResult['NAME'] ?></h1>
            </header>
            <? if ($arResult['PROPERTIES']['BLOCKQUOTE']['VALUE']['TEXT']): ?>
                <blockquote class="hyphenate">
                    <? +$arResult['PROPERTIES']['BLOCKQUOTE']['VALUE']['TEXT'] ?>
                    <!--cite=name-->
                </blockquote>
            <? endif; ?>
            <? if ($arResult['DETAIL_PICTURE']['SRC']): ?>
                <img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>">
            <? endif; ?>
            <p class="hyphenate">
                <?= $arResult['DETAIL_TEXT'] ?>
            </p>

            <a href="<?= $arParams['SEF_FOLDER'] ?>">Все акции и новости</a>
        </article>
    </div>
</div>

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<article class="l-article">
    <div class="l-article-hdr l-article-hdr_sub">
        <h1><?= $arParams['TITLE'] ?></h1>
</article>
<?php if (empty($arResult['ITEMS'])): ?>
    <div class="l-article">
        <div class="l-article-col">
            <p>В избранном пусто.</p>
            <p>Нажмите <span class="b-fav-btn__add-star"></span> у карточки квартиры в разделе <a
                    href="<?= $arResult['HOME_PATH'] ?>">Квартиры и цены</a> чтобы добавить ее сюда.</p>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($arResult['ITEMS'])): ?>
        <?php $arResultItems = $arResult['ITEMS']; ?>
        <?php include 'inc_flats.php'; ?>

<?php endif; ?>

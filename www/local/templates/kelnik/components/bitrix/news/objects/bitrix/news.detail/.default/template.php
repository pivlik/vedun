<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="l-news">
    <div class="l-news__wrap">
        <a href="/">Вернуться к списку объектов</a>
        <br>
        <article class="b-news">
            <header>
                <h1><?= $arResult['NAME'] ?></h1>
            </header>
            <? if ($arResult['ESTATE_INFO']['DETAIL_PICTURE']): ?>
                <img src="<?= $arResult['ESTATE_INFO']['DETAIL_PICTURE'] ?>">
            <? endif; ?>
            <div>
                Город, адрес: <? if ($arResult['ESTATE_INFO']['CITY_NAME']) {
                    echo $arResult['ESTATE_INFO']['CITY_NAME'] . ", ";
                } ?><?= $arResult['ESTATE_INFO']['PROPERTY_ADDRESS_VALUE'] ?>
            </div>
            <div>
                Район, метро: <?= implode(", ", $arResult['ESTATE_INFO']['Raion_Metro']) ?>
            </div>
            <div>
                Кол-во активных корпусов: <?= count($arResult['ESTATE_INFO']['BUILDINGS']) ?>
            </div>
            <div>
                Срок сдачи:
                <?php if ($arResult['ESTATE_INFO']['TOTAL_READY']): ?>
                    СДАН
                <? else: ?>
                    <?= implode(" - ", $arResult['ESTATE_INFO']['READY_DATES']) ?>
                <? endif; ?>

            </div>
            <p class="hyphenate">
                <?= $arResult['DETAIL_TEXT'] ?>
            </p>

            <h4>Подменю</h4>
            <ul>
                <li>
                    <a href="/gallery-estate/?object_id=<?= $arResult['ID'] ?>">Ход строительства</a>
                </li>
                <li>
                    <a href="/infrastructure/?object_id=<?= $arResult['ID'] ?>">Инфраструктура</a>
                </li>
                <li>
                    <a href="/dokumenty/?object_id=<?= $arResult['ID'] ?>">Документы</a>
                </li>
                <li>
                    <a href="/kvartiry/?objects%5B<?= $arResult['ID'] ?>%5D=on">Параметрический поиск</a>
                </li>
                <li>
                    <a href="/visual/">Визуальный выборщик</a>
                </li>
            </ul>
        </article>
        <br>
    </div>
</div>

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="l-map">
    <div class="b-map">
        <!-- если на мобилке карту нужно показывать в попапе, то ничего не меняем-->
        <!-- если карту нужно показывать всегда выпиливаем .b-map__mobile и все внутренности-->
        <!-- с с самой карты .j-map убираем data-noinit-->
        <div class="b-map__mobile">
            <div class="b-map__img"></div>
            <div class="b-map__btn">
                <a href="#popup-map" data-target="#map" data-theme="map" class="b-btn j-popup">
                    Развернуть карту
                </a>
            </div>
        </div>

        <div class="b-map__cnt">
            <!--Табы начало-->
            <div class="b-map-tabs">
                <a href="javascript:;" class="b-map-tabs__btn b-btn">Фильтр</a>
                <ul class="b-map-tabs__list">
                    <li class="b-map-tabs__item is-active">
                        <a href="javascript:;" data-type="0" class="b-map-tabs__link">Все</a>
                    </li>
                    <?php foreach ($arResult['GROUP_MARKERS'] as $itemGroup): ?>
                        <li class="b-map-tabs__item">
                            <a href="javascript:;" data-type="<?= $itemGroup['ID'] ?>"
                               class="b-map-tabs__link"><?= $itemGroup['NAME'] ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <!--Табы конец-->

            <!--Карта начало-->
            <div id="map" data-noinit
                 class="j-map b-map__base"<? if (!$arResult['ESTATE_INFO']['MAP_ZOOM']): ?> data-fit="true"<? endif; ?>></div>
            <!--Карта конец-->

            <!--Кастомные зумы начало-->
            <a href="javascript:;" class="b-map__zoom b-map__zoom-in">+</a>
            <a href="javascript:;" class="b-map__zoom b-map__zoom-out">-</a>
            <!--Кастомные зумы конец-->
        </div>
    </div>
</div>

<div id="popup-map" class="mfp-hide b-popup">
    <a href="javascript:;" title="Закрыть" class="b-popup__close">Свернуть карту</a>
    <div class="b-popup__cnt"></div>
</div>

<script>
    window.map = {};
    window.map['map'] = {
        scrollwheel: false,
        center: [<?=$arResult['ESTATE_INFO']['MAP_CENTER_COORD']?>],
        <? if($arResult['ESTATE_INFO']['MAP_ZOOM']): ?>
        zoom: <?=$arResult['ESTATE_INFO']['MAP_ZOOM']?>,
        <? endif; ?>
        <? if($arResult['ESTATE_INFO']['MARKER_3D_COORD'] && $arResult['ESTATE_INFO']['MARKER_3D']['SRC']): ?>
        imageBounds: {
            <?=$arResult['ESTATE_INFO']['MARKER_3D_COORD']?>
            image: '<?=$arResult['ESTATE_INFO']['MARKER_3D']['SRC']?>'
        },
        <? endif; ?>
        markers: [
            <?php foreach ($arResult['ITEMS'] as $item): ?>
            {
                coords: [<?= $item['MAP'] ?>],
                <? if($item['MARKER']['SRC']): ?>
                image: '<?=$item['MARKER']['SRC'] ?>',
                <? endif;?>
                title: '<?= $item['NAME'] ?>',
                type: <?= $item['SECTION_ID'] ?>,
                text: '<?= $item['DETAIL_TEXT'] ?>'
            },
            <?php endforeach;?>
            {
                coords: [<?= $arResult['ESTATE_INFO']['MAP'] ?>],
                <? if($arResult['ESTATE_INFO']['MARKER']['SRC']): ?>
                image: '<?=$arResult['ESTATE_INFO']['MARKER']['SRC'] ?>',
                <? endif;?>
                title: '<?= $arResult['ESTATE_INFO']['NAME'] ?>',
                text: '<?= $arResult['ESTATE_INFO']['SHORT_DESCRIPTION'] ?>'
            },
        ]
    }
</script>

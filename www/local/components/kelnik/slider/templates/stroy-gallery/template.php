<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <?
    $arFotoHeaders = array();
    foreach ($arResult['ITEMS'] as $item) {
        $arFotoHeaders[$item['ID']]['TITLE'] = $item['NAME'];
        $arFotoHeaders[$item['ID']]['COUNT'] = count($item['PROPERTIES']['FOTOS']['VALUE']);
    }
    $arResultHeaders = array();
    $firstItem = array_shift($arFotoHeaders);
    $arResultHeaders[] = '&quot;0&quot;: &quot;' . $firstItem['TITLE'] . '&quot;';
    $cn = $firstItem['COUNT'];
    foreach ($arFotoHeaders as $item) {
        $arResultHeaders[] = '&quot;' . $cn . '&quot;: &quot;' . $item['TITLE'] . '&quot;';
        $cn = $cn + $item['COUNT'];
    }
    ?>
    <div class="b-gallery j-gallery b-gallery_theme_arrow-none">
        <h2 class="b-gallery__ttl">Ход строительства</h2>
        <!--кнопка возможно скоро появится-->
        <!--.b-gallery__btna(href="#iframe" data-type="iframe").b-btn.b-btn_theme_simple Посмотреть видеотрансляцию
-->
        <div data-width="100%" data-height="80%" data-arrows="false" data-nav="thumbs" data-keyboard="true"
             data-fit="cover"
             data-labels="{<?= implode(", ", $arResultHeaders); ?>}"
             data-thumbwidth="100" data-thumbheight="60" class="b-gallery__base">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <? foreach ($item['PROPERTIES']['FOTOS']['VALUE'] as $foto): ?>
                    <a href="<?= $foto['SRC']; ?>"></a>
                <? endforeach; ?>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>

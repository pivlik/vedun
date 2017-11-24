<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (count($arResult['ITEMS'])): ?>
    <?php foreach ($arResult['ITEMS']['FLATS'] as $item): ?>
        <tr>
            <td class="b-search-table__plan-cell">
                <?php if ($item['IMAGE_THUMB']): ?>
                    <img src="<?= $item['IMAGE_THUMB2']['src'] ?>" alt="Планировка"
                         class="b-search-table__small-plan show-only-desktop">
                    <div class="b-search-table__plan-wrap">
                        <img src="<?= $item['IMAGE_THUMB']['src'] ?>" alt="Увеличенная планировка"
                             class="b-search-table__zoom-plan">
                    </div>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= $item['HREF'] ?>"
                   class="link b-search-table__ttl"><?= $arResult['FLAT']['TYPE_NAME'] ?>
                    №<?= $item['NAME'] ?></a>
                <span>с большой гостевой зоной</span>
            </td>
            <td>
                <span class="show-only-desktop"><?= $item['BUILDING']['NAME'] ?></span>
                <div class="hide-on-desktop">
                    <div class="b-search-table__param">
                        <div class="b-search-table__param-name">Корпус</div>
                        <div
                            class="b-search-table__param-data"><?= str_replace("Корпус ", "", $item['BUILDING']['NAME']) ?></div>
                    </div>
                </div>
            </td>
            <td>
                <span class="show-only-desktop"><?= $item['SECTION']['NAME'] ?></span>
                <div class="hide-on-desktop">
                    <div class="b-search-table__param">
                        <div class="b-search-table__param-name">Парадная</div>
                        <div
                            class="b-search-table__param-data"><?= str_replace("Парадная ", "", $item['SECTION']['NAME']) ?></div>
                    </div>
                </div>
            </td>
            <td>
                            <span class="show-only-desktop"><?= $item['FLOOR']['NAME'] ?>
                                /<?= $item['SECTION']['MAX_FLOOR'] ?></span>
                <div class="hide-on-desktop">
                    <div class="b-search-table__param">
                        <div class="b-search-table__param-name">Этаж</div>
                        <div class="b-search-table__param-data"><?= $item['FLOOR']['NAME'] ?></div>
                    </div>
                </div>
            </td>
            <td>
                <div class="show-only-desktop"><span><?= $item['AREA_TOTAL'] ?> м<sup>2</sup></span>
                </div>
                <div class="hide-on-desktop">
                    <div class="b-search-table__param">
                        <div class="b-search-table__param-name">Общая площадь</div>
                        <div class="b-search-table__param-data"><?= $item['AREA_TOTAL'] ?></div>
                    </div>
                </div>
            </td>
            <td class="show-only-desktop"><span>На Тульскую</span></td>
            <td><span class="show-only-desktop">оригинальная планировка</span>
                <div class="hide-on-desktop">
                    <div class="b-search-table__special">Оригинальная планировка</div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr class="b-search-result_hover_none">
        <td colspan="8">
            <h2 class="b-ntf__title">Предложений не найдено</h2>
        </td>
    </tr>
<?php endif; ?>

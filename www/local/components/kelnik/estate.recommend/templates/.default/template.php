<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="l-flat">
        <h2 class="l-flat__title">Похожие квартиры</h2>
        <div class="l-flat__cnt" id="filter-results">
            <?php foreach ($arResult['ITEMS']['FLATS'] as $item): ?>
                <div class="l-flat__item">
                    <div class="b-flat-card">
                        <div class="b-flat-card__header">
                            <div class="b-flat-card__descr">
                                <div class="b-flat-card__floors"><?= $arResult['FLAT']['TYPE_NAME'] ?></div>
                                <a href="<?= $item['HREF'] ?>" class="b-flat-card__ttl">Квартира
                                    № <?= $item['NAME'] ?></a>
                                <div class="b-flat-card__caption">
                                    Корпус <?= str_replace("Корпус ", "", $item['BUILDING']['NAME']) ?>,
                                    этаж <?= $item['FLOOR']['NAME'] ?></div>
                            </div>
                            <div class="b-flat-card__img-wrap">
                                <?php if ($item['IMAGE_THUMB']): ?>
                                    <a href="<?= $item['HREF'] ?>"><img src="<?= $item['IMAGE_THUMB']['src'] ?>"
                                                                        alt="Уменьшенная планировка квартиры"></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="b-flat-card__param">
                            <ul class="b-param-list">
                                <li class="b-param-list__item">
                                    <div class="b-param-list__name">Общая площадь</div>
                                    <div class="b-param-list__data"><?= $item['AREA_TOTAL'] ?> м<sup>2</sup></div>
                                </li>
                                <li class="b-param-list__item">
                                    <div class="b-param-list__name">Жилая площадь</div>
                                    <div class="b-param-list__data"><?= $item['AREA_LIVING'] ?> м<sup>2</sup></div>
                                </li>
                                <li class="b-param-list__item">
                                    <div class="b-param-list__name">Стоимость</div>
                                    <div class="b-param-list__data"> <?= $item['PRICE_TOTAL_F'] ?> <span class="b-ruble">a</span></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>


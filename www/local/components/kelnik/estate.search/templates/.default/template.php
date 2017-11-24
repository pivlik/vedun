<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<a href="/favorite/" class="b-favorite b-favorite_theme_target j-favorite-target<?if ($arResult['TOTAL_FAVORITES']):?> is-active<?endif;?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="240" viewBox="0 0 51 48" class="b-favorite__svg">
        <title>Five Pointed Star</title>
        <path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/>
    </svg>
    <div class="b-favorite__num"><?=$arResult['TOTAL_FAVORITES'];?></div>
</a>

<div class="l-bnt-up hide-on-desktop j-show-up is-nav-up">
    <a href="javascript:;" class="b-btn j-btn-up">
        Наверх
    </a>
</div>

<div class="l-search-filter j-search-filter">
    <form action="/ajax/search.php">
        <input type="hidden" name="sort" value="<?= $arResult['ACTIVE_SORT'] ?>">
        <input type="hidden" name="order" value="<?= $arResult['ACTIVE_ORDER'] ?>">
        <div class="b-search-filter j-search-filter">

            <?php if (!empty($arResult['OBJECTS']) && count($arResult['OBJECTS']) > 1): ?>
                <div class="b-search-filter__select-building">
                    <div class="b-search-filter__ttl">Проекты в продаже</div>
                    <div class="b-search-filter__building-cnt">
                        <?php foreach ($arResult['OBJECTS'] as $ID => $obj): ?>
                            <div class="b-search-filter__build">
                                <input
                                    id="objects-<?= $ID ?>"
                                    type="checkbox"
                                    name="objects[<?= $ID ?>]"
                                    class="b-search-filter__input"
                                    <?= !empty($_REQUEST['objects'][$ID]) ? ' checked' : '' ?><?= !in_array($ID, $arResult['BORDERS']['OBJECTS']) ? ' readonly' . (empty($_REQUEST['objects'][$ID]) ? ' disabled' : '') : '' ?>>

                                <label
                                    for="objects-<?= $ID ?>"
                                    class="b-search-filter__label">
                                    <?= $obj['NAME'] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <? endif; ?>

            <?php if (!empty($arResult['BUILDING'])): ?>
                <div class="b-search-filter__select-building">
                    <div class="b-search-filter__ttl">Срок сдачи</div>
                    <div class="b-search-filter__building-cnt">
                        <?php foreach ($arResult['FILTERS']['BUILDING'] as $iBuilding): ?>
                            <div class="b-search-filter__build">
                                <input
                                    id="building-<?= $iBuilding ?>"
                                    type="checkbox"
                                    name="building[<?= $iBuilding ?>]"
                                    class="b-search-filter__input"
                                    <?= !empty($_REQUEST['building'][$iBuilding]) ? ' checked' : '' ?><?= !in_array($iBuilding, $arResult['BORDERS']['BUILDING']) ? ' readonly' . (empty($_REQUEST['building'][$iBuilding]) ? ' disabled' : '') : '' ?>>

                                <label
                                    for="building-<?= $iBuilding ?>"
                                    class="b-search-filter__label">
                                    <?= $arResult['BUILDING'][$iBuilding]['PROPERTY_READY_DATE_VALUE'] ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <? endif; ?>

            <div class="b-search-filter__floors">
                <div class="b-search-filter__ttl">Комнатность</div>
                <div class="b-search-filter__floors-cnt">
                    <?php foreach ($arResult['FLAT_TYPES'] as $ID => $type): ?>
                        <div class="b-search-filter__floor b-form__checkbox">
                            <input
                                id="apart-<?= $ID ?>"
                                type="checkbox"
                                name="apart[<?= $ID ?>]"
                                <?= !empty($_REQUEST['apart'][$ID]) ? ' checked' : '' ?><?= !in_array($ID, $arResult['BORDERS']['APART']) ? ' readonly' . (empty($_REQUEST['apart'][$ID]) ? ' disabled' : '') : '' ?>>
                            <label
                                for="apart-<?= $ID ?>">
                                <?= $type['NAME'] ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($arResult['PARAM'])): ?>
                <div class="b-search-filter__sub">
                    <div class="b-search-filter__ttl">Особенности</div>
                    <div class="b-search-filter__sub-cnt">
                        <?php foreach ($arResult['PARAM'] as $ID => $name): ?>
                            <div class="b-search-filter__sub-item">
                                <input
                                    id="param-<?= $ID ?>"
                                    type="checkbox"
                                    name="param[<?= $ID ?>]"
                                    class="b-search-filter__input"
                                    <?= !empty($_REQUEST['param'][$ID]) ? ' checked' : '' ?><?= !in_array($ID, $arResult['BORDERS']['PARAM']) ? ' readonly' . (empty($_REQUEST['param'][$ID]) ? ' disabled' : '') : '' ?>>
                                <label
                                    for="param-<?= $ID ?>"
                                    class="b-search-filter__sub-label">
                                    <?= $name ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <? endif; ?>

            <?php if (!empty($arResult['OPTION'])): ?>
                <div class="b-search-filter__sub">
                    <div class="b-search-filter__ttl">Дополнительные параметры</div>
                    <div class="b-search-filter__sub-cnt">
                        <?php foreach ($arResult['OPTION'] as $ID => $name): ?>
                            <div class="b-search-filter__sub">
                                <input
                                    id="option-<?= $ID ?>"
                                    type="checkbox"
                                    name="option[<?= $ID ?>]"
                                    class="b-search-filter__input"
                                    <?= !empty($_REQUEST['option'][$ID]) ? ' checked' : '' ?><?= !in_array($ID, $arResult['BORDERS']['OPTION']) ? ' readonly' . (empty($_REQUEST['option'][$ID]) ? ' disabled' : '') : '' ?>>
                                <label
                                    for="option-<?= $ID ?>"
                                    class="b-search-filter__sub-label">
                                    <?= $name ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <? endif; ?>

            <div class="b-search-filter__prices">
                <div class="b-search-filter__ttl">Стоимость, руб.</div>
                <div class="b-search-filter__fields">
                    <div class="b-search-filter__price">
                        <div class="b-search-filter__wrapper-input">
                            <div class="b-search-filter__range-text">от</div>
                            <input
                                type="text"
                                name="price[min]"
                                data-min="<?= $arResult['FILTERS']['PRICE']['MIN'] ?>"
                                data-max="<?= $arResult['FILTERS']['PRICE']['MAX_VALUE'] ?>"
                                placeholder="<?= $arResult['FILTERS']['PRICE']['MIN_FORMAT'] ?>"
                                value="<?=str_replace(' ', '', $_REQUEST['price']['min'])?>"
                                pattern="[0-9]*"/>
                            <a href="javascript:;" class="b-search-filter__clear-input" title="Очистить поле">×</a>
                        </div>
                    </div>
                    <div class="b-search-filter__price">
                        <div class="b-search-filter__wrapper-input">
                            <div class="b-search-filter__range-text">до</div>
                            <input
                                type="text"
                                name="price[max]"
                                data-min="<?= $arResult['FILTERS']['PRICE']['MIN'] ?>"
                                data-max="<?= $arResult['FILTERS']['PRICE']['MAX_VALUE'] ?>"
                                placeholder="<?= $arResult['FILTERS']['PRICE']['MAX_FORMAT'] ?>"
                                value="<?=str_replace(' ', '', $_REQUEST['price']['max'])?>"
                                pattern="[0-9]*"/>
                            <a href="javascript:;" class="b-search-filter__clear-input" title="Очистить поле">×</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="b-search-filter__areas">
                <div class="b-search-filter__ttl">Жилая площадь, м<sup>2</sup></div>
                <div class="b-search-filter__fields">
                    <div class="b-search-filter__area">
                        <div class="b-search-filter__wrapper-input">
                            <div class="b-search-filter__range-text">от</div>
                            <input
                                type="text"
                                name="area[min]"
                                data-min="<?= $arResult['FILTERS']['AREA']['MIN'] ?>"
                                data-max="<?= $arResult['FILTERS']['AREA']['MAX'] ?>"
                                placeholder="<?= $arResult['FILTERS']['AREA']['MIN'] ?>"
                                value="<?=str_replace(' ', '', $_REQUEST['area']['min'])?>"
                                pattern="[0-9]*"/>
                            <a href="javascript:;" class="b-search-filter__clear-input" title="Очистить поле">×</a>
                        </div>
                    </div>
                    <div class="b-search-filter__area">
                        <div class="b-search-filter__wrapper-input">
                            <div class="b-search-filter__range-text">до</div>
                            <input
                                type="text"
                                name="area[max]"
                                data-min="<?= $arResult['FILTERS']['AREA']['MIN'] ?>"
                                data-max="<?= $arResult['FILTERS']['AREA']['MAX'] ?>"
                                placeholder="<?= $arResult['FILTERS']['AREA']['MAX'] ?>"
                                value="<?=str_replace(' ', '', $_REQUEST['area']['max'])?>"
                                pattern="[0-9]*"/>
                            <a href="javascript:;" class="b-search-filter__clear-input" title="Очистить поле">×</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="b-search-filter__dispatch">
                <div class="b-search-filter__reset">
                    <a href="javascript:;" class="b-btn j-search-reset">
                        Сбросить фильтр
                    </a>
                </div>
                <div class="b-search-filter__submit">
                    <a href="javascript:;" class="b-search-filter__submit-btn b-btn">
                        <?php if ($arResult['CNT']): ?>
                            Посмотреть <?= $arResult['CNT'] ?> <?= $arResult['CNT_WORD'] ?>
                        <?php else: ?>
                            Нет подходящих квартир
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <div class="b-ajax-loader">
        <div class="b-ajax-loader__overlay"></div>
        <svg width="152px" height="152px" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 100 100"
             preserveaspectratio="xMidYMid" class="b-ajax-loader__icon uil-rin">
            <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
            <defs>
                <filter id="uil-ring-shadow" x="-100%" y="-100%" width="300%" height="300%">
                    <feoffset result="offOut" in="SourceGraphic" dx="0" dy="0"></feoffset>
                    <fegaussianblur result="blurOut" in="offOut" stddeviation="0"></fegaussianblur>
                    <feblend in="SourceGraphic" in2="blurOut" mode="normal"></feblend>
                </filter>
            </defs>
            <path
                d="M10,50c0,0,0,0.5,0.1,1.4c0,0.5,0.1,1,0.2,1.7c0,0.3,0.1,0.7,0.1,1.1c0.1,0.4,0.1,0.8,0.2,1.2c0.2,0.8,0.3,1.8,0.5,2.8 c0.3,1,0.6,2.1,0.9,3.2c0.3,1.1,0.9,2.3,1.4,3.5c0.5,1.2,1.2,2.4,1.8,3.7c0.3,0.6,0.8,1.2,1.2,1.9c0.4,0.6,0.8,1.3,1.3,1.9 c1,1.2,1.9,2.6,3.1,3.7c2.2,2.5,5,4.7,7.9,6.7c3,2,6.5,3.4,10.1,4.6c3.6,1.1,7.5,1.5,11.2,1.6c4-0.1,7.7-0.6,11.3-1.6 c3.6-1.2,7-2.6,10-4.6c3-2,5.8-4.2,7.9-6.7c1.2-1.2,2.1-2.5,3.1-3.7c0.5-0.6,0.9-1.3,1.3-1.9c0.4-0.6,0.8-1.3,1.2-1.9 c0.6-1.3,1.3-2.5,1.8-3.7c0.5-1.2,1-2.4,1.4-3.5c0.3-1.1,0.6-2.2,0.9-3.2c0.2-1,0.4-1.9,0.5-2.8c0.1-0.4,0.1-0.8,0.2-1.2 c0-0.4,0.1-0.7,0.1-1.1c0.1-0.7,0.1-1.2,0.2-1.7C90,50.5,90,50,90,50s0,0.5,0,1.4c0,0.5,0,1,0,1.7c0,0.3,0,0.7,0,1.1 c0,0.4-0.1,0.8-0.1,1.2c-0.1,0.9-0.2,1.8-0.4,2.8c-0.2,1-0.5,2.1-0.7,3.3c-0.3,1.2-0.8,2.4-1.2,3.7c-0.2,0.7-0.5,1.3-0.8,1.9 c-0.3,0.7-0.6,1.3-0.9,2c-0.3,0.7-0.7,1.3-1.1,2c-0.4,0.7-0.7,1.4-1.2,2c-1,1.3-1.9,2.7-3.1,4c-2.2,2.7-5,5-8.1,7.1 c-0.8,0.5-1.6,1-2.4,1.5c-0.8,0.5-1.7,0.9-2.6,1.3L66,87.7l-1.4,0.5c-0.9,0.3-1.8,0.7-2.8,1c-3.8,1.1-7.9,1.7-11.8,1.8L47,90.8 c-1,0-2-0.2-3-0.3l-1.5-0.2l-0.7-0.1L41.1,90c-1-0.3-1.9-0.5-2.9-0.7c-0.9-0.3-1.9-0.7-2.8-1L34,87.7l-1.3-0.6 c-0.9-0.4-1.8-0.8-2.6-1.3c-0.8-0.5-1.6-1-2.4-1.5c-3.1-2.1-5.9-4.5-8.1-7.1c-1.2-1.2-2.1-2.7-3.1-4c-0.5-0.6-0.8-1.4-1.2-2 c-0.4-0.7-0.8-1.3-1.1-2c-0.3-0.7-0.6-1.3-0.9-2c-0.3-0.7-0.6-1.3-0.8-1.9c-0.4-1.3-0.9-2.5-1.2-3.7c-0.3-1.2-0.5-2.3-0.7-3.3 c-0.2-1-0.3-2-0.4-2.8c-0.1-0.4-0.1-0.8-0.1-1.2c0-0.4,0-0.7,0-1.1c0-0.7,0-1.2,0-1.7C10,50.5,10,50,10,50z"
                fill="#ff0000"></path>
        </svg>
    </div>
</div>


<div id="search-result" class="l-search-result j-search-result">
    <div class="b-search-result">
        <h2 class="b-search-result__ttl">Результаты поиска</h2>

        <div class="b-search-result__buildings">
            <?
            $totalObjects = count($arResult['RESULT']);
            $totalActiveObjects = count($arResult['OBJECTS']);

            ?>
            <?php foreach ($arResult['RESULT'] as $objectResults): ?>
                <?php
                $is_active = false;
                if ($totalObjects == 1) {
                    $is_active = true;
                }
                if ($_REQUEST['object'] === $objectResults['INFO']['ID']) {
                    $is_active = true;
                }
                if ($arResult['CNT'] === $objectResults['CNT'] && $arResult['CNT'] > 0) {
                    $is_active = true;
                }
                ?>
                <form action="" data-noinit novalidate id="building-<?= $objectResults['INFO']['ID'] ?>">
                    <input type="hidden" name="page" value="<?=$objectResults['page']?>" data-next="<?=$objectResults['next_page']?>">
                    <input type="hidden" name="object" value="<?= $objectResults['INFO']['ID'] ?>">
                    <div class="b-search-result__building">
                        <div class="b-building<? if ($is_active): ?> is-active<? endif; ?>">
                            <? if ($totalActiveObjects > 1): ?>
                                <div class="b-building__cnt"><?= $objectResults['INFO']['NAME'] ?>
                                    <div class="b-building__progress">
                                        <?php if ($objectResults['INFO']['TOTAL_READY']): ?>
                                            СДАН
                                        <? else: ?>
                                            <?= implode(" - ", $objectResults['INFO']['READY_DATES']) ?>
                                        <? endif; ?>
                                    </div>
                                </div>
                                <button
                                    data-text="Показать <?= $objectResults['CNT'] ?> <?= $objectResults['CNT_WORD'] ?>"
                                    class="b-building__more-all b-btn j-btn-more" <?= $objectResults['CNT'] == 0 ? ' style="display:none;"' : '' ?>>
                                    <? if ($is_active): ?>
                                        Скрыть
                                        <? else: ?>
                                        Показать <?= $objectResults['CNT'] ?> <?= $objectResults['CNT_WORD'] ?>
                                    <? endif; ?>
                                </button>
                            <? endif; ?>
                            <div class="b-building__results">
                                <?php $arResultItems = $objectResults['ITEMS']; ?>
                                <?php include 'inc_flats.php'; ?>

                                <div class="b-building__info">
                                    <div class="b-building__more">
                                        <button
                                            type="submit"
                                            class="b-building__more-results b-btn j-building-more"
                                            data-action="<?= $objectResults['NEXT_URL'] ?>" <?= !$objectResults['NEXT_URL'] ? ' style="display:none;"' : '' ?>
                                        "> Показать ещё
                                        </button>
                                    </div>
                                    <div class="b-building__show">
                                       <div class="b-building__shown-text">
                                           Показано <?= $objectResults['SHOW_CNT'] ?>
                                           из <?= $objectResults['CNT'] ?> <?= $objectResults['SHOW_CNT_WORD'] ?>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

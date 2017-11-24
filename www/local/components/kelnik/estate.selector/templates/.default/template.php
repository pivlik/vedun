<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<script>
    window.homePath = '<?= $arResult['HOME_PATH'] ?>visual/';
    window.dataLocation = '/ajax/visual_search.php';

    window.customConfig = {
        <? if($arParams['objectId']): ?>
        /* ID объекта ЖК из настроек компонента */
        objectId: <?=$arParams['objectId']?>,
        /* Настройка шагов выборщика для ЖК */
        <? endif;?>
        buildings: {
            steps: [
                {
                    division: 'homepage',
                    view: 'building',
                    urlTemplate: ''
                },
                {
                    division: 'building',
                    view: 'section',
                    urlTemplate: 'building/:building/'
                },
                {
                    division: 'section',
                    view: 'floor',
                    urlTemplate: 'building/:building/section/:section/'
                },
                {
                    division: 'floor',
                    view: 'flat',
                    urlTemplate: 'building/:building/floor/:floor/'
                }
            ]
        }
    };
</script>

<div id="wrapper" class="al-l-wrapper">
    <div id="substrate" class="al-l-substrate">
        <div id="content" class="al-l-content"></div>

        <video id="video" class="b-video">
            <source src="" type="video/mp4">
            <source src="" type="video/webm">
            <source src="" type="video/ogg">
        </video>
    </div>

    <? if ($arResult['COMPASS_IMAGE']): ?>
        <div class="al-l-compass" style="top: 70%; right: 5%">
            <img class="al-b-compass" src="<?= $arResult['COMPASS_IMAGE'] ?>">
        </div>
    <? endif; ?>

    <div class="al-l-nav">
        <div class="l-header">
            <header class="l-header__wrap">
                <div class="l-header__logo"><a href="/" title="ParkTown" class="b-logo">ParkTown</a></div>
                <div class="l-header__nav">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "top",
                        Array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "DELAY" => "Y",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(""),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top",
                            "USE_EXT" => "N"
                        )
                    ); ?>
                </div>
            </header>
        </div>
    </div>

    <div class="al-l-toggle j-al-nav">
        <a href="javascript:;" class="al-b-toggle">
            <span class="al-b-toggle__icon">
            <i class="b-icons b-icons_image_butter-white"></i>
            </span>
            <span class="al-b-toggle__text">меню</span>
        </a>
    </div>

    <div class="al-l-back-link hide" id="back-link-wrap">
        <a href="" id="back-link" class="al-b-back-link">Выбрать предыдущий шаг</a>
    </div>

    <div class="al-l-name">
        <!--На выборе этажа надо добавить класс b-logo_color_green-->
        <a href="/" class="b-logo j-logo b-logo_color_white">ParkTown</a>
    </div>

    <!--<div class="al-l-dropdown" id="floors-list">
        <div class="b-dropdown j-dropdown">
            <a href="javascript:;" class="b-dropdown__active-floor" id="floor-number"></a>
            <div class="b-dropdown__list-floor" id="floor-number-list"></div>
        </div>
    </div>-->

    <!-- <div class="al-l-floor">
        <div class="al-b-floor">
            <a href="javascript:;" class="al-b-floor__up"></a>
            <div class="al-b-floor__cnt">
                <div class="al-b-floor__num">3</div>
                <div class="al-b-floor__txt">этаж</div>
            </div>
            <a href="javascript:;" class="al-b-floor__down"></a>
        </div>
    </div>

    <!--<div id="tooltip" class="al-b-tooltip">
        <div class="al-b-tooltip__title">2 этаж</div>
        <div class="al-b-tooltip__txt">20 квартир</div>
    </div>-->

    <div class="al-l-mini-filter hide">
        <div class="al-b-mini-filter">
            <div class="al-b-mini-filter__form">
                <form action="#" method="GET">
                    <ul class="al-b-mini-filter__list">
                        <?php foreach ($arResult['FLAT_TYPES'] as $ID => $type): ?>
                            <li class="al-b-mini-filter__item b-form">
                                <input id="filter-item-<?= $ID ?>" class="b-mini-filter" type="checkbox"
                                       name="apart[<?= $ID ?>]" checked="checked"/>
                                <label for="filter-item-<?= $ID ?>"
                                       class="b-mini-filter__label"><?= str_replace(' ', '<br>', $type['NAME']) ?></label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </form>
            </div>
        </div>
    </div>

    <div class="al-l-rotate hide" id="rotate">
        <a href="javascript:;" class="al-b-rotate">Сменить ракурс</a>
    </div>

    <div class="al-l-select-options hide" id="select-options">
        <a href="#parameters" class="al-b-select-options j-popup" data-opacity="false" data-theme="visual"
           data-fullsize>
            <span class="al-b-select-options__txt">Подобрать по цене</span>
        </a>
    </div>
</div>

<div class="al-l-filter mfp-hide" id="parameters">
    <a href="javascript:;" class="mfp-close b-btn b-btn_tt_uppercase b-btn_width_auto">Закрыть</a>
    <div class="al-l-filter__wrap">
        <div class="al-l-filter__col">
            <form id="filter" action="/kvartiry/" data-url="/ajax/search.php" method="GET" data-order="1"
                  data-noinit="data-noinit" class="b-filter"><input type="hidden" name="sort" value="price"/> <input
                    type="hidden" name="order" value="0"/>
                <div class="b-filter__row">
                    <h2>Подбор квартиры</h2>
                </div>
                <div class="b-filter__row">
                    <div class="b-filter__flats">
                        <div class="b-filter__flats-item">
                            <div class="b-filter__list">
                                <?php foreach ($arResult['FLAT_TYPES'] as $ID => $type): ?>
                                    <div class="b-filter__item">
                                        <input id="apart-<?= $ID ?>" type="checkbox" name="apart[<?= $ID ?>]"
                                               class="b-filter__inp-ch" <?= !empty($_REQUEST['apart'][$ID]) ? ' checked' : '' ?><?= !in_array($ID, $arResult['BORDERS']['APART']) ? ' readonly' . (empty($_REQUEST['apart'][$ID]) ? ' disabled' : '') : '' ?>/>
                                        <label for="apart-<?= $ID ?>" class="b-filter__lbl"><?= $type['NAME'] ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php if (!empty($arResult['PARAM'])): ?>
                            <?php foreach ($arResult['PARAM'] as $ID => $name): ?>
                                <div class="b-filter__flats-item">
                                    <input id="param-<?= $ID ?>" type="checkbox" name="param[<?= $ID ?>]"
                                           class="b-filter__inp-ch" <?= !empty($_REQUEST['param'][$ID]) ? ' checked' : '' ?><?= !in_array($ID, $arResult['BORDERS']['PARAM']) ? ' readonly' . (empty($_REQUEST['param'][$ID]) ? ' disabled' : '') : '' ?>/>
                                    <label for="param-<?= $ID ?>" class="b-filter__btn-lbl"><?= $name ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif ?>
                    </div>
                </div>
                <div class="b-filter__row j-filter__range-slider-row">
                    <div class="b-filter__name">Площадь, м<sup>2</sup></div>
                    <div class="b-filter__range-slider">
                        <div data-min='<?= $arResult['FILTERS']['AREA']['MIN'] ?>'
                             data-max='<?= $arResult['FILTERS']['AREA']['MAX'] ?>' data-step='1' data-dist="10"
                             class="j-filter__range-slider"></div>
                        <div class="b-filter__input">
                            <input type="text" value="<?= $arResult['FILTERS']['AREA']['MIN_VALUE'] ?>" maxlength="2"
                                   name="area[min]" pattern="[0-9]*" class="b-filter__inp j-filter__range-slider-min">
                            <input type="text" value="<?= $arResult['FILTERS']['AREA']['MAX_VALUE'] ?>" maxlength="2"
                                   name="area[max]" pattern="[0-9]*" class="b-filter__inp j-filter__range-slider-max">
                        </div>
                    </div>
                </div>
                <div class="b-filter__row j-filter__range-slider-row">
                    <div class="b-filter__name">Стоимость, <span class="b-ruble">a</span></div>
                    <div class="b-filter__range-slider">
                        <div data-min='0' data-max='<?= $arResult['FILTERS']['PRICE']['MAX'] ?>' data-step='100000'
                             data-dist="55" class="j-filter__range-slider"></div>
                        <div class="b-filter__input">
                            <input type="text" value="0" maxlength="8" name="price[min]" pattern="[0-9]*"
                                   class="b-filter__inp j-filter__range-slider-min">
                            <input type="text" value="<?= $arResult['FILTERS']['PRICE']['MAX_VALUE'] ?>" maxlength="8"
                                   name="price[max]" pattern="[0-9]*" class="b-filter__inp j-filter__range-slider-max">
                        </div>
                    </div>
                </div>
                <div class="b-filter__row">
                    <?php if ($arResult['CNT']): ?>
                        <div class="b-filter__btn">
                            <button type="submit" class="b-btn b-btn_tt_uppercase b-btn_width_auto">
                                Посмотреть <?= $arResult['CNT'] ?> <?= $arResult['CNT_WORD'] ?> </button>
                        </div>
                    <?php else: ?>
                        <div class="b-filter__btn">
                            <button type="submit" class="b-btn b-btn_tt_uppercase b-btn_width_auto" disabled>Нет
                                подходящих квартир
                            </button>
                        </div>
                    <?php endif; ?>

                </div>
            </form>
        </div>
    </div>
</div>

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

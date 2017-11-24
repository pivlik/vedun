<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="l-flat">
    <div class="b-flat">
        <div class="b-flat__right">
            <div class="b-flat__check-another-room">
                <!--умная кнопка возвращает на параметрический или визуальный выборщик-->
                <a href="/visual/" class="b-flat__link">Выбрать другую квартиру</a><a
                    href="/kvartiry/flat/<?= $arResult['FLAT']['ID'] ?>/pdf/" class="b-flat__print"><i
                        class="b-icons b-icons_image_print"></i><span>Распечатать</span></a></div>
            <div class="b-flat__right-img">
                <? /* if ($arResult['PLANOPLAN']['tabs']['3d']['content']): ?>
                        <img src="<?= $arResult['PLANOPLAN']['tabs']['3d']['content'] ?>" alt="Планировка квартиры">
                    <? endif */ ?>
                <? if ($arResult['PLANOPLAN']['tabs']['3d']['content']): ?>

                    <? /*
                            <a href="<?= $arResult['PLANOPLAN']['tabs']['3d']['content'] ?>" class="j-popup" data-type="image">
                                <img src="<?= $arResult['PLANOPLAN']['tabs']['3d']['content'] ?>" alt="Карточка квартиры">
                            </a>
                            */ ?>
                    <script type="text/javascript">
                        var el = document.getElementsByClassName('b-flat__right-img');
                        var width = el[0].clientWidth;

                        var planoplanWidgetOptions = {
                            textColor: "#000",
                            activeTab: "3d",
                            width: width,
                            height: 650,
                            lang: "ru",
                            borderColor: "#ffffff",
                            borderWidth: 0,
                            fontFamily: "lato-heavy",
                            fontSize: 12,
                            uid: "<?= $arResult['FLAT']['PLANOPLAN'] ?>"
                        };
                    </script>
                    <script type="text/javascript" src="//widget.planoplan.com/js/widget.js?v20150326"></script>
                <? else: ?>
                <a href="#popup" class="j-popup">
                    <img src="<?= $arResult['FLAT']['IMAGE']['SRC'] ?>" alt="Планировка квартиры" >
                </a>
                <div id="popup" class="mfp-hide b-popup">
                    <a href="javascript:;" title="Закрыть" class="b-popup__close">Свернуть планировку</a>
                    <div class="b-popup__cnt">
                        <img src="<?= $arResult['FLAT']['IMAGE']['SRC'] ?>" alt="Планировка квартиры" >
                    </div>
                </div>
                <? endif ?>
            </div>
        </div>
        <div class="b-flat__left">
            <h1>Квартира № <?= $arResult['FLAT']['NAME'] ?></h1>
            <div class="b-flat__row">
                <ul class="b-param-list">
                    <li class="b-param-list__item">
                        <div class="b-param-list__name">Общая площадь</div>
                        <div class="b-param-list__data"><?= $arResult['FLAT']['AREA_TOTAL_F'] ?> м<sup>2</sup></div>
                    </li>
                    <li class="b-param-list__item">
                        <div class="b-param-list__name">Жилая площадь</div>
                        <div class="b-param-list__data"><?= $arResult['FLAT']['AREA_LIVING_F'] ?> м<sup>2</sup>
                        </div>
                    </li>
                    <li class="b-param-list__item">
                        <div class="b-param-list__name">Кухня</div>
                        <div class="b-param-list__data"><?= $arResult['FLAT']['AREA_KITCHEN_F'] ?> м<sup>2</sup>
                        </div>
                    </li>
                    <li class="b-param-list__item">
                        <div class="b-param-list__name">Балкон</div>
                        <div
                            class="b-param-list__data"><?= intval($arResult['FLAT']['AREA_BALKON']) > 0 ? 'есть' : 'нет' ?></div>
                    </li>
                    <? if ($arResult['FLAT']['PARAMS_STR']): ?>
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Особенности</div>
                            <div
                                class="b-param-list__data"><?= $arResult['FLAT']['PARAMS_STR'] ?></div>
                        </li>
                    <? endif; ?>
                    <? if ($arResult['FLAT']['OPTIONS_STR']): ?>
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Доп. параметры</div>
                            <div
                                class="b-param-list__data"><?= $arResult['FLAT']['OPTIONS_STR'] ?></div>
                        </li>
                    <? endif; ?>
                </ul>
            </div>
            <div class="b-flat__row">
                <div class="b-flat__key">Стоимость при 100% оплате</div>
                <div class="b-flat__val"><?= $arResult['FLAT']['PRICE_TOTAL_F'] ?> <span class="b-ruble">o</span>
                </div>
                <div class="b-flat__btn"><a href="#flat" data-theme="max-width-none"
                                            class="j-popup b-btn b-btn_width_auto">Хочу эту квартиру</a></div>
            </div>
            <div class="b-flat__row">
                <div class="b-flat__col">
                    <div class="b-flat__img-wrap">
                        <? if ($arResult['FLAT']['BUILDING']['IMAGE_IN_OBJECT']): ?>
                            <img src="<?= $arResult['FLAT']['BUILDING']['IMAGE_IN_OBJECT'] ?>"
                                 alt="Планировка района">
                        <? endif; ?>
                    </div>
                    <ul class="b-flat__param-list b-param-list">
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Корпус</div>
                            <div class="b-param-list__data"><?= $arResult['FLAT']['BUILDING']['NAME'] ?></div>
                        </li>
                    </ul>
                </div>
                <div class="b-flat__col">
                    <div class="b-flat__img-wrap">
                        <? if ($arResult['FLAT']['IMAGE_ON_FLOOR']['SRC']): ?>
                            <img src="<?= $arResult['FLAT']['IMAGE_ON_FLOOR']['SRC'] ?>"
                                 alt="Положение на схеме этажа">
                        <? endif; ?>
                    </div>
                    <ul class="b-flat__param-list b-param-list">
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Этаж</div>
                            <div class="b-param-list__data"><?= $arResult['FLAT']['FLOOR']['NAME'] ?>
                                /<?= $arResult['FLAT']['BUILDING']['MAX_FLOOR'] ?></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="flat" class="mfp-hide b-popup"><a href="javascript:;" title="Закрыть" class="b-popup__close">Закрыть
        попап</a>
    <div class="b-popup__cnt">
        <div class="b-popup__ttl">
            <h2>Расскажем подробности</h2></div>
        <div class="b-popup__flat">
            <div class="b-flat-card">
                <div class="b-flat-card__header">
                    <div class="b-flat-card__descr">
                        <div class="b-flat-card__floors"><?= $arResult['FLAT']['TYPE_NAME'] ?></div>
                        <a href="/kvartiry/flat/<?= $arResult['FLAT']['ID'] ?>/" class="b-flat-card__ttl">Квартира
                            № <?= $arResult['FLAT']['NAME'] ?></a>
                        <div class="b-flat-card__caption">Корпус <?= $arResult['FLAT']['BUILDING']['NAME'] ?>,
                            этаж <?= $arResult['FLAT']['FLOOR']['NAME'] ?>
                            из <?= $arResult['FLAT']['BUILDING']['MAX_FLOOR'] ?></div>
                    </div>
                    <div class="b-flat-card__img-wrap">
                        <a href="/kvartiry/flat/<?= $arResult['FLAT']['ID'] ?>/">
                            <? if ($arResult['PLANOPLAN']['images']['2d']): ?>
                                <img src="<?= $arResult['PLANOPLAN']['images']['2d'] ?>"
                                     alt="Планировка квартиры">
                            <? else: ?>
                                <img src="<?= $arResult['FLAT']['IMAGE']['SRC'] ?>" alt="Планировка квартиры">
                            <? endif ?>
                        </a>
                    </div>
                </div>
                <div class="b-flat-card__param">
                    <ul class="b-param-list">
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Общая площадь</div>
                            <div class="b-param-list__data"><?= $arResult['FLAT']['AREA_TOTAL_F'] ?> м<sup>2</sup>
                            </div>
                        </li>
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Жилая площадь</div>
                            <div class="b-param-list__data"><?= $arResult['FLAT']['AREA_LIVING_F'] ?> м<sup>2</sup>
                            </div>
                        </li>
                        <li class="b-param-list__item">
                            <div class="b-param-list__name">Стоимость</div>
                            <div class="b-param-list__data"> <?= $arResult['FLAT']['PRICE_TOTAL_F'] ?> <span
                                    class="b-ruble">a</span></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="b-popup__form">
            <form action="/ajax/call_request.php"
                  data-message="Спасибо, что воспользовались данной услугой. Наш менеджер перезвонить в удобное для вас время.">
                <h3 class="b-popup__desc">Мы перезвоним и расскажем всё о квартире, комплексе и условиях покупки.
                    По будним дням в рабочее время</h3>
                <div class="b-callback">
                    <div class="b-callback__row"><label for="callback-1" class="b-callback__lbl">Как вас
                            зовут</label> <input id="callback-1" type="text" name="name" autocomplete="name"
                                                 placeholder="ФИО" required></div>
                    <div class="b-callback__row"><label for="callback-2" class="b-callback__lbl">Телефон</label>
                        <input id="callback-2" type="tel" name="phone" autocomplete="tel" placeholder="Телефон"
                               required></div>
                    <div class="b-callback__row">
                        <div class="b-callback__btn"><input type="submit" value="Расскажите мне" class="b-btn">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<? $APPLICATION->IncludeComponent('kelnik:mortgage', '',
    array(
        'TYPE' => 'both',
        'PRICE' => $arResult['FLAT']['PRICE_TOTAL'],
    ),
    false
); ?>


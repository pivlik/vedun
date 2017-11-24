<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<article class="l-article">
    <div class="l-article-cols">
        <div class="l-commercial-application-left">
            <h2><?= $arParams['TITLE'] ?></h2>

            <form data-abide="" action="/ajax/commercial_order.php" method="POST" data-header="Ваша заявка успешно отправлена." data-message="В ближайшее время с вами свяжется наш специалист.">
                <input type="hidden" name="ID" value="<?= $arResult['ITEM']['ID'] ?>" />
                <p class="b-article-hdr-lead">
                    Если Вы хотите открыть бизнес на территории «Нового Зеленограда», пожалуйста, заполните анкету.
                </p>

                <div class="b-tabs-radio">
                    <input class="is-invisible">
                    <div class="b-tabs-radio__item">
                        <input class="b-tabs-radio__inp" type="radio" id="b-tab-1" name="action" value="rent" checked>
                        <label class="b-tabs-radio__lbl" for="b-tab-1">Хочу арендовать</label>
                    </div>

                    <div class="b-tabs-radio__item">
                        <input class="b-tabs-radio__inp" type="radio" id="b-tab-2" name="action" value="buy">
                        <label class="b-tabs-radio__lbl" for="b-tab-2">Хочу купить</label>
                    </div>
                </div>

                <div class="b-commercial-profile">
                    <h3 class="b-commercial-profile__title">Контактные данные</h3>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__name">
                            <span class="b-commercial-profile__lbl-name">
                                ФИО
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input type="text" class="b-commercial-profile__inp" id="b-commercial-profile__name" name="name" autocomplete="name" required/>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__phone">
                            <span class="b-commercial-profile__lbl-name">
                                Контактный телефон
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input id="b-commercial-profile__phone" type="tel" name="phone" autocomplete="tel" required/>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__email">
                            <span class="b-commercial-profile__lbl-name">
                                Электронная почта
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input id="b-commercial-profile__email" type="email" name="email" autocomplete="email" required/>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <div class="b-commercial-profile__lbl">
                            <span class="b-commercial-profile__lbl-name">
                                &nbsp;
                            </span>
                        </div>
                        <div class="b-commercial-profile__field-wrap">
                            <input type="checkbox" name="living" /> Я живу в &laquo;Новом Зеленограде&raquo;
                            <div class="b-commercial-profile__lbl-field-txt">Жителям предоставляются льготные условия</div>
                        </div>
                    </div>
                </div>

                <div class="b-commercial-profile">
                    <h3 class="b-commercial-profile__title">Расскажите о своем бизнессе</h3>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__org">
                            <span class="b-commercial-profile__lbl-name">
                                Какой вид бизнесса <br/> выхотите открыть
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input id="b-commercial-profile__org" type="text" name="business_type" required/>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <div class="b-commercial-profile__lbl">
                            <span class="b-commercial-profile__lbl-name">
                                &nbsp;
                            </span>
                        </div>
                        <div class="b-commercial-profile__field-wrap">
                            <input type="checkbox" name="business_have" />
                            <span>У меня уже есть подобный бизнес</span>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__name-org">
                            <span class="b-commercial-profile__lbl-name">
                                Название фирмы
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input id="b-commercial-profile__name-org" type="text" name="business_name" required/>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__address">
                            <span class="b-commercial-profile__lbl-name">
                                Фактический адрес
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input id="b-commercial-profile__address" type="text" name="business_address" autocomplete="street-address" required/>
                        </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <label  class="b-commercial-profile__lbl" for="b-commercial-profile__url">
                            <span class="b-commercial-profile__lbl-name">
                                Адрес сайта <br/>(если есть)
                            </span>
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input id="b-commercial-profile__url" name="business_site" type="text"/>
                         </div>
                    </div>

                    <div class="b-commercial-profile__row">
                        <label class="b-commercial-profile__lbl" for="b-commercial-profile__email">
                            &nbsp;
                        </label>
                        <div class="b-commercial-profile__field-wrap">
                            <input class="button btn-yellow" type="submit" value="Отправить заявку"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="l-commercial-application-right">
            <div class="b-mini-card-room b-mini-card-room_size_small">
                <?php if ($arResult['ITEM']['IMAGE']): ?>
                    <div class="b-mini-card-room__img-wrap">
                        <img class="b-mini-card-room__img" src="<?= $arResult['ITEM']['IMAGE']['SRC'] ?>" alt="Паркинг"/>
                    </div>
                <?php endif; ?>
                <div class="b-mini-card-room__cont">
                    <div class="b-mini-card-room__title">Коммерческое помещение</div>
                    <div class="b-mini-card-room__location">Корпус <?= $arResult['ITEM']['BUILDING_NAME'] ?>, секция <?= $arResult['ITEM']['SECTION_NAME'] ?></div>
                    <div class="b-mini-card-room__info">
                        <span class="b-mini-card-room__info-key">Общая площадь: </span>
                        <span class="b-mini-card-room__info-val"><?= $arResult['ITEM']['AREA_TOTAL'] ?> м<sup>2</sup></span>
                    </div>
                    <div class="b-mini-card-room__info">
                        <span class="b-mini-card-room__info-key">Статус: </span>
                        <span class="b-mini-card-room__info-val"><?= $arResult['ITEM']['STATUS'] ?></span>
                    </div>
                    <?php if ($arResult['ITEM']['PRICE']): ?>
                        <div class="b-mini-card-room__info">
                            <span class="b-mini-card-room__info-key">Аренда: </span>
                                <span class="b-mini-card-room__info-val">
                                    <?= $arResult['ITEM']['PRICE'] ?>
                                    <span class="b-mini-card-room__info-ruble"><?= RUBLE ?></span>
                                    /мес.
                                </span>
                        </div>
                    <?php endif; ?>
                    <?php if ($arResult['ITEM']['PRICE_LEASE']): ?>
                        <div class="b-mini-card-room__info">
                            <span class="b-mini-card-room__info-key">Покупка: </span>
                                <span class="b-mini-card-room__info-val">
                                    <?= $arResult['ITEM']['PRICE_LEASE'] ?>
                                    <span class="b-mini-card-room__info-ruble"><?= RUBLE ?></span>
                                </span>
                        </div>
                    <?php endif; ?>
                    <?php if ($arResult['ITEM']['TEXT']): ?>
                        <div class="b-mini-card-room__comment">
                            <?= $arResult['ITEM']['TEXT'] ?>
                        </div>
                    <?php endif; ?>

                    <a class="b-mini-card-room__link" href="<?= $arParams['SEF_FOLDER'] ?>">Выбрать другое помещение</a>
                </div>
                <a href="commercial-application.php" class="button b-mini-card-room__btn">Оправить заявку</a>
            </div>
        </div>
    </div>
</article>

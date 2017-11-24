<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if ($arResult["GALLERY"]): ?>
    <div class="l-construction-progress j-construction-progress">
        <div class="b-construction-progress">
            <h2 class="b-construction-progress__ttl">Ход строительства</h2>
            <div class="b-construction-progress__row">
                <form action="/ajax/gallery_estate.php">
                    <div class="b-construction-progress__selects">
                        <div class="b-construction-progress__select">
                            <? if (count($arResult['selects']["period"])): ?>
                                <select name="period">
                                    <? foreach ($arResult['selects']["period"] as $elPeriod): ?>
                                        <option
                                            value="<?= $elPeriod['value'] ?>"<? if ($elPeriod['isSelect']): ?> selected<? endif; ?>><?= $elPeriod['text'] ?></option>
                                    <? endforeach; ?>
                                </select>
                            <? endif; ?>
                        </div>
                        <div class="b-construction-progress__select">
                            <? if (count($arResult['selects']["gallery"]) > 1): ?>
                                <select name="gallery">
                                    <? foreach ($arResult['selects']["gallery"] as $elBuilding): ?>
                                        <option
                                            value="<?= $elBuilding['value'] ?>"<? if ($elBuilding['isSelect']): ?> selected<? endif; ?>><?= $elBuilding['text'] ?></option>
                                    <? endforeach; ?>
                                </select>
                            <? endif; ?>
                        </div>
                    </div>
                    <input type="hidden" name="object_id" value="<?=$arParams['OBJECT_ID']?>">
                </form>
                <div class="b-construction-progress__progress">
                    <div class="b-progress-bar">
                        <div
                            class="b-progress-bar__base<? if ($arResult["GALLERY"]['IS_READY']): ?> is-inactive<? endif; ?>">
                            <div class="b-progress-bar__line">
                                <div style="width: <?= $arResult["GALLERY"]['PROGRESS'] ?>%"
                                     class="b-progress-bar__current"></div>
                            </div>
                            <div class="b-progress-bar__text"><?= $arResult["GALLERY"]['PROGRESS'] ?>%</div>
                        </div>
                        <div
                            class="b-progress-bar__done <? if ($arResult["GALLERY"]['IS_READY']): ?> is-active<? endif; ?>">
                            Дом сдан
                        </div>
                    </div>
                    <div
                        class="b-construction-progress__deadline<? if ($arResult["GALLERY"]['IS_READY']): ?> is-inactive<? endif; ?>"><?= $arResult["GALLERY"]["READY_DATE"] ?></div>
                </div>
            </div>
            <div class="b-construction-progress__row">
                <div class="b-construction-progress__gallery">
                    <div class="b-gallery j-gallery">
                        <div
                            data-width="100%"
                            data-arrows="false"
                            data-nav="thumbs"
                            data-loop="false"
                            data-keyboard="true"
                            data-thumbwidth="120px"
                            data-fit="contain"
                            class="b-gallery__base">
                            <? foreach ($arResult["GALLERY"]['FOTOS'] as $foto): ?>
                                <a href="<?= $foto['SRC'] ?>" data-thumb="<?= $foto['SRC'] ?>">
                                    <?= $foto['DESCRIPTION'] ?>
                                </a>
                            <? endforeach; ?>
                        </div>
                            <a href="#gallery-popup" class="b-gallery__increase">Увеличить</a>
                            <a href="javascript:;" class="j-gallery__prev b-gallery__arrow b-gallery__arrow_show_prev">Показать предыдущий слайд</a>
                            <a href="javascript:;" class="j-gallery__next b-gallery__arrow b-gallery__arrow_show_next">Показать следущий слайд</a>
                        </div>
                </div>
                <div class="b-construction-progress__content b-typo-reset">
                    <?= $arResult["GALLERY"]['DETAIL_TEXT'] ?>
                </div>
            </div>
        </div>
        <div id="gallery-popup" class="mfp-hide b-popup"><a href="javascript:;" title="Закрыть" class="b-popup__close">Закрыть попап</a>
            <div class="b-popup__cnt"></div>
        </div>
        <div class="b-ajax-loader">
            <div class="b-ajax-loader__overlay"></div> <svg width="152px" height="152px" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 100 100" preserveaspectratio="xMidYMid" class="b-ajax-loader__icon uil-rin">
              <rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect>
              <defs>
                <filter id="uil-ring-shadow" x="-100%" y="-100%" width="300%" height="300%">
                  <feoffset result="offOut" in="SourceGraphic" dx="0" dy="0"></feoffset>
                  <fegaussianblur result="blurOut" in="offOut" stddeviation="0"></fegaussianblur>
                  <feblend in="SourceGraphic" in2="blurOut" mode="normal"></feblend>
                </filter>
              </defs>
              <path d="M10,50c0,0,0,0.5,0.1,1.4c0,0.5,0.1,1,0.2,1.7c0,0.3,0.1,0.7,0.1,1.1c0.1,0.4,0.1,0.8,0.2,1.2c0.2,0.8,0.3,1.8,0.5,2.8 c0.3,1,0.6,2.1,0.9,3.2c0.3,1.1,0.9,2.3,1.4,3.5c0.5,1.2,1.2,2.4,1.8,3.7c0.3,0.6,0.8,1.2,1.2,1.9c0.4,0.6,0.8,1.3,1.3,1.9 c1,1.2,1.9,2.6,3.1,3.7c2.2,2.5,5,4.7,7.9,6.7c3,2,6.5,3.4,10.1,4.6c3.6,1.1,7.5,1.5,11.2,1.6c4-0.1,7.7-0.6,11.3-1.6 c3.6-1.2,7-2.6,10-4.6c3-2,5.8-4.2,7.9-6.7c1.2-1.2,2.1-2.5,3.1-3.7c0.5-0.6,0.9-1.3,1.3-1.9c0.4-0.6,0.8-1.3,1.2-1.9 c0.6-1.3,1.3-2.5,1.8-3.7c0.5-1.2,1-2.4,1.4-3.5c0.3-1.1,0.6-2.2,0.9-3.2c0.2-1,0.4-1.9,0.5-2.8c0.1-0.4,0.1-0.8,0.2-1.2 c0-0.4,0.1-0.7,0.1-1.1c0.1-0.7,0.1-1.2,0.2-1.7C90,50.5,90,50,90,50s0,0.5,0,1.4c0,0.5,0,1,0,1.7c0,0.3,0,0.7,0,1.1 c0,0.4-0.1,0.8-0.1,1.2c-0.1,0.9-0.2,1.8-0.4,2.8c-0.2,1-0.5,2.1-0.7,3.3c-0.3,1.2-0.8,2.4-1.2,3.7c-0.2,0.7-0.5,1.3-0.8,1.9 c-0.3,0.7-0.6,1.3-0.9,2c-0.3,0.7-0.7,1.3-1.1,2c-0.4,0.7-0.7,1.4-1.2,2c-1,1.3-1.9,2.7-3.1,4c-2.2,2.7-5,5-8.1,7.1 c-0.8,0.5-1.6,1-2.4,1.5c-0.8,0.5-1.7,0.9-2.6,1.3L66,87.7l-1.4,0.5c-0.9,0.3-1.8,0.7-2.8,1c-3.8,1.1-7.9,1.7-11.8,1.8L47,90.8 c-1,0-2-0.2-3-0.3l-1.5-0.2l-0.7-0.1L41.1,90c-1-0.3-1.9-0.5-2.9-0.7c-0.9-0.3-1.9-0.7-2.8-1L34,87.7l-1.3-0.6 c-0.9-0.4-1.8-0.8-2.6-1.3c-0.8-0.5-1.6-1-2.4-1.5c-3.1-2.1-5.9-4.5-8.1-7.1c-1.2-1.2-2.1-2.7-3.1-4c-0.5-0.6-0.8-1.4-1.2-2 c-0.4-0.7-0.8-1.3-1.1-2c-0.3-0.7-0.6-1.3-0.9-2c-0.3-0.7-0.6-1.3-0.8-1.9c-0.4-1.3-0.9-2.5-1.2-3.7c-0.3-1.2-0.5-2.3-0.7-3.3 c-0.2-1-0.3-2-0.4-2.8c-0.1-0.4-0.1-0.8-0.1-1.2c0-0.4,0-0.7,0-1.1c0-0.7,0-1.2,0-1.7C10,50.5,10,50,10,50z" fill="#ff0000"></path>
        </svg> </div>
    </div>
<? endif; ?>

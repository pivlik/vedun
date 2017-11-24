<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <? foreach ($arResult['MORTGAGE'] as $key => $item): ?>
        <div class="l-banks__item b-bank">
            <div class="b-bank__row">
                <div class="b-bank__col"><img src="<?= $arResult['BANKS'][$key]['IMG']['SRC'] ?>" class="b-bank__img">
                </div>
                <div class="b-bank__col"><a href="<?= $arResult['BANKS'][$key]['LINK'] ?>" class="b-bank__link"
                                            target="_blank">Условия программы</a></div>
            </div>
            <div class="b-bank__row b-bank__row_border_bottom">
                <div class="b-bank__col">
                    <div class="b-bank__key">минимальный кредит</div>
                    <div class="b-bank__val">от <?= $item['PROPERTY_MIN_CREDIT_VALUE'] ?> <span class="b-ruble">o</span></div>
                </div>
                <div class="b-bank__col">
                    <div class="b-bank__key">процентная ставка</div>
                    <div class="b-bank__val">от <?= $item['PROPERTY_RATE_VALUE'] ?>%</div>
                </div>
            </div>
            <div class="b-bank__row">
                <div class="b-bank__col">
                    <div class="b-bank__key">первый взнос</div>
                    <div class="b-bank__val">от <?= $item['PROPERTY_MIN_PAYMENT_VALUE'] ?>%</div>
                </div>
                <div class="b-bank__col">
                    <div class="b-bank__key">срок кредита</div>
                    <div class="b-bank__val">до <?= $item['PROPERTY_MAX_TIME_VALUE'] ?> лет</div>
                </div>
            </div>
        </div>
    <? endforeach; ?>
<? endif; ?>
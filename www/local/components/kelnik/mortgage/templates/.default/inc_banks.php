<?php foreach ($arResult['MORTGAGE'] as $ID => $programm): ?>
    <? if (in_array($programm['ID'], $arResult['ACTIVE_MORTGAGE'])): ?>
        <div class="l-mortgage__banks-item">
            <div class="l-banks__item b-bank">
                <div class="b-bank__row">
                    <div class="b-bank__col"><img src="<?= $arResult['BANKS'][$programm['BANK']]['IMG']['SRC'] ?>"
                                                  class="b-bank__img"></div>
                    <div class="b-bank__col"><a href="<?= $programm['LINK'] ?>" target="_blank" class="b-bank__link">Условия
                            программы</a></div>
                </div>
                <div class="b-bank__row b-bank__row_border_bottom">
                    <div class="b-bank__col">
                        <div class="b-bank__key">минимальный кредит</div>
                        <div class="b-bank__val">
                            от <?= isset($programm['MIN_SUMM']) ? $programm['MIN_SUMM_F'] . '' : '&mdash;' ?>
                            <span class="b-ruble">o</span></div>
                    </div>
                    <div class="b-bank__col">
                        <div class="b-bank__key">процентная ставка</div>
                        <div class="b-bank__val">
                            от <?= isset($programm['RATE']) ? $programm['RATE'] . '%' : '&mdash;' ?></div>
                    </div>
                </div>
                <div class="b-bank__row">
                    <div class="b-bank__col">
                        <div class="b-bank__key">первый взнос</div>
                        <div class="b-bank__val">
                            от <?= isset($programm['MIN_PAYMENT']) ? $programm['MIN_PAYMENT'] . '%' : '&mdash;' ?></div>
                    </div>
                    <div class="b-bank__col">
                        <div class="b-bank__key">срок кредита</div>
                        <div class="b-bank__val">
                            до <?= isset($programm['MAX_TIME']) ? $programm['MAX_TIME'] . ' ' . $programm['MAX_TIME_WORD'] : '&mdash;' ?></div>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
<?php endforeach; ?>


<?php foreach ($arResult['MORTGAGE'] as $ID => $bank): ?>
    <? if (!in_array($programm['ID'], $arResult['ACTIVE_MORTGAGE'])): ?>
        <div class="l-mortgage__banks-item">
            <div class="l-banks__item b-bank b-bank_is_disabled">
                <div class="b-bank__row">
                    <div class="b-bank__col"><img src="<?= $arResult['BANKS'][$programm['BANK']]['IMG']['SRC'] ?>"
                                                  class="b-bank__img"></div>
                    <div class="b-bank__col"><a href="<?= $programm['LINK'] ?>" target="_blank" class="b-bank__link">Условия
                            программы</a></div>
                </div>
                <div class="b-bank__row b-bank__row_border_bottom">
                    <div class="b-bank__col">
                        <div class="b-bank__key">минимальный кредит</div>
                        <div class="b-bank__val">
                            от <?= isset($programm['MIN_CREDIT']) ? $programm['MIN_CREDIT'] . '' : '&mdash;' ?>
                            <span class="b-ruble">o</span></div>
                    </div>
                    <div class="b-bank__col">
                        <div class="b-bank__key">процентная ставка</div>
                        <div class="b-bank__val">
                            от <?= isset($programm['RATE']) ? $programm['RATE'] . '%' : '&mdash;' ?></div>
                    </div>
                </div>
                <div class="b-bank__row">
                    <div class="b-bank__col">
                        <div class="b-bank__key">первый взнос</div>
                        <div class="b-bank__val">
                            от <?= isset($programm['MIN_PAYMENT']) ? $programm['MIN_PAYMENT'] . '%' : '&mdash;' ?></div>
                    </div>
                    <div class="b-bank__col">
                        <div class="b-bank__key">срок кредита</div>
                        <div class="b-bank__val">
                            до <?= isset($programm['MAX_TIME']) ? $programm['MAX_TIME'] . ' ' . $programm['MAX_TIME_WORD'] : '&mdash;' ?></div>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
<?php endforeach; ?>

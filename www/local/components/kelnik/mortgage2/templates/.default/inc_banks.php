<?php foreach ($arResult['BANKS'] as $ID => $bank): ?>


	<div class="b-calculator__banks ">
		<!-- для выключения из списка добавить класс is-disabled-->
		<section class="b-card-bank <?= !isset($arResult['MORTGAGE'][$ID]) ? ' is-disabled' : '' ?>">
			<div class="b-card-bank__row">
				<div class="b-card-bank__img-wrap"><img src="<?= $bank['IMG']['SRC'] ?>" alt="Банк" class="b-card-bank__img"></div>
			</div>
			<div class="b-card-bank__row">
				<div class="b-card-bank__col">
					<div class="b-card-bank__txt-hdr">
						<div class="b-card-bank__txt-hdr-wrap">Eжемесячный платеж</div>
					</div>
					<div class="b-card-bank__txt-ftr"><?= isset($arResult['MORTGAGE'][$ID]) ? $arResult['MORTGAGE'][$ID]['PAYMENT'] . '' : '&mdash;' ?> руб</div>
				</div>
				<div class="b-card-bank__col">
					<div class="b-card-bank__txt-hdr">
						<div class="b-card-bank__txt-hdr-wrap">Процентная ставка</div>
					</div>
					<div class="b-card-bank__txt-ftr">От <?= isset($arResult['MORTGAGE'][$ID]) ? $arResult['MORTGAGE'][$ID]['RATE'] . '%' : '&mdash;' ?></div>
				</div>
			</div>
			<div class="b-card-bank__row">
				<div class="b-card-bank__col">
					<div class="b-card-bank__txt-hdr">
						<div class="b-card-bank__txt-hdr-wrap">Первый взнос</div>
					</div>
					<div class="b-card-bank__txt-ftr">От <?= isset($arResult['MORTGAGE'][$ID]) ? $arResult['MORTGAGE'][$ID]['MIN_PAYMENT'] . '%' : '&mdash;' ?></div>
				</div>
				<div class="b-card-bank__col">
					<div class="b-card-bank__txt-hdr">
						<div class="b-card-bank__txt-hdr-wrap">Срок кредита</div>
					</div>
					<div class="b-card-bank__txt-ftr">До <?= isset($arResult['MORTGAGE'][$ID]) ? $arResult['MORTGAGE'][$ID]['MAX_TIME'] . ' ' . $arResult['MORTGAGE'][$ID]['MAX_TIME_WORD'] : '&mdash;' ?></div>
				</div>
			</div>
			<?if($bank['LINK']): ?>
				<div class="b-card-bank__row">
					<a href="<?= $bank['LINK'] ?>" class="b-card-bank__link">Подробные условия на сайте банка</a>
				</div>
			<?endif?>
		</section>
	</div>
<?php endforeach; ?>

<?php foreach ($arResult['ITEMS'] as $ID => $item): ?>
    <li class="l-commercial-premises-item" >
        <div class="b-mini-card-room">
            <?php if ($item['IMAGE']['SRC']): ?>
                <div class="b-mini-card-room__img-wrap">
                    <img class="b-mini-card-room__img" src="<?= $item['IMAGE']['SRC'] ?>" alt="Паркинг"/>
                </div>
            <?php endif; ?>
            <div class="b-mini-card-room__cont">
                <div class="b-mini-card-room__title"><?= $item['NAME'] ?></div>
                <div class="b-mini-card-room__location">Корпус <?= $item['BUILDING_NAME'] ?>, секция <?= $item['SECTION_NAME'] ?></div>
                <div class="b-mini-card-room__info">
                    <span class="b-mini-card-room__info-key">Общая площадь: </span>
                    <span class="b-mini-card-room__info-val"><?= $item['AREA_TOTAL'] ?> м<sup>2</sup></span>
                </div>
                <?php if ($item['STATUS']): ?>
                    <div class="b-mini-card-room__info">
                        <span class="b-mini-card-room__info-key">Статус: </span>
                        <span class="b-mini-card-room__info-val"><?= $item['STATUS'] ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($item['PRICE_LEASE']): ?>
                    <div class="b-mini-card-room__info">
                        <span class="b-mini-card-room__info-key">Аренда: </span>
                        <span class="b-mini-card-room__info-val">
                            <?= $item['PRICE_LEASE'] ?>
                            <span class="b-mini-card-room__info-ruble"><?= RUBLE ?></span>
                            /мес.
                        </span>
                    </div>
                <?php endif; ?>
                <?php if ($item['PRICE']): ?>
                    <div class="b-mini-card-room__info">
                        <span class="b-mini-card-room__info-key">Покупка: </span>
                        <span class="b-mini-card-room__info-val">
                            <?= $item['PRICE'] ?>
                            <span class="b-mini-card-room__info-ruble"><?= RUBLE ?></span>
                        </span>
                    </div>
                <?php endif; ?>
                <div class="b-mini-card-room__comment">
                    <?= $item['TEXT'] ?>
                </div>
            </div>
            <?php if (empty($arParams['HIDE_BUTTON'])): ?>
                <a href="<?= $arParams['SEF_FOLDER'] ?>order/?ID=<?= $ID ?>" class="button b-mini-card-room__btn">Отправить заявку</a>
            <?php endif; ?>
        </div>
    </li>
<?php endforeach; ?>

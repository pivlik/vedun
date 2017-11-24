<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
    <table data-action="" class="b-building__table">
        <thead>
             <tr>
                 <td>План</td>
                 <td>Квартира</td>
                 <td>Комнат</td>
                 <td>Корпус</td>
                 <td>Этаж</td>
                 <td>Общая</td>
                 <td>Жилая</td>
                 <td>При 100% оплате</td>
                 <td></td>
             </tr>
         </thead>
        <tbody>
        <?php if (count($arResultItems)): ?>
        <?php foreach ($arResultItems as $item): ?>
            <tr>
                <td>
                    <? if ($item['PLANOPLAN_CONTENT']['images']['2d']): ?>
                        <img
                            src="<?= $item['PLANOPLAN_CONTENT']['images']['2d'] ?>"
                            alt="Планировка"
                            class="b-building__plan">
                    <? else: ?>
                        <img src="<?= $item['IMAGE_THUMB_MICRO']['src'] ?>" alt="Планировка" class="b-building__plan">
                    <? endif ?>
                </td>
                <td>
                    <a href="<?= '/kvartiry/flat/' . $item['ID'] . '/' ?>">Квартира № <?= $item['NAME'] ?></a>
                </td>
                <td>
                    <div class="b-building__mob-view b-building__text-wrap">
                        <span class="b-building__text">Комнатность</span>
                    </div>
                    <?= $item['TYPE_NAME'] ?>
                </td>
                <td>
                    <div class="b-building__mob-view b-building__text-wrap">
                        <span class="b-building__text">Корпус</span>
                    </div>
                    <?= $item['BUILDING']['NAME'] ?>
                </td>
                <td>
                    <div class="b-building__mob-view b-building__text-wrap">
                        <span class="b-building__text">Этаж</span>
                    </div>
                    <?= $item['FLOOR']['NAME'] ?>
                </td>
                <td>
                    <div class="b-building__mob-view b-building__text-wrap">
                        <span class="b-building__text">Общая</span>
                    </div>
                    <?= $item['AREA_TOTAL'] ?> м<sup>2</sup>
                </td>
                <td>
                    <div class="b-building__mob-view b-building__text-wrap">
                        <span class="b-building__text">Жилая</span>
                    </div>
                    <?= $item['AREA_LIVING'] ?> м<sup>2</sup>
                </td>
                <td>
                    <div class="b-building__mob-view b-building__text-wrap">
                        <span class="b-building__text">Стоимость</span>
                    </div>
                    <?= $item['PRICE_TOTAL_F'] ?> р.
                </td>
                <td class="b-building__favorite">
                    <a href="javascript:;" title="Добавить в избранное" class="b-favorite j-favorite<?if ($item['inFavorite']):?> is-active<?endif;?>" data-flat-id="<?=$item['ID']?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="240" viewBox="0 0 51 48" class="b-favorite__svg">
                            <title>Добавить в избранное</title>
                            <path d="m25,1 6,17h18l-14,11 5,17-15-10-15,10 5-17-14-11h18z"/>
                        </svg>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>


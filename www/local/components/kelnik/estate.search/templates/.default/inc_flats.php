<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

    <table data-action="" class="b-building__table">
        <thead>
            <tr class="b-building__desktop-view">
                <td>План</td>
                <td><a href="javascript:;" data-sort="number" class="j-sort b-building__sort">Квартира</a></td>
                <td><a href="javascript:;" data-sort="rooms" class="j-sort b-building__sort">Комнат</a></td>
                <td><a href="javascript:;" data-sort="building" class="j-sort b-building__sort">Корпус</a></td>
                <td><a href="javascript:;" data-sort="floor" class="j-sort b-building__sort">Этаж</a></td>
                <td><a href="javascript:;" data-sort="area" class="j-sort b-building__sort">Общая</a></td>
                <td><a href="javascript:;" data-sort="living" class="j-sort b-building__sort">Жилая</a></td>
                <td>
                    <a
                        href="javascript:;"
                        data-sort="price"
                        data-direction="asc"
                        class="j-sort b-building__sort b-building__sort_style_asc">
                        При 100% оплате
                    </a>
                </td>
                <td></td>
            </tr>
            <tr class="b-building__mob-view">
                <td>
                    <select class="j-select-sort">
                        <option data-sort="price" data-direction="desc" select>По цене (по убыванию)</option>
                        <option data-sort="price" data-direction="asc">По цене (по возрастанию)</option>
                        <option data-sort="area" data-direction="desc">По площади (по убыванию)</option>
                        <option data-sort="area" data-direction="asc">По площади (по возрастанию)</option>
                    </select>
                </td>
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
        <?php else: ?>
            <div class="b-ntf b-ntf_theme_white">
                <h2 class="b-ntf__title">Квартиры не найдены</h2>
                <div class="b-ntf__cont">
                    <p class="b-ntf__txt">Измените параметры поиска.</p>
                </div>
            </div>
        <?php endif; ?>
        </tbody>
    </table>


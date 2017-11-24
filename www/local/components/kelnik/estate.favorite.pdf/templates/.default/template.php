<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Избранные квартиры</title>
</head>
<body>

    <table width="100%" cellpadding="15" style="font-size: 20px;">
        <tr>
            <td>
                <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/" target="_blank">
                    <img src="<?= $_SERVER['DOCUMENT_ROOT'] ?>/img/b-logo.png" alt="Новый Зеленорад"/>
                </a>
            </td>
            <td>
                <a href="http://<?= $_SERVER['HTTP_HOST'] ?>/" style="text-decoration: none; border-bottom: 2px solid #000; color: #000000;">newzelenograd.ru</a>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Адрес объекта:</strong> <br/>
                Московская область, посёлок Рузино
            </td>
            <td>
                <a href="tel:+7-499-795-77-77" style="text-decoration: none; color: #000000;">+7-499-795-77-77</a> <br/>
                <a href="maito:sale@newzelenograd.ru" style="text-decoration: none; color: #000000;">sale@newzelenograd.ru</a>
            </td>
        </tr>
    </table>
    <div style="width: 100%; margin: 70px 0;">
        <h1 align="left">Избранные квартиры</h1>
    </div>

    <table width="100%" cellpadding="2">
        <thead style="font-size: 12px;">
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>Комнаты</td>
                <td>Этаж</td>
                <td>S общ, м<sup>2</sup></td>
                <td>S жилая</td>
                <td>Кухня</td>
                <td>Стоимость, руб.</td>
            </tr>
        </thead>
        <tbody style="font-size: 16px;">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <tr>
                    <td>
                        <?php if ($item['IMAGE_THUMB']): ?>
                            <img src="<?= $_SERVER['DOCUMENT_ROOT'] ?><?= $item['IMAGE_THUMB']['src'] ?>" alt="<?= $item['ROOMS_NAME'] ?>"/>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><a href="<?= $arResult['HOME_PATH'] ?>flat/<?= $item['ID'] ?>/" target="_blank" style="color: #386f2a; text-decoration: none;">Квартира №<?= $item['NAME'] ?></a></strong><br/>
                        <?= $item['DESCR'] ?>
                    </td>
                    <td><?= $item['ROOMS'] ?></td>
                    <td><?= $item['FLOOR']['NAME'] ?>/<?= $item['SECTION']['MAX_FLOOR'] ?></td>
                    <td><?= $item['AREA_TOTAL'] ?></td>
                    <td><?= $item['AREA_LIVING'] ?></td>
                    <td><?= $item['AREA_KITCHEN'] ?></td>
                    <td><?= $item['PRICE_TOTAL_F'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>

</body>
</html>

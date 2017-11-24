<!DOCTYPE html>
<html lang="ru" style="background-color:#ffffff;">
<head>
    <meta charset="UTF-8">
    <title>Карточка квартиры</title>
</head>

<body style="background-color:#ffffff;">
    <div style="border-bottom: 1px solid #ccc; padding: 18px 0;">
        <table width="100%">
            <tr>
                <td>
                    <img src="<?= $_SERVER['DOCUMENT_ROOT'] ?>/img/pdf/logo.jpg" alt="Parktow">
                </td>
                <td>
                    <b style="text-transform: uppercase; color: #ccc; font-size: 12px">ЭТА КВАРТИРА НА САЙТЕ</b>
                    <br>
                    <a
                        href="http://<?= $_SERVER['HTTP_HOST'] ?>/kvartiry/flat/<?= $arResult['FLAT']['ID'] ?>/"
                        style="color: #000000; text-decoration: none; font-size: 11px;">
                        <?= $_SERVER['HTTP_HOST'] ?> /kvartiry/flat/<?= $arResult['FLAT']['ID'] ?>/
                    </a>
                </td>
                <td style="text-align: right">
                    <a href="tel: +7-4842-27-77-72" style="color: #000; text-decoration: none; font-size: 18px">
                        <b>
                            <span style="color: #ccc">+7 (4842)</span>
                            <span>27-77-72</span>
                        </b>
                        <br>
                        <a href="http://parktown.com" target="_blank" style="font-size: 18px; color: #000000; text-decoration: none;">parktown.com</a></a>
                </td>
            </tr>
        </table>
    </div>

    <table width="100%">
        <tr>
            <td>
                <h1 style="font-size: 18px;">
                    <?= $arResult['FLAT']['TYPE_NAME'] ?><br/>
                    квартира №<?= $arResult['FLAT']['NAME'] ?>
                </h1>
            </td>
            <td><b style="color: #ccc; text-transform: uppercase; font-size: 12px">общая площадь, м<sup>2<sup></b><br><span style="font-size: 16px"><?= $arResult['FLAT']['AREA_TOTAL_F'] ?></span></td>
            <td><b style="color: #ccc; text-transform: uppercase; font-size: 12px">Жилая, м<sup>2<sup></b><br><span style="font-size: 16px"><?= $arResult['FLAT']['AREA_LIVING_F'] ?></span></td>
            <td><b style="color: #ccc; text-transform: uppercase; font-size: 12px">Кухня, м<sup>2<sup></b><br><span style="font-size: 16px"><?= $arResult['FLAT']['AREA_KITCHEN_F'] ?></span></td>
            <td><b style="color: #ccc; text-transform: uppercase; font-size: 12px">Стоиость при 100% оплате</b><br><span style="font-size: 16px"><?= $arResult['FLAT']['PRICE_TOTAL_F'] ?> рублей</span></td>
        </tr>
        <tr>
            <td valign="top" style="font-size: 12px; line-height: 15px;">
                <b style="font-size: 15px; line-height: 20px;">Центральный офис продаж</b>
                <br>
                <span>Калуга, ул. Кирова 19, ТРЦ «РИО» 4 этаж</span>
                <br>
                <span>Каждый день с 10:00 до 22:00</span>
                <br>
                <br>

                <b style="font-size: 15px; line-height: 20px;">Офис продаж на объекте</b>
                <br>
                <span>Калужская область, Ферзиковский район,<br> д. Ястебовка</span>
                <br>
                <span>Каждый день с 10:00 до 19:00</span>
            </td>
            <td colspan="2"><img src="<?= $arResult['PLANOPLAN']['tabs']['2d']['content'] ?>" alt="Планировка" width="150"></td>
            <td colspan="2"><img src="<?= $arResult['PLANOPLAN']['tabs']['3d']['content'] ?>" alt="Планировка 3D" width="200"></td>
        </tr>
        <tr>
            <td>
                <img src="<?= $arResult['PLANOPLAN']['tabs']['qrcode']['content'] ?>" alt="Короткая ссылка" style="float: left" width="150" height="150">
                <div style="width: 200px; float: left; padding-top: 3px; font-size: 14px;">
                    ОТСКАНИРУЙТЕ <br>
                    ЭТОТ QR-CODE<br>
                    с помощью мобильного приложения «PLANOPLAN GO!» и совершите виртуальную экскурсию по квартире. Приложение доступно в Google Play и App Store
                </div>
            </td>
            <td colspan="2" align="center">
                <img src="<?= $_SERVER['DOCUMENT_ROOT'] ?><?= $arResult['FLAT']['BUILDING']['IMAGE_IN_OBJECT'] ?>" alt="Планировка квартала" width="150">
                    <br>Корпус <?= $arResult['FLAT']['BUILDING']['NAME'] ?>

            </td>
            <td colspan="2" align="center">

                        <img src="<?= $_SERVER['DOCUMENT_ROOT'] ?><?= $arResult['FLAT']['IMAGE_ON_FLOOR']['SRC'] ?>" alt="Планировка этажа" width="200">

                    <br>Этаж <?= $arResult['FLAT']['FLOOR']['NAME'] ?>/<?= $arResult['FLAT']['BUILDING']['MAX_FLOOR'] ?>

            </td>
        </tr>
    </table>
</body>
</html>

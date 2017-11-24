<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <nav class="b-nav b-nav_theme_footer">
        <ul class="b-nav__list">

            <?
            foreach ($arResult as $arItem):
                if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                    continue;
                ?>
                <? if ($arItem["SELECTED"]):?>
                <li class="b-nav__item"><a href="<?= $arItem["LINK"] ?>" class="b-nav__link selected"><?= $arItem["TEXT"] ?></a>
                </li>
            <? else:?>
                <li class="b-nav__item"><a href="<?= $arItem["LINK"] ?>" class="b-nav__link"><?= $arItem["TEXT"] ?></a></li>
            <? endif ?>

            <? endforeach ?>

        </ul>
    </nav>
<? endif ?>
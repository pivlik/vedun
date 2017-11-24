<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="l-members">
        <div class="l-members__wrap">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <?
                $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="l-members__item" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                    <div class="b-member">
                        <div class="b-member__img">
                            <div class="b-member__img-wrap"><img src="<?= $item['PREVIEW_PICTURE']['SRC']; ?>"></div>
                        </div>
                        <div class="b-member__cnt">
                            <h4 class="b-member__ttl"><?= $item['NAME']; ?></h4>
                            <p>
                                <? if ($item['PROPERTIES']['POSITION']['VALUE']): ?>
                                    <?= $item['PROPERTIES']['POSITION']['VALUE']; ?><br>
                                <? endif; ?>
                                <? if ($item['PROPERTIES']['PHONE']['VALUE']): ?>
                                <a href="tel:<?= $item['PROPERTIES']['PHONE']['VALUE']; ?>"><?= $item['PROPERTIES']['PHONE']['VALUE']; ?></a>
                                <? endif; ?>
                                <? if ($item['PROPERTIES']['DOB']['VALUE']): ?>
                                <br>доб. <?= $item['PROPERTIES']['DOB']['VALUE']; ?>
                                <? endif; ?>
                                <? if ($item['PROPERTIES']['EMAIL']['VALUE']): ?>
                                    <br><a href="mailto:<?= $item['PROPERTIES']['EMAIL']['VALUE']; ?>"><?= $item['PROPERTIES']['EMAIL']['VALUE']; ?></a>
                                <? endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>

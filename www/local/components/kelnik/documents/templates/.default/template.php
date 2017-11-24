<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <div class="l-documents">
        <div class="b-documents">
            <h2 class="b-documents__ttl">Документы</h2>
            <div class="b-documents__list">
                <? foreach ($arResult['ITEMS'] as $item): ?>
                    <?
                    $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>
                    <? if (is_array($item['DOC'])): ?>
                        <a href="<?= $item['DOC']['SRC']; ?>"
                           class="b-documents__item b-documents__item_format_<?= $item['DOC_EXT']; ?>"
                           id="<?= $this->GetEditAreaId($item['ID']); ?>">
                            <div class="b-documents__text"><?= $item['NAME']; ?></div>
                            <div class="b-documents__format"><?= mb_strtoupper($item['DOC_EXT']); ?>
                                , <?= $item['DOC_SIZE_F']; ?></div>
                        </a>
                    <? endif; ?>
                <? endforeach; ?>
            </div>
        </div>
    </div>
<? endif; ?>

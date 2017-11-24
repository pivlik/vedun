<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="l-documents">
        <div class="l-documents__ttl">
            <h2>Документы</h2></div>
        <div class="l-documents__cnt">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <?
                $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <? if (is_array($item['PROPERTIES']['DOC']['VALUE'])): ?>
                    <? $extName = ToLower(pathinfo($item['PROPERTIES']['DOC']['VALUE']['FILE_NAME'], PATHINFO_EXTENSION)); ?>
                    <div class="l-documents__item" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                        <a href="<?= $item['PROPERTIES']['DOC']['VALUE']['SRC']; ?>" download class="b-documents"
                           target="_blank">
                            <div class="b-documents__ttl"><?= $item['NAME']; ?></div>
                            <div class="b-documents__format"><?= $extName; ?></div>
                        </a>
                    </div>
                <? endif; ?>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>

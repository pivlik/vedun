<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="l-contacts">
        <div class="l-contacts__wrap">
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <?
                $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="l-contacts__item" id="<?= $this->GetEditAreaId($item['ID']); ?>">
                    <div class="b-contacts">
                        <h4 class="b-contacts__ttl"><?= $item['NAME']; ?></h4>
                        <p>
                            <? if ($item['PROPERTIES']['ADDRESS']['VALUE']): ?>
                                <?= $item['PROPERTIES']['ADDRESS']['VALUE']; ?><br>
                            <? endif; ?>
                            <? if ($item['PROPERTIES']['PHONE']['VALUE']): ?>
                                <a href="tel:<?= $item['PROPERTIES']['PHONE']['VALUE']; ?>"><?= $item['PROPERTIES']['PHONE']['VALUE']; ?></a>
                            <? endif; ?>
                            <? if ($item['PROPERTIES']['WORK_TIME']['VALUE']): ?>
                                <br><?= $item['PROPERTIES']['WORK_TIME']['VALUE']; ?>
                            <? endif; ?>
                        </p>
                        <div class="b-contacts__map">
                            <div id="map<?= $item['ID']; ?>" style="width: 100%; height: 500px" data-zoom="14"
                                 data-scrollwheel="false"
                                 class="j-map b-map"></div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
            <script>
                window.map = {}
                <? foreach ($arResult['ITEMS'] as $item): ?>
                <? if ($item['PROPERTIES']['GMAP']['VALUE']): ?>
                window.map['map<?= $item['ID']; ?>'] = {
                    center: [<?= $item['PROPERTIES']['GMAP']['VALUE']; ?>],
                    markers: [
                        {
                            coords: [<?= $item['PROPERTIES']['GMAP']['VALUE']; ?>],
                            image: '<?= $item['PREVIEW_PICTURE']['SRC']; ?>'
                        }]
                }
                <? endif; ?>
                <? endforeach; ?>
            </script>
        </div>
    </div>
<? endif; ?>

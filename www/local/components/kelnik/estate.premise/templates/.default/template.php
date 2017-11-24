<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="l-article">
    <div class="l-article-hdr l-article-hdr-tabs">
        <div class="l-article-hdr__tabs">
            <dl id="js-commercial-premises-tabs" class="tabs" data-tab data-filter-name="status">
                <dd class="active">
                    <a href="" data-value="">Все</a>
                </dd>
                <?php foreach ($arResult['STATUSES'] as $ID => $status): ?>
                    <dd>
                        <a href="" data-value="<?= $ID ?>"><?= $status['TAB_NAME'] ?></a>
                    </dd>
                <?php endforeach; ?>
            </dl>
        </div>
        <div class="l-article-hdr__view-plan end">
            <a class="b-view-area-plan js-view-area-plan"
               href="#js-callback-genplan"
               id="js-view-area-plan"
               data-img="/img/b-view-area-plan.jpg">
                Посмотреть план района
            </a>
        </div>
    </div>

    <div class="l-article-col">
        <ul class="l-commercial-premises-cols">
            <?php include 'inc_items.php'; ?>
        </ul>
    </div>
    <?php if ($arResult['HAS_NEXT_PAGE']): ?>
        <div class="l-btn-wide">
            <a id="js-more-premises" data-url="" class="btn-gray button" href="javascript:;">Показать еще 10 помещений</a>
        </div>
    <?php endif; ?>
</div>
<div id="js-callback-genplan" class="b-popup mfp-hide b-popup_head_small">
    <div class="b-popup__hdr">
        <h1 class="b-popup__title">План района</h1>
    </div>
    <div class="b-popup__cnt">
        <img src="/img/b-view-area-plan.jpg" alt="План района"/>
    </div>
</div>
<div id="panel"><?$APPLICATION->ShowPanel();?></div>
<div class="l-wrap">
    <div class="l-header">
        <header class="l-header__wrap">
            <div class="l-header__logo"><a href="/" title="ParkTown" class="b-logo">ParkTown</a> </div>
            <div class="l-header__nav">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "top",
                    Array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "DELAY" => "Y",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(""),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "top",
                        "USE_EXT" => "N"
                    )
                );?>
            </div>
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "COMPONENT_TEMPLATE" => ".default",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/top-phone.inc"
                )
            ); ?>
            <div class="l-header__menu-show j-nav">
                <a href="javascript:;" class="b-icons b-icons_image_nav-show"></a>
            </div>
        </header>
    </div>
    <main class="l-main">

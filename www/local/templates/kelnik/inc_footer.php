</main>
<footer class="l-footer">
    <div class="l-footer-info">
        <div class="l-footer-info__item">
            <div class="b-footer-info">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPONENT_TEMPLATE" => ".default",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/include/footer.inc"
                    )
                ); ?>
            </div>
        </div>
        <div class="l-footer-info__item">
            <div class="b-footer-info">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPONENT_TEMPLATE" => ".default",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => "/include/footer-office.inc"
                    )
                ); ?>
            </div>
        </div>
    </div>
    <div class="l-footer-nav">
        <div class="l-footer-nav__wrap">
            <div class="l-footer-nav__logo">
                <a href="/" title="ParkTown" class="b-icons b-icons_image_mini-logo"></a>
            </div>
            <div class="l-footer-nav__nav">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "footer",
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
            <div class="l-footer-nav__kelnik">
                <a href="http://kelnik.ru" target="_blank" title="Кельник" class="b-kelnik">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         x="0px" y="0px" width="403.5px" height="69.3px" viewbox="0 0 403.5 69.3"
                         style="enable-background:new 0 0 403.5 69.3;" xml:space="preserve"
                         class="b-kelnik__img">
                  <g>
                      <path
                          d="M10.4,67.2c0,0.4-0.3,0.6-0.6,0.6H0.6c-0.4,0-0.6-0.3-0.6-0.6V1.6C0,1.3,0.3,1,0.6,1h9.1c0.4,0,0.6,0.3,0.6,0.6V67.2z"></path>
                      <path
                          d="M117.9,67.2c0,0.4-0.3,0.6-0.7,0.6h-8.5c-0.4,0-0.6-0.3-0.6-0.6V1.6c0-0.3,0.3-0.6,0.6-0.6h8.5c0.4,0,0.7,0.3,0.7,0.6V67.2            z"></path>
                      <path
                          d="M210.2,67.2c0,0.4-0.3,0.6-0.6,0.6h-8.5c-0.4,0-0.6-0.3-0.6-0.6V1.7c0-0.4,0.3-0.7,0.6-0.7h8.5c0.4,0,0.6,0.3,0.6,0.7V67.2            z"></path>
                      <path
                          d="M190.8,67.2c0,0.4-0.3,0.6-0.6,0.6h-8.5c-0.4,0-0.6-0.3-0.6-0.6V19.5c0-0.4,0.3-0.6,0.6-0.6h8.5c0.4,0,0.6,0.3,0.6,0.6            V67.2z"></path>
                      <path
                          d="M190.8,10.3c0,0.4-0.3,0.6-0.6,0.6h-8.5c-0.4,0-0.6-0.3-0.6-0.6V1.7c0-0.4,0.3-0.7,0.6-0.7h8.5c0.4,0,0.6,0.3,0.6,0.7V10.3            z"></path>
                      <path
                          d="M24.9,34.4c3.9-4.5,28-31.8,28.6-32.5C53.8,1.6,53.5,1,53.1,1H42c-0.3,0-0.6,0.2-0.9,0.6L16.3,30.1v8.6l24.9,28.5            c0.3,0.3,0.6,0.6,0.9,0.6h11.9c0.3,0,0.7-0.6,0.4-0.9C53.7,66.3,28.5,38.4,24.9,34.4z"></path>
                      <path
                          d="M223.1,42.7c5-5.9,18.6-22.3,19.2-23c0.3-0.3-0.1-0.9-0.4-0.9h-9.4c-0.3,0-0.6,0.2-0.9,0.6l-15.5,19V47l16.3,20.2            c0.3,0.4,0.6,0.6,0.9,0.6h10.2c0.4,0,0.7-0.6,0.4-0.9C243.3,66.3,235.6,57.8,223.1,42.7z"></path>
                      <path
                          d="M149.7,17.6c-5.9,0-22.2,0-22.2,22.2v27.3c0,0.4,0.3,0.6,0.6,0.6h8.5c0.4,0,0.6-0.3,0.6-0.6V38.8            c0-12.8,8.8-12.7,12.2-12.7c3.3,0,12.2,0.1,12.2,12.7v28.4c0,0.4,0.3,0.6,0.6,0.6h8.5c0.4,0,0.7-0.3,0.7-0.6V39.8            C171.4,17.6,155.8,17.6,149.7,17.6z"></path>
                      <path
                          d="M93.6,24.2c-4-4.5-9.3-6.6-16.2-6.6c-7.1,0-12.5,2.1-16.5,6.7c-4.1,4.6-6.1,10.9-6.1,19c0,8.2,2.1,14.6,6.1,19.2            c4,4.7,9.3,6.6,16.3,6.6c5.6,0,10.2-1.2,14.2-4.3c3.9-3,6.3-7,7.3-11.9l0,0c0,0,0,0,0-0.1c0.1-0.4-0.4-0.4-0.4-0.4h-9            c-0.6,2.5-1.9,4.8-3.8,6.2c-1.9,1.4-4.9,2.3-7.7,2.3c-3.9,0-7.4-1.4-9.6-3.8c-2.2-2.4-3.8-6.3-3.7-10.9h33.8c0.7,0,1-0.4,1-1            c0.1-1.5,0.1-2,0.1-2.6C99.5,34.8,97.5,28.7,93.6,24.2z M64.6,38.8c0.3-4.1,1.8-7.5,3.8-9.7c2-2.1,5.3-3.4,8.8-3.4            c3.7,0,7.3,1.3,9.3,3.4c2,2.2,3.3,5.6,3.3,9.6H64.6z"></path>
                  </g>
                        <g>
                            <path
                                d="M355.2,56.3c0-1.2-0.8-2.7-1.8-3.3l-20-14.2c-1-0.7-1.8-0.2-1.8,0.9v25.8c0,1.2,1,2.1,2.1,2.1H353c1.2,0,2.1-1,2.1-2.1            L355.2,56.3L355.2,56.3z"></path>
                            <path
                                d="M285,2.1c0-1.2,1-2.1,2.2-2.1h17.6c1.2,0,2.1,1,2.1,2.1v63.4c0,1.2-1,2.1-2.1,2.1h-17.6c-1.2,0-2.2-1-2.2-2.1V2.1z"></path>
                            <path
                                d="M355.2,11.4c0,1.2-0.8,2.7-1.8,3.3l-20,14.2c-1,0.7-1.8,0.2-1.8-0.9V2.1c0-1.2,1-2.1,2.1-2.1H353c1.2,0,2.1,1,2.1,2.1            L355.2,11.4L355.2,11.4z"></path>
                            <path
                                d="M403.4,56.3c0-1.2-0.8-2.7-1.8-3.3l-20-14.2c-0.9-0.7-1.8-0.2-1.8,0.9v25.8c0,1.2,1,2.1,2.1,2.1h19.2c1.2,0,2.1-1,2.1-2.1            L403.4,56.3L403.4,56.3z"></path>
                            <path
                                d="M403.4,11.4c0,1.2-0.8,2.7-1.8,3.3l-20,14.2c-0.9,0.7-1.8,0.2-1.8-0.9V2.1c0-1.2,1-2.1,2.1-2.1h19.2c1.2,0,2.1,1,2.1,2.1            L403.4,11.4L403.4,11.4z"></path>
                        </g>
                </svg>
                </a>
            </div>
        </div>
    </div>
</footer>
</div>
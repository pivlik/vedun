CREATE TABLE IF NOT EXISTS `b_estate_entity` (
    `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `NAME` varchar(100) COLLATE utf8_general_ci NOT NULL,
    `CLASS_NAME` varchar(64) COLLATE utf8_general_ci NOT NULL,
    `TABLE_NAME` varchar(64) COLLATE utf8_general_ci NOT NULL,
    `GROUP_NAME` varchar(100) COLLATE utf8_general_ci NOT NULL,
    `SORT` int(11) NOT NULL,
    PRIMARY KEY (`ID`),
    KEY `SORT` (`SORT`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `b_estate_entity` VALUES (1, 'Объекты', 'EstateObjectTable', 'estate_object', 'Объекты', 300),
(2, 'Корпуса', 'EstateBuildingTable', 'estate_building', 'Объекты', 310),
(3, 'Секции', 'EstateSectionTable', 'estate_section', 'Объекты', 320),
(4, 'Этажи', 'EstateFloorTable', 'estate_floor', 'Объекты', 330),
(5, 'Квартиры', 'EstateFlatTable', 'estate_flat', 'Объекты', 340),
(6, 'Города', 'EstaterefCitiesTable', 'estateref_cities', 'Города', 400),
(7, 'Районы', 'EstateRefDistrictsTable', 'estateref_districts', 'Города', 410),
(8, 'Метро', 'EstateRefSubwayTable', 'estateref_subway', 'Города', 420),
(9, 'Очереди строительства', 'EstateRefStagesTable', 'estateref_stages', '', 450),
(10, 'Статусы квартир', 'EstateRefFlatStatusesTable', 'estateref_flatstatuses', '', 460),
(11, 'Типы квартир', 'EstateRefFlatTypesTable', 'estateref_flattypes', '', 350),
(12, 'Особенности квартир', 'EstateRefFeaturesTable', 'estateref_features', '', 470),
(13, 'Нежилые помещения', 'EstatePremiseTable', 'estate_premise', 'Объекты', 350),
(14, 'Типы нежилых помещений', 'EstateRefPremiseTypesTable', 'estateref_premisetypes', '', 480),
(15, 'Статусы нежилых помещений', 'EstateRefPremiseStatusesTable', 'estateref_premisestatuses', '', 490),
(16, 'Объекты - Панорамы', 'EstateObjectPanoTable', 'estate_object_pano', 'Объекты', 360),
(17, 'Параметры квартир', 'EstateRefOptionsTable', 'estateref_options', '', 471);

CREATE TABLE `estate_building` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `ACTIVE` char(1) COLLATE utf8_general_ci NOT NULL,
  `PARENT` int(11) NOT NULL,
  `STAGE` int(11) NOT NULL,
  `MAX_FLOOR` int(11) NOT NULL,
  `IMAGE_IN_OBJECT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `DELIVERY` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `DELIVERED` char(1) COLLATE utf8_general_ci NOT NULL,
  `VISUAL_LINK` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `VISUAL_SKIP` char(1) COLLATE utf8_general_ci NOT NULL,
  `LOCATION_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `COMPASS_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD_ALT` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_NAME` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_POSITION` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_POSITION_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `FLY_VIDEO_MP4` int(11) NOT NULL,
  `FLY_VIDEO_WEBM` int(11) NOT NULL,
  `FLY_VIDEO_OGG` int(11) NOT NULL,
  `FLY_VIDEO_MP4_ALT` int(11) NOT NULL,
  `FLY_VIDEO_WEBM_ALT` int(11) NOT NULL,
  `FLY_VIDEO_OGG_ALT` int(11) NOT NULL,
  `POS_VIDEO_MP4` int(11) NOT NULL,
  `POS_VIDEO_WEBM` int(11) NOT NULL,
  `POS_VIDEO_OGG` int(11) NOT NULL,
  `POS_VIDEO_MP4_ALT` int(11) NOT NULL,
  `POS_VIDEO_WEBM_ALT` int(11) NOT NULL,
  `POS_VIDEO_OGG_ALT` int(11) NOT NULL,
  `BUILDING` int(11) NOT NULL,
  `LINK_SECTIONS` text COLLATE utf8_general_ci NOT NULL,
  `IMPORT_ID` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`),
  KEY `ACTIVE` (`ACTIVE`),
  KEY `STAGE` (`STAGE`),
  KEY `IMPORT_ID` (`IMPORT_ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_favorite` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FLAT` int(11) NOT NULL,
  `UID` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FLAT` (`FLAT`),
  KEY `UID` (`UID`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `estate_flat` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `ACTIVE` char(1) COLLATE utf8_general_ci NOT NULL,
  `PARENT` int(11) NOT NULL,
  `SECTION` int(11) NOT NULL,
  `TYPE` int(11) NOT NULL,
  `ROOMS` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL,
  `PRICE_TOTAL` int(11) NOT NULL,
  `PRICE_METER` int(11) NOT NULL,
  `AREA_TOTAL` float NOT NULL,
  `AREA_LIVING` float NOT NULL,
  `AREA_KITCHEN` float NOT NULL,
  `IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `IMAGE_3D` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `VISUAL_LINK` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD` text COLLATE utf8_general_ci NOT NULL,
  `IMAGE_ON_FLOOR` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `STATUS` int(11) NOT NULL,
  `ACTIONS` text COLLATE utf8_general_ci NOT NULL,
  `PLANOPLAN` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `IMPORT_ID` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IMPORT_ID` (`IMPORT_ID`),
  KEY `AREA_TOTAL` (`AREA_TOTAL`),
  KEY `ROOMS` (`ROOMS`),
  KEY `PRICE` (`PRICE_TOTAL`),
  KEY `TYPE` (`TYPE`,`STATUS`,`PARENT`,`AREA_TOTAL`,`PRICE_TOTAL`),
  KEY `PARENT` (`PARENT`,`STATUS`),
  KEY `STATUS` (`STATUS`,`TYPE`,`PARENT`,`AREA_TOTAL`,`PRICE_TOTAL`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_flat_features` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FLAT` int(11) NOT NULL,
  `FEATURE` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FLAT_FEATURE` (`FLAT`,`FEATURE`),
  KEY `FEATURE` (`FEATURE`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `estate_flat_options` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FLAT` int(11) NOT NULL,
  `OPTION` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FLAT_OPTION` (`FLAT`,`OPTION`),
  KEY `OPTION` (`OPTION`)
) DEFAULT CHARSET=utf8;

CREATE TABLE `estate_floor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `ACTIVE` char(1) COLLATE utf8_general_ci NOT NULL,
  `PARENT` int(11) NOT NULL,
  `PARENT_SECTION` int(11) NOT NULL,
  `VISUAL_LINK` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD_ALT` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `COMPASS_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `IMPORT_ID` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`),
  KEY `IMPORT_ID` (`IMPORT_ID`),
  KEY `ACTIVE` (`ACTIVE`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_genplan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAV_IMAGE` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE_ALT` varchar(255) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_object` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `DELIVERY` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `ACTIVE` char(1) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD_ALT` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `COMPASS_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_NAME` varchar(155) COLLATE utf8_general_ci NOT NULL,
  `POINTER_POSITION` text COLLATE utf8_general_ci NOT NULL,
  `POINTER_POSITION_ALT` text COLLATE utf8_general_ci NOT NULL,
  `POS_VIDEO_MP4` int(11) NOT NULL,
  `POS_VIDEO_WEBM` int(11) NOT NULL,
  `POS_VIDEO_OGG` int(11) NOT NULL,
  `POS_VIDEO_MP4_ALT` int(11) NOT NULL,
  `POS_VIDEO_WEBM_ALT` int(11) NOT NULL,
  `POS_VIDEO_OGG_ALT` int(11) NOT NULL,
  `OBJECT` int(11) NOT NULL,
  `IMPORT_ID` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ACTIVE` (`ACTIVE`),
  KEY `IMPORT_ID` (`IMPORT_ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_object_pano` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ACTIVE` char(1) COLLATE utf8_general_ci NOT NULL,
  `FILE` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `PARENT` int(11) NOT NULL,
  `POSITION` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POSITION_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`),
  KEY `ACTIVE` (`ACTIVE`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_premise` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ACTIVE` char(1) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `TYPE` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL,
  `PRICE_LEASE` int(11) NOT NULL,
  `AREA_TOTAL` float NOT NULL,
  `IMAGE` varchar(100) NOT NULL,
  `TEXT` text NOT NULL,
  `STATUS` int(11) NOT NULL,
  `IMPORT_ID` varchar(100) NOT NULL,
  `PARENT` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ACTIVE` (`ACTIVE`),
  KEY `TYPE` (`TYPE`),
  KEY `STATUS` (`STATUS`),
  KEY `IMPORT_ID` (`IMPORT_ID`),
  KEY `PARENT` (`PARENT`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_section` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `ACTIVE` char(1) COLLATE utf8_general_ci NOT NULL,
  `MAX_FLOOR` int(11) NOT NULL,
  `PARENT` int(11) NOT NULL,
  `VISUAL_LINK` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `VISUAL_SKIP` char(1) COLLATE utf8_general_ci NOT NULL,
  `LOCATION_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `NAV_COORD_ALT` text COLLATE utf8_general_ci NOT NULL,
  `NAV_IMAGE_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `COMPASS_IMAGE` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_NAME` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_POSITION` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `POINTER_POSITION_ALT` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `FLY_VIDEO_MP4` int(11) NOT NULL,
  `FLY_VIDEO_WEBM` int(11) NOT NULL,
  `FLY_VIDEO_OGG` int(11) NOT NULL,
  `FLY_VIDEO_MP4_ALT` int(11) NOT NULL,
  `FLY_VIDEO_WEBM_ALT` int(11) NOT NULL,
  `FLY_VIDEO_OGG_ALT` int(11) NOT NULL,
  `POS_VIDEO_MP4` int(11) NOT NULL,
  `POS_VIDEO_WEBM` int(11) NOT NULL,
  `POS_VIDEO_OGG` int(11) NOT NULL,
  `POS_VIDEO_MP4_ALT` int(11) NOT NULL,
  `POS_VIDEO_WEBM_ALT` int(11) NOT NULL,
  `POS_VIDEO_OGG_ALT` int(11) NOT NULL,
  `LINK_SECTIONS` text COLLATE utf8_general_ci NOT NULL,
  `IMPORT_ID` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`),
  KEY `ACTIVE` (`ACTIVE`),
  KEY `IMPORT_ID` (`IMPORT_ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estateref_cities` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estateref_districts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `PARENT` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estateref_features` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `ICON` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `estateref_features` (`ID`, `NAME`) VALUES
(1, 'с паркингом'),
(2, 'с балконом'),
(3, 'не первый этаж'),
(4, 'не последний этаж');

CREATE TABLE `estateref_options` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `ICON` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `estateref_options` (`ID`, `NAME`) VALUES
  (1, 'c отделкой');

CREATE TABLE `estateref_flatstatuses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `estateref_flatstatuses` (`ID`, `NAME`) VALUES
(1, 'В продаже'),
(2, 'Забронировано'),
(3, 'Продано');

CREATE TABLE `estateref_flattypes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `ROOMS` tinyint(1) NOT NULL,
  `IMPORT_ID` varchar(100) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `estateref_flattypes` (`ID`, `NAME`, `ROOMS`) VALUES
(1, 'Студия', 0),
(2, 'Однокомнатная', 1),
(3, 'Двухкомнатная', 2),
(4, 'Трёхкомнатная', 3);

CREATE TABLE `estateref_premisestatuses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `TAB_NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `estateref_premisestatuses` (`ID`, `NAME`, `TAB_NAME`) VALUES
(1, 'готово', 'Готовые'),
(2, 'строится', 'Строящиеся');

CREATE TABLE `estateref_premisetypes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `estateref_premisetypes` (`ID`, `NAME`) VALUES
(1, 'Коммерческое'),
(2, 'Кладовка');

CREATE TABLE `estateref_stages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NUMBER` int(11) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `NAME_DATIVE` varchar(100) NOT NULL,
  `DELIVERY` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estateref_subway` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(100) NOT NULL,
  `PARENT` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `estate_object_subways` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OBJECT` int(11) NOT NULL,
  `SUBWAY` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `OBJECT_SUBWAY` (`OBJECT`,`SUBWAY`),
  KEY `SUBWAY` (`SUBWAY`)
) DEFAULT CHARSET=utf8;

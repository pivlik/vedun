<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"VARIABLE_ALIASES" => array(
			"section_id" => array("NAME" => "ID раздела"),
			"element_id" => array("NAME" => "ID элемента"),
		),
		"SEF_MODE" => array(
			"selector" => array(
				"NAME" => "Генплан",
				"DEFAULT" => "index.php",
				"VARIABLES" => array(),
			),
			"building" => array(
				"NAME" => "Корпус",
				"DEFAULT" => "building/#BUILDING_ID#/",
				"VARIABLES" => array("BUILDING_ID"=>"BUILDING_ID"),
			),
			"section" => array(
				"NAME" => "Секция",
				"DEFAULT" => "building/#BUILDING_ID#/section/#SECTION_ID#/",
				"VARIABLES" => array("SECTION_ID"=>"SECTION_ID"),
			),
			"floor" => array(
				"NAME" => "Этаж",
				"DEFAULT" => "building/#BUILDING_ID#/section/#SECTION_ID#/floor/#FLOOR_ID#",
				"VARIABLES" => array("FLOOR_ID"=>"FLOOR_ID"),
			),
			"flat" => array(
				"NAME" => "Карточка квартиры",
				"DEFAULT" => "flat/#FLAT_ID#/",
				"VARIABLES" => array(),
			),
			"search" => array(
				"NAME" => "Поиск",
				"DEFAULT" => "search",
				"VARIABLES" => array(),
			),
			"favorite" => array(
				"NAME" => "Избранное",
				"DEFAULT" => "favorite",
				"VARIABLES" => array(),
			),
		),
	),
);

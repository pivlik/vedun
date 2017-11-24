<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("KELNIK_SLIDER_NAME"),
	"DESCRIPTION" => GetMessage("KELNIK_SLIDER_DESC"),
	"PATH" => array(
		"ID" => "Kelnik",
		"CHILD" => array(
			"ID" => "components",
			"NAME" => GetMessage("KELNIK_COMPONENTS")
		)
	),
);

?>
<?
IncludeModuleLangFile(__FILE__);

class FieldTypeRefSelectMulti
{
    function OnBeforeSave($arField, $newValues, $curId) {
        return serialize((array) $newValues);
    }
}

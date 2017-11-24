<?
IncludeModuleLangFile(__FILE__);

class FieldTypeFile
{
    function OnBeforeSave($arField, $value) {
        if(is_array($value)) {
            //Protect from user manipulation
            if(isset($value["old_id"]) && $value["old_id"] > 0) {
                if(is_array($arField["VALUE"])) {
                    if(!in_array($value["old_id"], $arField["VALUE"]))
                        unset($value["old_id"]);
                } else {
                    if($arField["VALUE"] != $value["old_id"])
                        unset($value["old_id"]);
                }
            }

            if($value["del"] && $value["old_id"]) {
                CFile::Delete($value["old_id"]);
                $value["old_id"] = false;
            }

            if($value["error"]) {
                return $value["old_id"];
            } else {
                if($value["old_id"]) {
                    CFile::Delete($value["old_id"]);
                }
                $value["MODULE_ID"] = "main";
                $id =  CFile::SaveFile($value, "estate");
                return $id;
            }
        } else {
            return $value;
        }
    }
}

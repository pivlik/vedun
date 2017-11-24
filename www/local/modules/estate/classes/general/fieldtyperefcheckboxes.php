<?
IncludeModuleLangFile(__FILE__);

class FieldTypeRefCheckboxes
{
    function OnBeforeSave($arField, $newValues, $curId)
    {
        if (!is_array($newValues)) {
            return false;
        }
        if ($curId) {
            // Получаем выбранные значения
            $res = call_user_func_array(
                array($arField['linking_class'], 'getList'),
                array(
                    array(
                        'select' => array('ID', 'KEY' => $arField['linking_field2']),
                        'filter' => array($arField['linking_field1'] => $curId)
                    )
                )
            );
            $values = array();
            while ($data = $res->fetch()) {
                $values[$data['ID']] = $data['KEY'];
            }

            $del = array();
            foreach ($values as $id => $key) {
                if (isset($newValues[$key]) && $newValues[$key] === 'Y') {
                    unset($newValues[$key]);
                    continue;
                }
                $del[] = $id;
            }

            if (!empty($del)) {
                foreach ($del as $id) {
                    call_user_func_array(
                        array($arField['linking_class'], 'delete'),
                        array($id)
                    );
                }
            }
            if (!empty($newValues)) {
                foreach ($newValues as $key => $val) {
                    if ($val !== 'Y') {
                        continue;
                    }
                    $data = array(
                        $arField['linking_field1'] => $curId,
                        $arField['linking_field2'] => $key
                    );
                    call_user_func_array(
                        array($arField['linking_class'], 'add'),
                        array($data)
                    );
                }
            }
        }
        return false;
    }

    function OnAfterSave($arField, $newValues, $curId)
    {
        self::OnBeforeSave($arField, $newValues, $curId);
    }
}

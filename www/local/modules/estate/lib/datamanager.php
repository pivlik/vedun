<?php
/**
 * Bitrix Framework
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Entity;

/**
 * Class description
 * @package    estate
 * @subpackage datamanager
 */
abstract class DataManager extends Entity\DataManager
{
    const ESTATE_UF_PREFIX = 'ESTATE_';

    /**
     * Возвращает путь к папке с классами модуля
     * @return string
     */
    final public static function getFilePath()
    {
        return __FILE__;
    }

    /**
     * Возвращает название таблицы сущности
     * @return string
     */
    final public static function getTableName()
    {
        $instance = static::getInstance();
        return $instance->_tableName;
    }

    /**
     * Абстрактный метод-синглтон
     * @return string
     */
    abstract public static function getInstance();

    /**
     * Возвращает ассоциативный массив записей сущности
     * @param array $params Параметры для ORM
     * @param string $keyField Название поля для подстановки его значений в ключи массива
     * @param string $valueField Название поля для подстановки его в значения массива
     * @return array
     */
    final public static function getAssoc(array $params = array(), $keyField = false, $valueField = false) {
        $result = array();
        $res = static::getList($params);

        while ($data = $res->fetch()) {
            if ($keyField && !isset($data[$keyField])) {
                $error = "Field {$keyField} is not exists in result array in Estate\DataManager::getAssoc()";
            }
            if ($valueField && !isset($data[$valueField])) {
                $error = "Field {$valueField} is not exists in result array in Estate\DataManager::getAssoc()";
            }
            if (isset($error)) {
                return new Main\SystemException($error);
            }

            $value = $valueField ? $data[$valueField] : $data;
            if ($keyField) {
                $result[(string) $data[$keyField]] = $value;
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * Возвращает ассоциативный массив записей сущности, фильтруя элементы по id
     * @param array $ids ID записей сущности
     * @param string $keyField Название поля для подстановки его значений в ключи массива
     * @param string $valueField Название поля для подстановки его в значения массива
     * @return array
     */
    final public static function getAssocByIds(array $ids = array(), $keyField = false, $valueField = false) {
        $params = array(
            'filter' => array(
                'ID' => $ids,
            ),
        );
        return static::getAssoc($params, $keyField, $valueField);
    }

    /**
     * Осуществляет выборку записей сущности по списку ID
     * Возвращает ассоциативный массив с ID в качестве ключей
     * @param array $ids Массив ID для выборки по ним
     * @return array
     */
    final public static function getByIds(array $ids) {
        return static::getAssocByIds($ids, 'ID');
    }

    /**
     * Осуществляет выборку записей сущности по списку IMPORT_ID
     * Возвращает ассоциативный массив с ID в качестве ключей
     * @param array $ids Массив IMPORT_ID для выборки по ним
     * @return array
     */
    final public static function getByImportIds(array $ids) {
        $params = array(
            'filter' => array(
                'IMPORT_ID' => $ids,
            ),
        );
        return static::getAssoc($params, 'IMPORT_ID');
    }

    /**
     * Возвращает карту свойств сущности включая пользовательские поля
     * @return array
     */
    final public static function getMap()
    {
        IncludeModuleLangFile(self::getFilePath());

        $instance = static::getInstance();
        $fieldsMap = $instance->_fieldsMap;

        // USER FIELDS
        $connection = Main\Application::getConnection();

        $className = get_called_class();
        $pos = strrpos($className, '\\');
        if ($pos !== false) {
            $className = substr($className, $pos + 1);
        }
        $className = addslashes($className);

        $row = EstateTable::getRow(array(
            'select' => array('ID'),
            'filter' => array('CLASS_NAME' => $className),
        ));
        if (!$row) {
            return $fieldsMap;
        }
        $entityId = (int) $row['ID'];

        $userFieldManager = new \CUserTypeManager();
        $fields = $userFieldManager->getUserFields(self::ESTATE_UF_PREFIX . $entityId);
        foreach ($fields as $field) {
            $fieldsMap[$field['FIELD_NAME']] = array(
                'data_type' => \Bitrix\Main\Entity\UField::convertBaseTypeToDataType(
                    $field['USER_TYPE']['BASE_TYPE']
                )
            );
        }
        return $fieldsMap;
    }

    /**
     * Добавляет новую запись в сущность
     * @param array $data Массив для вставки
     * @param array $estate Массив свойств сущности
     * @return AddResult Объект содержащий ID новой записи
     */
    final public static function add(array $data, array $estate = array())
    {
        $dataAfter = $data;
        if ($estate) {
            self::_prepareToDb($data, $estate);
        }
        $resID =  parent::add($data);

        if ($estate && $resID->getId()) {
            self::_afterInsertToDb($dataAfter, $estate, $resID->getId());
        }
        
        return $resID;
    }

    /**
     * Обновляет запись сущности
     * @param int $primary ID записи
     * @param array $data Массив для обновления
     * @param array $estate Массив свойств сущности
     * @return UpdateResult
     */
    final public static function update($primary, array $data, array $estate = array())
    {
        if ($estate) {
            self::_prepareToDb($data, $estate, $primary);
        }
        return parent::update($primary, $data);
    }

    /**
     * Добавляет новую запись в сущность
     * @param int $primary ID записи
     * @param array $estate Массив свойств сущности
     * @return DeleteResult
     */
    final public static function delete($primary, array $estate = array())
    {
        // get old data
        $oldData = static::getByPrimary($primary)->fetch();

        // remove row
        $result = parent::delete($primary);

        if (!$estate) {
            return $result;
        }

        // remove files
        $fields = static::getMap();

        $userFieldManager = new \CUserTypeManager();
        $uFields = $userFieldManager->getUserFields(self::ESTATE_UF_PREFIX . $estate['ID']);

        foreach ($oldData as $k => $v) {
            if (isset($uFields[$k])) {
                $curField  = $uFields[$k];
                $fieldType = $curField["USER_TYPE"]["BASE_TYPE"];
            } else {
                $curField  = $fields[$k];
                $fieldType = isset($curField["field_type"])
                             ? $curField["field_type"]
                             : false;
            }

            if ($fieldType === "file") {
                foreach((array) $oldData[$k] as $value) {
                    \CFile::delete($value);
                }
            }
        }

        return $result;
    }

    /**
     * Обрабатывает массив данных перед вставкой или обновлением записи
     * @param array $data Массив с данными
     * @param array $estate Массив свойств сущности
     * @param int $ID ID обновляемой записи
     * @return void
     */
    final protected static function _prepareToDb(array &$data, array $estate, $ID = false)
    {
        $fields = static::getMap();

        $userFieldManager = new \CUserTypeManager();
        $uFields = $userFieldManager->getUserFields(self::ESTATE_UF_PREFIX . $estate['ID']);

        foreach ($data as $k => $v) {
            if (isset($uFields[$k])) {
                // Пользовательское поле
                $curField    = $uFields[$k];
                $fieldType   = $curField["USER_TYPE"]["BASE_TYPE"];
                $className   = $curField["USER_TYPE"]["CLASS_NAME"];
                $fieldTypeId = $curField["USER_TYPE_ID"];
            } else {
                $curField    = $fields[$k];
                $fieldType   = isset($curField["field_type"])
                               ? $curField["field_type"]
                               : false;
                $className   = $fieldType
                               ? 'FieldType' . ucfirst($fieldType)
                               : false;
                $fieldTypeId = '';
            }

            if ($className && is_callable(array($className, "onbeforesave"))) {
                if (($fieldType === 'file')
                    && !empty($v['old_id'])
                    && ($v['error'] === 4)
                ) {
                    $curField['VALUE'] = $v;
                }
                $data[$k] = call_user_func_array(
                    array($className, "onbeforesave"),
                    array($curField, $data[$k], $ID)
                );
                if ($data[$k] === false) {
                    unset($data[$k]);
                }
            }

            /*if(strlen($v) <= 0) {
                $data[$k] = false;
            } else {*/
                if ($fieldTypeId === 'datetime') {
                    try {
                        $data[$k] = Type\DateTime::createFromUserTime($v);
                    } catch(Main\ObjectException $e) {
                        $data[$k] = '';
                    }
                }
            //}
        }
        //exit;
    }

    /**
     * Обрабатывает массив данных после вставки записи
     * @param array $data Массив с данными
     * @param array $estate Массив свойств сущности
     * @param int $ID ID добавленной записи
     * @return void
     */
    final protected static function _afterInsertToDb(array &$data, array $estate, $ID = false)
    {
        $fields = static::getMap();

        $userFieldManager = new \CUserTypeManager();
        $uFields = $userFieldManager->getUserFields(self::ESTATE_UF_PREFIX . $estate['ID']);

        foreach ($data as $k => $v) {
            if (isset($uFields[$k])) {
                // Пользовательское поле
                $curField    = $uFields[$k];
                $fieldType   = $curField["USER_TYPE"]["BASE_TYPE"];
                $className   = $curField["USER_TYPE"]["CLASS_NAME"];
                $fieldTypeId = $curField["USER_TYPE_ID"];
            } else {
                $curField    = $fields[$k];
                $fieldType   = isset($curField["field_type"])
                    ? $curField["field_type"]
                    : false;
                $className   = $fieldType
                    ? 'FieldType' . ucfirst($fieldType)
                    : false;
                $fieldTypeId = '';
            }

            if ($className && is_callable(array($className, "onaftersave"))) {
                if (($fieldType === 'file')
                    && !empty($v['old_id'])
                    && ($v['error'] === 4)
                ) {
                    $curField['VALUE'] = $v;
                }
                $data[$k] = call_user_func_array(
                    array($className, "onaftersave"),
                    array($curField, $data[$k], $ID)
                );
                if ($data[$k] === false) {
                    unset($data[$k]);
                }
            }

            if ($fieldTypeId === 'datetime') {
                try {
                    $data[$k] = Type\DateTime::createFromUserTime($v);
                } catch(Main\ObjectException $e) {
                    $data[$k] = '';
                }
            }

        }
    }

    // public static function getConnectionName()
    // {
    //     return 'bpearl';
    // }
}

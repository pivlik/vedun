<?php
/**
 * Bitrix Framework
 *
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

use Bitrix\Main\Entity\ExpressionField;

/**
 * Class description
 *
 * @package    estate
 * @subpackage baseestate
 */
abstract class BaseEstate extends DataManager
{

    /**
     * Экземпляр класса сущности
     *
     * @var object
     */
    protected static $_instance = array();

    /**
     * Карта активных элементов недвижимости
     *
     * @const string
     */
    protected static $_activeMap = array();

    /**
     * Текущий активный объект
     *
     * @const int
     */
    protected static $_objectId = 0;

    /**
     * Карта активных объектов недвижимости с привязкой к объектам инфорблоков
     *
     * @const int
     */
    protected static $_activeMapObjects = array();

    /**
     * Инфа по активным объектам недвижимости с привязкой к объектам инфорблоков
     *
     * @const int
     */
    protected static $_activeObjectsFullInfo = array();

    /**
     * Алиас текущего объекта
     *
     * @const int
     */
    protected static $_objectCode = false;

    /**
     * Фильтры для поиска админке
     *
     * @var array
     */
    protected $_filters = array();

    /**
     * Название таблицы сущности
     *
     * @var sting
     */
    protected $_tableName = false;

    /**
     * Заголовки столбцов табцлицы в админке
     *
     * @var array
     */
    protected $_headers = array();

    /**
     * Карта полей сущности
     *
     * @var array
     */
    protected $_fieldsMap = array();

    /**
     * Список дочерних сущностей
     *
     * @var array || int
     */
    protected $_childEntityId = array();

    /**
     * ID родительской сущности
     *
     * @var int
     */
    protected $_parentEntityId;

    /**
     * ID Инфоблока объектов
     *
     * @const int
     */
    const OBJECTS_IBLOCK_ID = 6;

    /**
     * ID Инфоблока корпусов
     *
     * @const int
     */
    const BUILDINGS_IBLOCK_ID = 10;

    /**
     * ID основного объекта
     *
     * @const int
     */
    const MAIN_OBJECT_ID = 1;

    /**
     * Настройки отображения textarea в форме редактирования записи
     *
     * @const int
     */
    const EDIT_FORM_TEXTAREA_COLS = 60;
    const EDIT_FORM_TEXTAREA_ROWS = 4;

    /**
     * Настройки отображения <input type="text"> в форме редактирования записи
     *
     * @const int
     */
    const EDIT_FORM_TEXT_FIELD_SIZE = 50;
    const EDIT_FORM_TEXT_FIELD_MAXLENGTH = 255;

    /**
     * ID типа коммерческих помещений
     *
     * @const int
     */
    const ESTATE_PREMISE_COMMERCIAL_TYPE = 1;

    /**
     * Статус коммерческих помещений "В продаже"
     *
     * @const int
     */
    const ESTATE_COMMERCIAL_IN_SALE_STATUS = 1;

    /**
     * ID сущностей в базовой таблице модуля
     *
     * @const int
     */
    const ESTATE_ENTITY_OBJECT = 1;
    const ESTATE_ENTITY_BUILDING = 2;
    const ESTATE_ENTITY_SECTION = 3;
    const ESTATE_ENTITY_FLOOR = 4;
    const ESTATE_ENTITY_FLAT = 5;
    const ESTATE_ENTITY_CITIES = 6;
    const ESTATE_ENTITY_DISTRICTS = 7;
    const ESTATE_ENTITY_SUBWAY = 8;
    const ESTATE_ENTITY_STAGES = 9;
    const ESTATE_ENTITY_FLAT_STATUSES = 10;
    const ESTATE_ENTITY_FLAT_TYPES = 11;
    const ESTATE_ENTITY_FEATURES = 12;
    const ESTATE_ENTITY_PREMISE = 13;
    const ESTATE_ENTITY_PREMISE_TYPES = 14;
    const ESTATE_ENTITY_PANO = 16;
    const ESTATE_ENTITY_OPTIONS = 17;

    /**
     * Путь к шаблону страниц визуального выборщика
     *
     * @const string
     */
    const DEFAULT_TEMPLATE_PATH = '/local/components/kelnik/estate.selector/templates/.default';

    /**
     * Корень раздела компонента
     *
     * @const string
     */
    const ESTATE_HOME_PATH = '/kvartiry/';

    /**
     * Путь к поиску квартир для сайта с несколькими объектами
     *
     * @const string
     */
    const ESTATE_SEARCH_PATH = '/zhilye-kompleksy/#CODE#/kvartiry/';

    /**
     * Отключить перескоки через шаги выборщика при единственном элементе
     *
     * @const string
     */
    const DISABLE_VISUAL_SEARCH_REDITECTS = true;

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Метод-синглтон для классов сущностей
     *
     * @return object
     */
    final public static function getInstance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    /**
     * Геттер для фильтров
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = $this->_filters;

        if (!$filters) {
            return array();
        }

        foreach ($filters as &$filter) {
            if (!empty($filter['name_lang'])) {
                $filter['name'] = GetMessage($filter['name_lang']);
            }
            if (empty($filter['variants'])) {
                continue;
            }

            $filter['variants'] = array_map('GetMessage', $filter['variants']);
        }
        unset($filter);

        return $filters;
    }

    /**
     * Геттер для столбцов таблицы
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = $this->_headers;

        if (!$headers) {
            return array();
        }

        foreach ($headers as &$val) {
            if (!empty($val['content_lang'])) {
                $val['content'] = GetMessage($val['content_lang']);
            }
        }
        unset($val);

        return $headers;
    }

    /**
     * Возвращает массив данных запроса согласно карте полей сущности
     *
     * @return array
     */
    public function getRequestData()
    {
        $data = array();
        $fields = self::getMap();

        foreach ($fields as $name => $opt) {
            if (!isset($opt['save']) || !isset($_REQUEST[$name])) {
                continue;
            }
            $data[$name] = $_REQUEST[$name];
        }
        return $data;
    }

    final public static function setEstateObject($objectId)
    {
        $row = EstateObjectTable::getRow(array(
            'filter' => array(
                'ID' => $objectId,
            ),
        ));
        if (!$row) {
            return false;
        }
        self::setObject($row['OBJECT']);
        return true;
    }

    final public static function setObject($objectId)
    {
        if (!$object = self::getIblockObjects(array('ID' => $objectId))) {
            return false;
        }
        $object = current($object);
        self::$_objectId = (int)$objectId;
        self::$_objectCode = $object['CODE'];
    }

    final public static function setObjectByCode($code)
    {
        if (!$object = self::getIblockObjects(array('CODE' => $code))) {
            return false;
        }
        $object = current($object);
        self::$_objectCode = $code;
        self::$_objectId = $object['ID'];
    }

    final public static function getIblockObjects(array $filter = array())
    {
        $arSelect = array('ID', 'NAME', 'CODE', 'DETAIL_PICTURE', 'DETAIL_TEXT', 'PROPERTY_RAION', 'PROPERTY_METRO', 'PROPERTY_ADDRESS', 'PROPERTY_CITY');

        $arFilter = $filter;
        $arFilter['IBLOCK_ID'] = self::OBJECTS_IBLOCK_ID;
        $arFilter['ACTIVE'] = 'Y';
        $res = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $objects = array();
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $objects[$arFields['ID']] = $arFields;
        }
        return $objects;
    }

    final public static function getIblockBuilding()
    {
        $arSelect = array('ID', 'NAME', 'PROPERTY_ESTATE_OBJECT', 'PROPERTY_IS_READY', 'PROPERTY_READY_DATE');

        $arFilter['IBLOCK_ID'] = self::BUILDINGS_IBLOCK_ID;
        $arFilter['ACTIVE'] = 'Y';
        $res = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $objects = array();
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $objects[$arFields['ID']] = $arFields;
        }
        return $objects;
    }

    final public static function getIblockObjectsFullInfoWithBuilding()
    {
        if (!empty(self::$_activeObjectsFullInfo)) {
            return self::$_activeObjectsFullInfo;
        }
        //получаем список Объектов из инфоблока
        $iblockObjects = self::getIblockObjects();
        //получаем список Корпусов из инфоблока
        $iblockBuildings = self::getIblockBuilding();
        $params = array(
            'filter' => array(
                'OBJECT' => array_keys($iblockObjects)
            )
        );
        $objectsMap = EstateObjectTable::getAssoc($params, 'ID', 'OBJECT');
        $arResult = array();
        foreach ($iblockObjects as $iObject) {
            $objectID = array_search($iObject['ID'], $objectsMap);
            if (!$objectID) {
                //если не нашли связи объекта с квартирографией, то пропускаем
                continue;
            }

            $iObject['OBJECT_ID'] = $objectID;
            $iObject['BUILDINGS'] = array();
            foreach ($iblockBuildings as $building) {
                if ($building['PROPERTY_ESTATE_OBJECT_VALUE'] == $iObject['ID']) {
                    $iObject['BUILDINGS'][$building['ID']] = $building;
                }
            }
            $arResult[$iObject['ID']] = $iObject;
        }
        self::$_activeObjectsFullInfo = $arResult;

        return $arResult;

    }

    final public static function getIblockMapObjects()
    {
        if (!empty(self::$_activeMapObjects)) {
            return self::$_activeMapObjects;
        }
        //получаем список Объектов из инфоблока
        $iblockObjects = self::getIblockObjects();
        $params = array(
            'filter' => array(
                'OBJECT' => array_keys($iblockObjects)
            )
        );
        self::$_activeMapObjects = EstateObjectTable::getAssoc($params, 'ID', 'OBJECT');

        return self::$_activeMapObjects;
    }

    final public static function getHomePath($code = false)
    {
        if (!$code) {
            return self::ESTATE_BASE_HOME_PATH;
        }
        $code = $code ?: self::$_objectCode;
        return str_replace('#CODE#', $code, self::ESTATE_HOME_PATH);
    }

    /**
     * Возвращает карту активных элементов недвижимости от объектов до этажей
     *
     * @param bool $hasFlats     Проверять наличие квартир в продаже
     * @param bool $activeObject Проверять только активные объекты
     * @return array
     */
    final public static function getActiveElementsMap($hasFlats = true, $activeObject = true)
    {
        $result = array();

        $objectId = self::$_objectId;
        $withFlats = (int)$hasFlats;
        if (!empty(self::$_activeMap[$objectId][$withFlats])) {
            return self::$_activeMap[$objectId][$withFlats];
        }

        $filter = $activeObject ? array('ACTIVE' => 'Y') : array();
        if ($objectId) {
            $filter['OBJECT'] = $objectId;
        }
        $result['OBJECTS'] = EstateObjectTable::getAssoc(
            array(
                'select' => array(
                    'ID',
                    'NAME',
                    //'OBJECT',
                ),
                'filter' => $filter
            ),
            'ID'
        );

        $result['BUILDINGS'] = EstateBuildingTable::getAssoc(
            array(
                'select' => array(
                    'ID',
                    'NAME',
                    'DELIVERY',
                    'PARENT',
                    // 'VISUAL_SKIP',
                    // 'STAGE',
                ),
                'filter' => array(
                    'PARENT' => array_keys($result['OBJECTS']),
                    'ACTIVE' => 'Y',
                ),
            ),
            'ID'
        );

        $result['SECTIONS'] = EstateSectionTable::getAssoc(
            array(
                'select' => array(
                    'ID',
                    'PARENT',
                    // 'VISUAL_SKIP',
                ),
                'filter' => array(
                    'PARENT' => array_keys($result['BUILDINGS']),
                    'ACTIVE' => 'Y',
                )
            ),
            'ID'
        );

        $activeFloors = EstateFlatTable::getAssoc(
            array(
                'select' => array(
                    'PARENT' => new ExpressionField('PARENT', 'DISTINCT PARENT'),
                ),
                'filter' => array(
                    'STATUS' => EstateFlatTable::IN_SALE_STATUS,
                ),
            ),
            false,
            'PARENT'
        );

        $result['FLOORS'] = EstateFloorTable::getAssoc(
            array(
                'select' => array(
                    'ID',
                    'PARENT',
                    'NAME',
                    //'ACTIVE_CHILD_COUNT',
                ),
                'filter' => array(
                    'ACTIVE' => 'Y',
                    'PARENT' => array_keys($result['BUILDINGS']),
                    'ID' => $activeFloors
                    //'>ACTIVE_CHILD_COUNT' => $hasFlats ? 0 : -1,
                ),
                'order' => array(
                    'NUMBER' => 'ASC',
                ),
            ),
            'ID'
        );

        // Пройдемся в обратном направлении
        // $sectionIds = self::getParentsFromResult($result['FLOORS']);
        // $result['SECTIONS'] = array_intersect_key($result['SECTIONS'], array_flip($sectionIds));
        $buildingIds = self::getParentsFromResult($result['FLOORS']);
        $result['BUILDINGS'] = array_intersect_key($result['BUILDINGS'], array_flip($buildingIds));
        $objectIds = self::getParentsFromResult($result['BUILDINGS']);
        $result['OBJECTS'] = array_intersect_key($result['OBJECTS'], array_flip($objectIds));

        self::$_activeMap[$objectId][$withFlats] = $result;
        return $result;
    }

    final public static function getStartPath()
    {
        if (self::DISABLE_VISUAL_SEARCH_REDITECTS) {
            return '';
        }

        $activeMap = self::getActiveElementsMap();

        if (count($activeMap['SECTIONS']) === 1) {
            // Если секция всего одна, делаем редирект на нее
            reset($activeMap['SECTIONS']);
            $sectionId = key($activeMap['SECTIONS']);
            $section = current($activeMap['SECTIONS']);
            $buildingId = $section['PARENT'];
            $path = 'building/' . $buildingId . '/'
                . 'section/' . $sectionId . '/';
            return $path;
        }
        if (count($activeMap['BUILDINGS']) === 1) {
            // Если корпус всего один, делаем редирект на него
            reset($activeMap['BUILDINGS']);
            $building = current($activeMap['BUILDINGS']);
            $path = 'building/' . $building['ID'] . '/';
            return $path;
        }
        return '';
    }

    /**
     * Возвращает ссылку и фразу для возврата на один из предыдущих шагов
     *
     * @return array
     */
    final public static function getBackLink($buildingId, $sectionId = false)
    {
        $activeMap = self::getActiveElementsMap();

        $hasRedirects = !self::DISABLE_VISUAL_SEARCH_REDITECTS;
        $skipBuilding = $activeMap['BUILDINGS'][$buildingId]['VISUAL_SKIP'] === 'Y';
        $skipSection = $activeMap['SECTIONS'][$sectionId]['VISUAL_SKIP'] === 'Y';

        $result = array(
            'text' => 'Выбрать другой корпус',
            'link' => '',
        );
        if ($hasRedirects && count($activeMap['BUILDINGS']) < 2 || $skipBuilding) {
            $result['text'] = '';
        }

        if (!$buildingId || $skipBuilding) {
            return $result;
        }

        // $cnt = $hasRedirects ? 0 : 1;
        // foreach ($activeMap['SECTIONS'] as $ID => $section) {
        //     if ($section['PARENT'] === $buildingId) {
        //         ++$cnt;
        //     }
        //     if ($cnt > 1) {
        //         $result['text'] = 'Выбрать другую секцию';
        //         $result['link'] = 'building/' . $buildingId . '/';
        //         break;
        //     }
        // }

        // if (!$sectionId || $skipSection) {
        //     return $result;
        // }

        $cnt = $hasRedirects ? 0 : 1;
        foreach ($activeMap['FLOORS'] as $ID => $floor) {
            if ($floor['PARENT'] === $buildingId) {
                ++$cnt;
            }
            if ($cnt > 1) {
                $result['text'] = 'Выбрать другой этаж';
                $result['link'] = 'building/' . $buildingId . '/';
                break;
            }
        }

        return $result;
    }

    /**
     * Возвращает элементы массива $children, для которых есть родитель в $parentIds
     *
     * @param array $children Массив элементов сущности
     * @param int || array $parentIds ID родителей
     * @return array
     */
    final public static function filterByParentId(array $children, $parentIds)
    {
        $parentIds = (array)$parentIds;
        foreach ($children as $ID => $val) {
            if (!isset($val['PARENT']) || !in_array($val['PARENT'], $parentIds)) {
                unset($children[$ID]);
            }
        }
        return $children;
    }

    /**
     * Геттер ID дочерних сущностей
     *
     * @return array
     */
    final public function getChildEntity()
    {
        return (array)$this->_childEntityId;
    }

    /**
     * Геттер ID родительской сущности
     *
     * @return int
     */
    final public function getParentEntity()
    {
        return $this->_parentEntityId;
    }

    /**
     * Возвращает список значений PARENT из массива
     *
     * @param array $data Массив выборки сущности из бд
     * @return array
     */
    final public static function getParentsFromResult(array $data)
    {
        $data = array_map(
            function ($arr) {
                return isset($arr['PARENT']) ? $arr['PARENT'] : 0;
            },
            $data
        );
        return array_values(array_unique($data));
    }

    final public static function getDefaultJson()
    {
        $json = array(
            'status' => true,
            'canvasImg' => array(
                'def' => '',
                'alt' => '',
            ),
            'viewName' => '',
            'videoFly' => array(
                'def' => array(
                    'mp4' => '',
                    'webm' => '',
                    'ogg' => ''
                ),
                'alt' => array(
                    'mp4' => '',
                    'webm' => '',
                    'ogg' => ''
                )
            ),
            'videoChangePosition' => array(
                'def' => array(
                    'mp4' => '',
                    'webm' => '',
                    'ogg' => '',
                ),
                'alt' => array(
                    'mp4' => '',
                    'webm' => '',
                    'ogg' => '',
                )
            ),
            'elements' => array(
                'def' => array(),
                'alt' => array(),
            ),
        );
        return $json;
    }

    /**
     * Выводит поля формы редактирования в админке
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    final public function setFormFields(\CAdminForm $tabControl, $row = array())
    {
        $fields = self::getMap();
        foreach ($fields as $name => $opt) {
            if (!isset($opt['field_type'])) {
                continue;
            }

            if (isset($opt['content_lang'])) {
                $opt['content'] = GetMessage($opt['content_lang']);
            }

            $method = '_setFormField' . ucfirst($opt['field_type']);
            if (!is_callable(array($this, $method))) {
                $error = "Не найден метод {$method} для вывода поля {$name}";
                $this->_setFormFieldError($tabControl, $error);
                continue;
            }

            $this->$method($tabControl, $name, $opt, $row);
        }
    }

    /**
     * Выводит текстовое поле
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldText(\CAdminForm $tabControl, $name, $opt, $row)
    {
        $tabControl->AddEditField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required']),
            array(
                'size' => self::EDIT_FORM_TEXT_FIELD_SIZE,
                'maxlength' => self::EDIT_FORM_TEXT_FIELD_MAXLENGTH,
            ),
            isset($row[$name]) ? $row[$name] : ''
        );
    }

    /**
     * Выводит чекбокс
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldCheckbox(\CAdminForm $tabControl, $name, $opt, $row)
    {
        $tabControl->AddCheckBoxField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required']),
            "Y",
            isset($row[$name]) && $row[$name] === 'Y'
        );
    }

    /**
     * Выводит textarea
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldTextarea(\CAdminForm $tabControl, $name, $opt, $row)
    {
        $tabControl->AddTextField(
            $name,
            $opt['content'] . ':',
            isset($row[$name]) ? $row[$name] : '',
            array(
                'cols' => self::EDIT_FORM_TEXTAREA_COLS,
                'rows' => self::EDIT_FORM_TEXTAREA_ROWS,
            )
        );
    }

    /**
     * Выводит файловое поле
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldFile(\CAdminForm $tabControl, $name, $opt, $row)
    {
        $tabControl->AddFileField(
            $name,
            $opt['content'] . ':',
            isset($row[$name]) ? $row[$name] : ''
        );
    }

    /**
     * Выводит селект со значениями другой сущности
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldRefSelect(\CAdminForm $tabControl, $name, $opt, $row)
    {
        if (!isset($opt['ref_class'])) {
            $error = "Не указан класс связанной сущности для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        if (!is_callable(array($opt['ref_class'], 'getAssoc'))) {
            $error = "Класс {$opt['ref_class']} не содержит метод getAssoc";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        $select = array('ID', 'NAME');
        if (isset($opt['key_field'])) {
            $select[] = $opt['key_field'];
        }
        if (isset($opt['value_field'])) {
            $select[] = $opt['value_field'];
        }
        $result = call_user_func_array(
            array($opt['ref_class'], 'getAssoc'),
            array(array('select' => $select), 'ID')
        );
        $arSelect = array();

        if (empty($opt['required'])) {
            $arSelect[''] = '';
        }

        if ($result) {
            $keyField = isset($opt['key_field'])
                ? $opt['key_field']
                : 'ID';
            $valueField = isset($opt['value_field'])
                ? $opt['value_field']
                : 'NAME';

            $firstRow = current($result);
            if (!isset($firstRow[$keyField], $firstRow[$valueField])) {
                $missingField = !isset($firstRow[$keyField])
                    ? $keyField
                    : $valueField;
                $error = "Метод getAssoc класса {$opt['ref_class']}"
                    . " вернул массив не содержащий поле {$missingField}";
                $this->_setFormFieldError($tabControl, $name, $error);
                return;
            }

            foreach ($result as $data) {
                $arSelect[$data[$keyField]] = $data[$valueField];
            }
        }

        $tabControl->AddDropDownField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required']),
            $arSelect,
            isset($row[$name])
                ? $row[$name]
                : (!empty($_GET[$name]) ? (int)$_GET[$name] : '')
        );
    }

    /**
     * Выводит чекбоксы со значениями другой сущности
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldRefCheckboxes(\CAdminForm $tabControl, $name, $opt, $row)
    {
        if (!isset($opt['ref_class'])) {
            $error = "Не указан класс связанной сущности для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }
        if (!isset($opt['linking_class'])) {
            $error = "Не указан класс связывающей сущности для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        if (!is_callable(array($opt['ref_class'], 'getAssoc'))) {
            $error = "Класс {$opt['ref_class']} не содержит метод getAssoc";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        if (!is_callable(array($opt['linking_class'], 'getAssoc'))) {
            $error = "Класс {$opt['linking_class']} не содержит метод getAssoc";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        // Получаем все записи из конечного класса
        $result = call_user_func_array(
            array($opt['ref_class'], 'getAssoc'),
            array(array(), 'ID')
        );
        if (!$result) {
            return;
        }

        // Получаем выбранные значения
        $values = call_user_func_array(
            array($opt['linking_class'], 'getAssoc'),
            array(
                array(
                    'select' => array('KEY' => $opt['linking_field2']),
                    'filter' => array($opt['linking_field1'] => $row['ID'])
                ),
                'KEY',
                'KEY'
            )
        );

        $arSelect = array();
        $keyField = isset($opt['ref_key'])
            ? $opt['ref_key'] : 'ID';
        $valueField = isset($opt['ref_value'])
            ? $opt['ref_value'] : 'NAME';

        $firstRow = current($result);
        if (!isset($firstRow[$keyField], $firstRow[$valueField])) {
            $missingField = !isset($firstRow[$keyField])
                ? $keyField
                : $valueField;
            $error = "Метод getAssoc класса {$opt['ref_class']}"
                . " вернул массив не содержащий поле {$missingField}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        foreach ($result as $data) {
            $arSelect[$data[$keyField]] = $data[$valueField];
            $tabControl->AddCheckBoxField(
                $name . '[' . $data[$keyField] . ']',
                $data[$valueField] . ':',
                !empty($opt['required']),
                "Y",
                isset($values[$data[$keyField]])
            );
        }
    }

    /**
     * Выводит мультиселект со элементами инфоблока
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldRefIblockMulti(\CAdminForm $tabControl, $name, $opt, $row)
    {
        if (!isset($opt['iblock']) || !ctype_digit($opt['iblock'])) {
            $error = "Не указан ID связанного инфоблока для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        $html = '<select name="' . $name . '[]" multiple>';
        $values = unserialize($row[$name]);

        $arSelect = array(
            'ID',
            'NAME',
        );
        $arFilter = !empty($opt['filter']) ? $opt['filter'] : array();
        $arOrder = array(
            'ID' => 'ASC'
        );
        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            //$arSelect[$arFields['ID']] = $arFields['NAME'];
            $selected = in_array($arFields['ID'], $values) ? ' selected' : '';
            $html .= '<option value="' . $arFields['ID'] . '"' . $selected . '>'
                . $arFields['NAME'] . '</option>';
        }
        $html .= '</select>';

        $tabControl->BeginCustomField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required'])
        );
        echo '<td width="40%">'
            . ($required ? '<span class="adm-required-field">' : '')
            . $tabControl->GetCustomLabelHTML($name, $opt['content'] . ':')
            . ($required ? '</span>' : '')
            . '</td>'
            . '<td>' . $html . '</td>';
        $tabControl->EndCustomField($name);
    }

    /**
     * Выводит селект с элементами инфоблока
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldRefIblock(\CAdminForm $tabControl, $name, $opt, $row)
    {
        if (!isset($opt['iblock']) || (!ctype_digit($opt['iblock']) && !is_int($opt['iblock']))) {
            $error = "Не указан ID связанного инфоблока для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        $value = $row[$name];

        $arSelect = array(
            'ID',
            'NAME',
        );
        $arFilter = !empty($opt['filter']) ? $opt['filter'] : array();
        $arFilter['IBLOCK_ID'] = $opt['iblock'];

        $arOrder = array(
            'ID' => 'ASC'
        );
        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        $selectOptions = array();
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $selectOptions[$arFields['ID']] = $arFields['NAME'];
        }

        $tabControl->AddDropDownField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required']),
            $selectOptions,
            isset($row[$name])
                ? $row[$name]
                : (!empty($_GET[$name]) ? (int)$_GET[$name] : '')
        );
    }

    /**
     * Выводит селект с элементами инфоблока
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldRefBuildingWithObjects(\CAdminForm $tabControl, $name, $opt, $row)
    {
        if (!isset($opt['iblock']) || (!ctype_digit($opt['iblock']) && !is_int($opt['iblock']))) {
            $error = "Не указан ID связанного инфоблока для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        $value = $row[$name];

        $arSelect = array(
            'ID',
            'NAME',
            'PROPERTY_ESTATE_OBJECT.NAME',
        );
        $arFilter = !empty($opt['filter']) ? $opt['filter'] : array();
        $arFilter['IBLOCK_ID'] = $opt['iblock'];

        $arOrder = array(
            'ID' => 'ASC'
        );
        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
        $selectOptions = array();
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $selectOptions[$arFields['ID']] = $arFields['PROPERTY_ESTATE_OBJECT_NAME'] . '-' . $arFields['NAME'];
        }

        $tabControl->AddDropDownField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required']),
            $selectOptions,
            isset($row[$name])
                ? $row[$name]
                : (!empty($_GET[$name]) ? (int)$_GET[$name] : '')
        );
    }

    /**
     * Выводит мультиселект со элементами другой сущности
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param array $opt              Свойства поля из карты полей
     * @param array $row              Сохраненные значения полей формы
     * @return void
     */
    protected function _setFormFieldRefSelectMulti(\CAdminForm $tabControl, $name, $opt, $row)
    {
        if (!isset($opt['ref_class'])) {
            $error = "Не указан класс связанной сущности для поля {$name}";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        if (!is_callable(array($opt['ref_class'], 'getAssoc'))) {
            $error = "Класс {$opt['ref_class']} не содержит метод getAssoc";
            $this->_setFormFieldError($tabControl, $name, $error);
            return;
        }

        $html = '<select name="' . $name . '[]" multiple>'
            . '<option value=""></option>';
        $values = unserialize($row[$name]);

        // Получаем все записи из конечного класса
        $filter = array();
        if (isset($opt['filter'])) {
            foreach ($opt['filter'] as $key => $val) {
                if (is_array($val)) {
                    $filter[$key] = $row[$val[0]];
                } else {
                    $filter[$key] = $val;
                }
            }
        }
        $result = call_user_func_array(
            array($opt['ref_class'], 'getAssoc'),
            array(
                array(
                    'filter' => $filter,
                    'order' => array(
                        'NAME' => 'ASC',
                    ),
                ),
                'ID',
                'NAME'
            )
        );
        if (!$result) {
            return;
        }

        foreach ($result as $ID => $val) {
            //$arSelect[$arFields['ID']] = $arFields['NAME'];
            $selected = in_array($ID, $values) ? ' selected' : '';
            $html .= '<option value="' . $ID . '"' . $selected . '>'
                . $val . '</option>';
        }
        $html .= '</select>';

        $tabControl->BeginCustomField(
            $name,
            $opt['content'] . ':',
            !empty($opt['required'])
        );
        echo '<td width="40%">'
            . ($required ? '<span class="adm-required-field">' : '')
            . $tabControl->GetCustomLabelHTML($name, $opt['content'] . ':')
            . ($required ? '</span>' : '')
            . '</td>'
            . '<td>' . $html . '</td>';
        $tabControl->EndCustomField($name);
    }

    /**
     * Выводит сообщение об ошибке
     *
     * @param \CAdminForm $tabControl Объект менеджера форм
     * @param string $name            Имя поля
     * @param string $text            Текст ошибки
     * @return void
     */
    protected function _setFormFieldError(\CAdminForm $tabControl, $name, $text)
    {
        $tabControl->AddViewField($name, 'ОШИБКА!',
            '<span style="color:red;">' . $text . '</span>'
        );
    }

    public static function clearComponentsCache()
    {
    }
}

<?php
/**
 * Bitrix Framework
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

use Bitrix\Main\Entity\ExpressionField;

/**
 * Class description
 * @package    estate
 * @subpackage estatefloor
 */
class EstateFlatTable extends BaseEstate
{
    /**
     * Статус квартир "В продаже"
     * @const int
     */
    const IN_SALE_STATUS = 1;
    const NOT_IN_SALE_STATUS = 3;

    const COUNT_ON_PAGE = 18;

    public static $_instance = null;

    protected $_tableName = 'estate_flat';

    protected $_parentEntityId = self::ESTATE_ENTITY_FLOOR;

    protected $_defaultSearchOrder = array('PRICE_TOTAL' => 'ASC');

    protected $_typesColors = array('', '#ffc3c3', '#efe07e', '#94d388', '#88c9ee');

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type' => 'integer',
            'primary' => true,
            'autocomplete' => true,
        ),
        /*'ACTIVE' => array(
            'data_type'    => 'boolean',
            'values'       => array('N','Y'),
            'content_lang' => 'IBLOCK_FIELD_ACTIVE',
            'field_type'   => 'checkbox',
        ),*/
        'NAME' => array(
            'data_type' => 'string',
            'required' => true,
            'save' => true,
            'content' => 'Номер',
            'field_type' => 'text',
        ),
        'TYPE' => array(
            'data_type' => 'integer',
            'required' => true,
            'save' => true,
            'content' => 'Тип квартиры',
            'field_type' => 'refSelect',
            'ref_class' => '\Bitrix\Estate\EstateRefFlatTypesTable',
        ),
        'ROOMS' => array(
            'data_type' => 'integer',
            'save' => true,
            'content' => 'Количество комнат',
            'field_type' => 'text',
        ),
        'PRICE' => array(
            'data_type' => 'integer',
            //'save'       => true,
            'content' => 'Цена при 100% оплате',
            'field_type' => 'text',
        ),
        'PRICE_TOTAL' => array(
            'data_type' => 'integer',
            'save' => true,
            'content' => 'Конечная цена (с учетом акции)',
            'field_type' => 'text',
        ),
        'PRICE_METER' => array(
            'data_type' => 'integer',
            'save' => true,
            'content' => 'Цена за кв.м.',
            'field_type' => 'text',
        ),
        'AREA_TOTAL' => array(
            'data_type' => 'float',
            'save' => true,
            'content' => 'Общая площадь',
            'field_type' => 'text',
        ),
        'AREA_LIVING' => array(
            'data_type' => 'float',
            'save' => true,
            'content' => 'Жилая площадь',
            'field_type' => 'text',
        ),
        'AREA_KITCHEN' => array(
            'data_type' => 'float',
            'save' => true,
            'content' => 'Площадь кухни',
            'field_type' => 'text',
        ),
        'IMAGE' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Изображение',
        ),
        'IMAGE_3D' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Изображение 3D',
        ),
        'IMAGE_ON_FLOOR' => array(
            'data_type' => 'string',
            'save' => true,
            'field_type' => 'file',
            'content' => 'Положение на схеме этажа',
        ),
//        'PDF' => array(
//            'data_type'  => 'string',
//            'save'       => true,
//            'field_type' => 'file',
//            'content'    => 'PDF с планировкой',
//        ),
        'PLANOPLAN' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Номер в планоплане',
            'field_type' => 'text',
        ),
        // 'VISUAL_LINK' => array(
        //     'data_type'  => 'string',
        //     'save'       => true,
        //     'content'    => 'Переход по ссылке в визуальном поиске',
        //     'field_type' => 'text',
        // ),
        'NAV_COORD' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'Координаты для навигатора',
            'field_type' => 'textarea',
        ),
        'STATUS' => array(
            'data_type' => 'integer',
            'required' => true,
            'save' => true,
            'content' => 'Статус',
            'field_type' => 'refSelect',
            'ref_class' => '\Bitrix\Estate\EstateRefFlatStatusesTable',
        ),
        'IMPORT_ID' => array(
            'data_type' => 'string',
            'save' => true,
            'content' => 'ID выгрузки',
            'field_type' => 'text',
        ),
        'SECTION' => array(
            'data_type' => 'integer',
        ),
        'PARENT' => array(
            'data_type' => 'integer',
            'required' => true,
            'save' => true,
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_FLOOR_FIELD',
            'field_type' => 'refSelect',
            'ref_class' => '\Bitrix\Estate\EstateFloorTable',
            'value_field' => 'NAME_WITH_PARENT',
        ),
        'FEATURES' => array(
            'data_type' => 'integer',
            'expression' => '1',
            'save' => true,
            'content' => 'Особенности квартир',
            'field_type' => 'refCheckboxes',
            'ref_class' => '\Bitrix\Estate\EstateRefFeaturesTable',
            'linking_class' => '\Bitrix\Estate\EstateFlatFeaturesTable',
            'linking_field1' => 'FLAT',
            'linking_field2' => 'FEATURE',
        ),
        'OPTIONS' => array(
            'data_type' => 'integer',
            'expression' => '1',
            'save' => true,
            'content' => 'Дополнительные опции',
            'field_type' => 'refCheckboxes',
            'ref_class' => '\Bitrix\Estate\EstateRefOptionsTable',
            'linking_class' => '\Bitrix\Estate\EstateFlatOptionsTable',
            'linking_field1' => 'FLAT',
            'linking_field2' => 'OPTION',
        ),
        'PARENT_NAME' => array(
            'data_type' => 'integer',
            'expression' => array(
                '(SELECT CONCAT(estate_object.NAME, " - ", estate_building.NAME,
                                " - этаж ", estate_floor.NAME)
                    FROM estate_floor
                    LEFT JOIN estate_building ON estate_building.ID = estate_floor.PARENT
                    LEFT JOIN estate_object ON estate_object.ID = estate_building.PARENT
                    WHERE estate_floor.ID = %s
                )',
                'PARENT',
            ),
        ),
        'TYPE_NAME' => array(
            'data_type' => 'string',
            'expression' => array(
                '(SELECT NAME FROM estateref_flattypes WHERE ID = %s)',
                'TYPE',
            ),
        ),
        'STATUS_NAME' => array(
            'data_type' => 'integer',
            'expression' => array(
                '(SELECT NAME FROM estateref_flatstatuses WHERE ID = %s)',
                'STATUS',
            ),
        ),
        'FEATURESTABLE' => array(
            'data_type' => 'EstateFlatFeaturesTable',
            'reference' => array('=this.ID' => 'ref.FLAT'),
        ),
        'OPTIONSTABLE' => array(
            'data_type' => 'EstateFlatOptionsTable',
            'reference' => array('=this.ID' => 'ref.FLAT'),
        ),
        'HAVING_CNT' => array(
            'data_type' => 'integer',
            'expression' => array('count(%s)', 'ID'),
        ),
        'RAND' => array(
            'data_type' => 'integer',
            'expression' => array('rand()'),
        ),
        'IS_ACTION' => array(
            'data_type' => 'integer',
            'expression' => array('(PRICE - PRICE_TOTAL)'),
        ),
        'NUMBER' => array(
            'data_type' => 'integer',
            'expression' => array('NAME+0'),
        ),
        'REF_FLOOR' => array(
            'data_type' => 'EstateFloorTable',
            'reference' => array('=this.PARENT' => 'ref.ID'),
        ),
        'REF_SECTION' => array(
            'data_type' => 'EstateSectionTable',
            'reference' => array(
                '=this.SECTION' => 'ref.ID'
            )
        ),
    );

    protected $_headers = array(
        array(
            'id' => 'ID',
            'content' => 'ID',
            'sort' => 'ID',
        ),
        array(
            'id' => 'NAME',
            'content' => 'Номер',
            'sort' => 'NAME',
        ),
        array(
            'id' => 'TYPE_NAME',
            'content' => 'Тип квартиры',
            'sort' => 'TYPE_NAME',
        ),
        array(
            'id' => 'STATUS_NAME',
            'content' => 'Статус',
            'sort' => 'STATUS_NAME',
        ),
        array(
            'id' => 'PRICE_TOTAL',
            'content' => 'Конечная цена',
            'sort' => 'PRICE_TOTAL',
        ),
        // array(
        //     'id'      => 'PRICE',
        //     'content' => 'Цена',
        //     'sort'    => 'PRICE',
        // ),
        array(
            'id' => 'AREA_TOTAL',
            'content' => 'Площадь',
            'sort' => 'AREA_TOTAL',
        ),
        array(
            'id' => 'PARENT_NAME',
            'content_lang' => 'ESTATE_ADMIN_ROW_PARENT_FLOOR_FIELD',
            'sort' => 'PARENT_NAME',
            'filter' => 'PARENT',
        ),
    );

    protected $_filters = array(
        'ID' => array(
            'param' => 'find_id',
            'name' => 'ID',
        ),
        '%NAME' => array(
            'param' => 'find_name',
            'name' => 'Номер',
        ),
        'PARENT' => array(
            'param' => 'find_parent',
            'name_lang' => 'ESTATE_ADMIN_ROW_PARENT_FLOOR_ID_FIELD',
        ),
        '%TYPE_NAME' => array(
            'param' => 'find_type_name',
            'name' => 'Тип квартиры',
        ),
        '%STATUS_NAME' => array(
            'param' => 'find_status_name',
            'name' => 'Статус',
        ),
        /*'ACTIVE' => array(
            'param'     => 'find_active',
            'name_lang' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE',
            'variants'  => array(
                'Y' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_YES',
                'N' => 'ESTATE_ADMIN_ROWS_LIST_ACTIVE_NO'
            )
        )*/
    );

    /**
     * Возвращает полную информацию по квартире
     * @param int $flatId ID квартиры
     * @return array
     */
    public function getFullFlatInfo($flatId)
    {
        $result = array();

        // Данные квартиры
        $params = array(
            'filter' => array(
                'ID' => $flatId,
            ),
            'select' => array(
                'ID', 'NAME', 'TYPE_NAME', 'STATUS_NAME', 'IMAGE', 'IMAGE_3D',
                'STATUS', 'TYPE', 'IMAGE_ON_FLOOR', 'AREA_TOTAL', 'AREA_LIVING',
                'AREA_KITCHEN', 'PRICE',
                'PRICE_TOTAL', 'PARENT', 'PRICE_METER', 'SECTION', 'PLANOPLAN',
            ),
        );
        $result = EstateFlatTable::getRow($params);
        if (!$result) {
            return false;
        }
        $result = $this->_makeFullFlatsArray(array($result['ID'] => $result));
        $result = array_shift($result);

        // Данные очереди
        // $res = EstateRefStagesTable::getById($result['BUILDING']['STAGE']);
        // $result['STAGE'] = $res->fetch();

        $result['IN_SALE'] = (int)$result['STATUS'] === self::IN_SALE_STATUS;
        $result['STATUS_NAME'] = mb_strtolower($result['STATUS_NAME'], 'UTF-8');
        $result['ROOMS_NAME'] = mb_strtolower($result['ROOMS_NAME'], 'UTF-8');

//        $result['BUILDING']['LOCATION_IMAGE'] = \CFile::GetFileArray(
//            $result['BUILDING']['LOCATION_IMAGE']
//        );
//         $result['SECTION']['LOCATION_IMAGE'] = \CFile::GetFileArray(
//             $result['SECTION']['LOCATION_IMAGE']
//         );

        $result['IMAGE_THUMB'] = array(
            'src' => Common::_getImageOrPlaceholder($result['IMAGE_ID'], 306, 210)
        );

        $result['IMAGE_THUMB_MICRO'] = array(
            'src' => Common::_getImageOrPlaceholder($result['IMAGE_ID'], 65, 130)
        );

        $result['IMAGE_3D_THUMB'] = array(
            'src' => Common::_getImageOrPlaceholder($result['IMAGE_3D_ID'], 306, 210)
        );

        $result['SECTION']['IMAGE_ON_BUILDING'] = array(
            'src' => Common::_getImageOrPlaceholder($result['SECTION']['IMAGE_ON_BUILDING'], 240, 250)
        );

        return $result;
    }

    /**
     * Возвращает массив квартир найденных по фильтру
     * @param array $params Массив для запроса к ORM
     * @return array
     */
    public function getFlatsList(array $params = array())
    {
        $params['select'] = array(
            'ID', 'NAME', 'PRICE', 'PRICE_TOTAL', 'AREA_TOTAL', 'AREA_KITCHEN',
            'AREA_LIVING', 'PARENT',
            'ROOMS', 'TYPE_NAME', 'IMAGE', 'PRICE_METER', 'PLANOPLAN',
        );

        $result = self::getAssoc($params, 'ID');

        $result = $this->_makeFullFlatsArray($result);

        return $result;
    }

    /**
     * Возвращает массив этажей с количеством комнат
     * @param array $filter Массив с фильтрами для ORM
     * @return array
     */
    public function getCntByFloors(array $filter = array())
    {
        $floors = array();
        $res = self::getList(array(
            'select' => array(
                'PARENT',
                'HAVING_CNT',
            ),
            'group' => array('PARENT'),
            'filter' => $filter,
        ));
        while ($data = $res->fetch()) {
            $floors[$data['PARENT']] = array('CNT' => $data['HAVING_CNT']);
        }
        return $floors;
    }

    /**
     * Возвращает массив похожих квартир,
     * добавляет разницу по полщади, стоимости и этажу в сравнении с текущей квартирой
     * @param array $filter Массив с фильтрами для ORM
     * @param array $flatInfo Данные квартиры с которой сравниваем
     * @return array
     */
    public function getSameFlats(array $filter, array $flatInfo)
    {
        $result = EstateFlatTable::getAssoc($filter, 'ID');

        $result = $this->_makeFullFlatsArray($result);
        foreach ($result as &$v) {
            $v['DIFF_AREA'] = $v['AREA_TOTAL'] - $flatInfo['AREA_TOTAL'];
            $v['DIFF_AREA'] = round($v['DIFF_AREA'], 2);
            $v['DIFF_PRICE'] = $v['PRICE_TOTAL'] - $flatInfo['PRICE_TOTAL'];
            $v['DIFF_PRICE_F'] = price_format(abs($v['DIFF_PRICE']));
            $v['DIFF_FLOOR'] = $v['FLOOR']['NAME'] - $flatInfo['FLOOR']['NAME'];
            $v['DIFF_FLOOR_WORD'] = plural(
                abs($v['DIFF_FLOOR']),
                array('этаж', 'этажа', 'этажей')
            );
        }
        unset($v);

        $filter['filter']['!ID'] = array_keys($result);
        $filter['select'] = array(
            'cnt' => new ExpressionField('cnt', 'COUNT(*)'),
        );

        $res = EstateFlatTable::getRow($filter);
        $cnt = $res['cnt'];
        $word = plural($res['cnt'], array('квартира', 'квартиры', 'квартир'));

        $res = array('FLATS' => $result, 'CNT' => $cnt, 'WORD' => $word);
        return $res;
    }

    /**
     * Обрабатывает массив с данными квартир,
     * добавляет инфо о родителях, опциях, путях к картинкам и проч.
     * @param array $items Массив с квартирами
     * @return array
     */
    protected function _makeFullFlatsArray(array $items)
    {
        $favorite = EstateFavoriteTable::getInstance();
        $favoriteIds = $favorite->getFavoriteFlats();

        // Данные о родителях от этажей до корпусов
        $floorsIds = BaseEstate::getParentsFromResult($items);
        $floors = EstateFloorTable::getByIds($floorsIds);

        // $sectionsIds = BaseEstate::getParentsFromResult($floors);
        $sectionsIds = array_column($items, 'SECTION');
        $sections = EstateSectionTable::getByIds($sectionsIds);

        $buildingsIds = BaseEstate::getParentsFromResult($floors);
        $buildings = EstateBuildingTable::getByIds($buildingsIds);


        // Особенности. опции
        $res = EstateFlatFeaturesTable::getList(array(
            'select' => array('FLAT', 'FEATURE_NAME'),
            'filter' => array('FLAT' => array_keys($items)),
        ));
        while ($data = $res->fetch()) {
            $item = &$items[$data['FLAT']];
            if (!isset($item['PARAMS'])) {
                $item['PARAMS'] = array();
            }
            $item['PARAMS'][] = $data['FEATURE_NAME'];
        }
        unset($item);

        // Доп. опции
        $res = EstateFlatOptionsTable::getList(array(
            'select' => array('FLAT', 'OPTION_NAME'),
            'filter' => array('FLAT' => array_keys($items)),
        ));
        while ($data = $res->fetch()) {
            $item = &$items[$data['FLAT']];
            if (!isset($item['OPTIONS'])) {
                $item['OPTIONS'] = array();
            }
            $item['OPTIONS'][] = $data['OPTION_NAME'];
        }
        unset($item);

        foreach ($items as $id => &$item) {
            if (!isset($floors[$item['PARENT']])) {
                continue;
            }
            $floor = $floors[$item['PARENT']];
            $item['FLOOR'] = $floor;

            // if (!isset($sections[$floor['PARENT']])) {
            //     continue;
            // }
            $section = $sections[$item['SECTION']];
            $item['SECTION'] = $section;

            if (!isset($buildings[$floor['PARENT']])) {
                continue;
            }
            $item['BUILDING'] = $buildings[$floor['PARENT']];
            $item['BUILDING']['IMAGE_IN_OBJECT'] = \CFILE::GetPath($item['BUILDING']['IMAGE_IN_OBJECT']);

            $item['HREF'] = BaseEstate::ESTATE_HOME_PATH . 'flat/' . $item['ID'] . '/';

            $item['DESCR'] = 'Корпус ' . $item['BUILDING']['NAME'];
            $item['PARAMS_STR'] = mb_ucfirst(implode(', ', $item['PARAMS']));
            $item['OPTIONS_STR'] = mb_ucfirst(implode(', ', $item['OPTIONS']));

            $item['DISCOUNT'] = $item['PRICE_TOTAL'] !== $item['PRICE'];
            $item['FAVORITE'] = isset($favoriteIds[$item['ID']]);

            $item['PRICE_F'] = price_format($item['PRICE']);
            $item['PRICE_TOTAL_F'] = price_format($item['PRICE_TOTAL']);
            $item['PRICE_METER_F'] = price_format($item['PRICE_METER']);

            //Форматируем площадь
            $item['AREA_TOTAL_F'] = formatDelimeter($item['AREA_TOTAL']);
            $item['AREA_LIVING_F'] = formatDelimeter($item['AREA_LIVING']);
            $item['AREA_KITCHEN_F'] = formatDelimeter($item['AREA_KITCHEN']);

            $item['IMAGE_3D_BIG'] = array(
                'SRC' => Common::_getImageOrPlaceholder($item['IMAGE_3D'], 1920, 1080)
            );
            $item['IMAGE_3D'] = array(
                'SRC' => Common::_getImageOrPlaceholder($item['IMAGE_3D'], 430, 336)
            );

            $item['IMAGE_ON_FLOOR'] = array(
                'SRC' => Common::_getImageOrPlaceholder($item['IMAGE_ON_FLOOR'], 500, 300)
            );

            $item['IMAGE_ID'] = $item['IMAGE'];
            $item['IMAGE_BIG'] = array(
                'SRC' => Common::_getImageOrPlaceholder($item['IMAGE'], 1920, 1080)
            );
            $item['IMAGE'] = array(
                'SRC' => Common::_getImageOrPlaceholder($item['IMAGE'], 522, 415)
            );

            $item['IMAGE_THUMB'] = array(
                'src' => Common::_getImageOrPlaceholder($item['IMAGE_ID'], 319, 260)
            );

            $item['IMAGE_THUMB_MICRO'] = array(
                'src' => Common::_getImageOrPlaceholder($item['IMAGE_ID'], 65, 130)
            );
            $item['PDF'] = \CFile::GetPath($item['PDF']);
        }
        unset($item);

        return $items;
    }



    /**
     * Возвращает JSON для выборщика квартир на этаже
     * @param array $floor Массив с данными родительского этажа
     * @param array $filter Массив с фильтрами ORM
     * @return array
     */
    public function getJson(array $floor, array $filter)
    {
        $json = BaseEstate::getDefaultJson();

        $navImage = \CFile::GetFileArray($floor['NAV_IMAGE']);
        $json['canvasImg'] = array(
            'def' => $navImage['SRC'],
            'alt' => $navImage['SRC'],
        );

        $activeMap = BaseEstate::getActiveElementsMap();
        $buildingId = $floor['PARENT'];
        $json['viewName'] = $floor['NAME'];
        $back = BaseEstate::getBackLink($buildingId);
        $json['backText'] = $back['text'];
        $json['backLink'] = $back['link'];

        // Все этажи корпуса
        $buildingFloors = BaseEstate::filterByParentId(
            $activeMap['FLOORS'],
            $floor['PARENT']
        );
        $filter['PARENT'] = array_keys($buildingFloors);
        // Этажи секции по фильтрам
        $floors = $this->getCntByFloors($filter);

        $buildingFloors = array_reverse($buildingFloors);
        $json['floors'] = array();
        $currentFloorNumber = 1;
        $arFloors = array();
        foreach ($buildingFloors as $item) {
            $selected = (int)$item['ID'] === (int)$floor['ID'];
            $item['selected'] = $selected;
            $arFloors[$item['NAME']][$item['ID']] = $item;
        }

        foreach ($arFloors as $arFloorNumbers) {
            $item = false;
            ksort($arFloorNumbers);
            foreach ($arFloorNumbers as $arCurrFloor) {
                if ($arCurrFloor['selected']) {
                    $item = $arCurrFloor;
                    $currentFloorNumber = $item['NAME'];
                }
            }

            if (!$item) {
                $item = array_shift($arFloorNumbers);
            }

            $json['floors'][] = array(
                'link' => 'building/' . $buildingId . '/floor/' . $item['ID'] . '/',
                'number' => $item['NAME'],
                'selected' => (int)$item['selected'],
                'disabled' => (int)!isset($floors[$item['ID']]),
            );
        }

        $floorsInBuildings = array();
        foreach ($buildingFloors as $item) {
            if ($item['NAME'] === $currentFloorNumber) {
                $floorsInBuildings[] = $item['ID'];
            }
        }

//        $json['floorsInBuildings'] = $floorsInBuildings;
        // Типы квартир
        $flatTypes = EstateRefFlatTypesTable::getAssoc(array(), 'ID');

        // Квартиры этажа
        $flats = self::getAssoc(
            array('filter' => array(
                'PARENT' => $floorsInBuildings
            )),
            'ID'
        );
        // Активные квартиры этажа
        $filter['PARENT'] = $floorsInBuildings;
        $activeFlats = self::getAssoc(
            array('filter' => $filter),
            'ID',
            'ID'
        );
        $json['elements'] = array('def' => array(), 'alt' => array());
        foreach ($flats as $item) {
            $isAvail = (int)$item['STATUS'] === self::IN_SALE_STATUS;
            $isMatching = isset($activeFlats[$item['ID']]);
            $content = '';
            $currentFloor = $activeMap['FLOORS'][$item['PARENT']];
            $currentBuilding = $activeMap['BUILDINGS'][$currentFloor['PARENT']];
            $flatType = ((int)($flatTypes[$item['TYPE']]['ROOMS'])) ? $item['ROOMS'] . '-комнатная' : $flatTypes[$item['TYPE']]['NAME'];
            if ($isAvail) {
                $content = ' <ul class="b-param-list">'
                    . '<li class="b-param-list__item"><div class="b-param-list__name">Общая площадь</div>'
                    . '<div class="b-param-list__data">' . $item['AREA_TOTAL'] . '&nbsp;м<sup>2</sup></div></li>'
                    . '<li class="b-param-list__item"><div class="b-param-list__name">Жилая площадь</div>'
                    . '<div class="b-param-list__data">' . $item['AREA_LIVING'] . '&nbsp;м<sup>2</sup></div></li>'
                    . '<li class="b-param-list__item"><div class="b-param-list__name">Стоимость</div>'
                    . '<div class="b-param-list__data">' . price_format($item['PRICE_TOTAL']) . '<span class="b-ruble">o</span></div></li>'
                    . '</ul>';
            }
            $link = '/kvartiry/flat/' . $item['ID'] . '/';
            $toPage = !empty($item['VISUAL_LINK']) ? $item['VISUAL_LINK'] : false;
            $json['elements']['alt'][] = $json['elements']['def'][] = array(
                'id' => $item['ID'],
                'coords' => $item['NAV_COORD'],
                'status' => (int)($isAvail && $isMatching),
                'link' => ((int)($isAvail && $isMatching)) ? $link : '',
                'available' => (int)$isAvail,
                'Matching' => (int)$isMatching,
                'color' => ((int)($isAvail && $isMatching)) ? $this->_typesColors[$item['TYPE']] : '#F3E7BC',
                'toPage' => $toPage,
                'rooms' => $flatTypes[$item['TYPE']]['ROOMS'],
                'tooltip' => array(
                    'room' => ((int)($isAvail && $isMatching)) ? $flatType : 'Нет в продаже',
                    'title' => 'Квартира № ' . $item['NAME'],
                    'floor' => 'Корпус ' . $currentBuilding['NAME'] . ', этаж ' . $currentFloor['NAME'],
                    'content' => $content,
                ),
                //'isMatching' => (int) $isMatching,
                //'title'        => $title,
                //'price'        => $item['PRICE_TOTAL'],
                //'area'         => $item['AREA_TOTAL'],
            );
        }
        return $json;
    }


    /**
     * Подставляет данные квартиры в маску ссылки на этаж
     * @param string $mask Маска ссылки на этаж
     * @param array $flat Данные квартиры
     * @return string
     */
    public function getVisualFloorLink($mask, array $flat)
    {
        $link = str_replace(
            array(
                '#BUILDING_ID#',
                // '#SECTION_ID#',
                '#FLOOR_ID#'
            ),
            array(
                $flat['BUILDING']['ID'],
                // $flat['SECTION']['ID'],
                $flat['PARENT']
            ),
            $mask
        );
        return $link;
    }

    public static function clearComponentsCache()
    {
        $sites = array();
        $res = \CSite::GetList($by = 'sort', $order = 'asc', array(
            'ACTIVE' => 'Y'
        ));
        while ($arSite = $res->Fetch()) {
            $sites[] = $arSite['ID'];
        }

        $components = array(
            "kelnik:estate.flat",
            "kelnik:estate.search",
        );
        $obCache = new \CPHPCache;
        foreach ($components as $component) {
            // Находим путь к компоненту, из его имени
            $componentPath = \CComponentEngine::MakeComponentPath($component);

            if (!$componentPath) {
                continue;
            }

            foreach ($sites as $siteId) {
                $cache_path = '/' . $siteId . $componentPath;
                $obCache->CleanDir($cache_path, "cache");
                BXClearCache(true, $cache_path);
            }
        }
    }

    public function getSearchRequest()
    {
        return EstateSearch::getInstance()->getSearchRequest();
    }

    protected function _getParentByRequest()
    {
        return EstateSearch::getInstance()->_getParentByRequest();
    }

    public function getSearchOrder()
    {
        return EstateSearch::getInstance()->getSearchOrder();
    }

    public function getSearchForm()
    {
        return EstateSearch::getInstance()->getSearchForm();
    }

    public function getFilterBorders(array $filter = array(), $withSliders = false)
    {
        return EstateSearch::getInstance()->getFilterBorders($filter, $withSliders);
    }

    public function getResultCount(array $filter = array())
    {
        return EstateSearch::getInstance()->getResultCount($filter);
    }

    protected function _getFilterWhere(array $filter = array())
    {
        return EstateSearch::getInstance()->_getFilterWhere($filter);
    }

    protected function _getQueryResult($select = '*', array $where, $unset = array(), $debug = false)
    {
        return EstateSearch::getInstance()->_getQueryResult($select, $where, $unset, $debug);
    }


}

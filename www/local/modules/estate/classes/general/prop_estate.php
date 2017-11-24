<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Estate as Estate;

Loc::loadMessages(__FILE__);

class CIBlockPropertyEstate
{
	const TABLE_PREFIX = 'b_hlbd_';

	protected static $tableRows = array();
	protected static $directoryMap = array();

	/**
	 * Возвращает описание пользовательского свойства
	 * @return array
	 */
	public static function GetUserTypeDescription()
	{
		return array(
			'PROPERTY_TYPE'             => 'S',
			'USER_TYPE'                 => 'estate',
			'DESCRIPTION'               => Loc::getMessage('ESTATE_PROP_DESCRIPTION'),
			'GetPropertyFieldHtml'      => array(__CLASS__, 'GetPropertyFieldHtml'),
			'GetPropertyFieldHtmlMulty' => array(__CLASS__, 'GetPropertyFieldHtmlMulty'),
			'GetAdminListViewHTML'      => array(__CLASS__, 'GetAdminListViewHTML'),
			'GetPublicViewHTML'         => array(__CLASS__, 'GetPublicViewHTML'),
			'GetSettingsHTML'           => array(__CLASS__, 'GetSettingsHTML'),
			'PrepareSettings'           => array(__CLASS__, 'PrepareSettings'),
			'GetAdminFilterHTML'        => array(__CLASS__, 'GetAdminFilterHTML'),
		);
	}

	/**
	 * Возвращает настройки cвойства
	 * @param array $arProperty Значения полей метаданных свойства
	 * @return array
	 */
	public static function PrepareSettings($arProperty)
	{
		$ret = array(
			'size'       => 1,
			'width'      => 0,
			'multiple'   => 'N',
			'group'      => 'N',
			'TABLE_NAME' => '',
		);

		if (empty($arProperty["USER_TYPE_SETTINGS"]) || !is_array($arProperty["USER_TYPE_SETTINGS"])) {
			return $ret;
		}

		if (isset($arProperty["USER_TYPE_SETTINGS"]["size"])) {
			$ret['size'] = (int) $arProperty["USER_TYPE_SETTINGS"]["size"];
			if ($ret['size'] <= 0) {
				$ret['size'] = 1;
			}
		}

		if (isset($arProperty["USER_TYPE_SETTINGS"]["width"])) {
			$ret['width'] = (int) $arProperty["USER_TYPE_SETTINGS"]["width"];
			if ($ret['width'] < 0) {
				$ret['width'] = 0;
			}
		}

		if (isset($arProperty["USER_TYPE_SETTINGS"]["group"]) && $arProperty["USER_TYPE_SETTINGS"]["group"] === "Y") {
			$ret['group'] = "Y";
		}

		if (isset($arProperty["USER_TYPE_SETTINGS"]["multiple"]) && $arProperty["USER_TYPE_SETTINGS"]["multiple"] === "Y") {
			$ret['multiple'] = "Y";
		}

		if (isset($arProperty["USER_TYPE_SETTINGS"]["TABLE_NAME"])) {
			$ret['TABLE_NAME'] = (string)$arProperty["USER_TYPE_SETTINGS"]['TABLE_NAME'];
		}
		return $ret;
	}

	/**
	 * Возвращает HTML настроек для формы редактирования инфоблока
	 * @param array $arProperty Метаданные свойства
	 * @param array $strHTMLControlName	Имя элемента управления
	 * @param array $arPropertyFields Пустой массив для флагов управления формой.
	 * @return string
	 */
	public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
	{
		$iblockID = 0;
		if (isset($arProperty['IBLOCK_ID'])) {
			$iblockID = (int) $arProperty['IBLOCK_ID'];
		}
		CJSCore::Init(array('translit'));
		$settings = self::PrepareSettings($arProperty);
		$arPropertyFields = array(
			"HIDE" => array("ROW_COUNT", "COL_COUNT", "MULTIPLE_CNT", "DEFAULT_VALUE", "WITH_DESCRIPTION",
				"SMART_FILTER", "DISPLAY_TYPE", "DISPLAY_EXPANDED", "FILTER_HINT"),
		);

		$modules = Estate\EstateTable::getList(array(
			'select' => array('TABLE_NAME', 'NAME')
		));

		$options = '';
		while($data = $modules->fetch()) {
			$selected = ($settings["TABLE_NAME"] == $data['TABLE_NAME']) ? ' selected' : '';
			$options .= '<option ' . $selected . ' value="' . htmlspecialcharsbx($data["TABLE_NAME"]) . '">'
				      . htmlspecialcharsex($data["NAME"] . ' (' . $data["TABLE_NAME"] . ')')
				      . '</option>';
		}

		$tableNameTitle = Loc::getMessage("ESTATE_TABLE_SELECT_NAME");
		$columnNameField = Loc::getMessage("ESTATE_COLUMN_SELECT_NAME");

		$html = <<<"HTML"
	<tr>
		<td>{$tableNameTitle}</td>
		<td>
			<select name="{$strHTMLControlName["NAME"]}[TABLE_NAME]" id="estate_prop_table" onchange="loadColumns();">
				{$options}
			</select>
		</td>
	</tr>
HTML;
	/*<tr>
		<td>{$columnNameField}</td>
		<td>
			<select name="{$strHTMLControlName["NAME"]}[COLUMN_NAME]" id="estate_prop_clumn">
				<option value="0">Выберите столбец</option>
			</select>
		</td>
	</tr>
	<script>
		var table  = BX('estate_prop_table');
		var column = BX('estate_prop_clumn');
		var defOption = column.innerHTML;
		function loadColumns() {
			column.innerHTML = defOption;
			var tableName = table.value;
			BX.ajax.loadJSON('/bitrix/admin/estate_property_ajax.php', {
				sessid : BX.bitrix_sessid(),
				table  : tableName
			},
			BX.delegate(function(data) {
				if (!data.ret) {
					alert(data.message);
					return;
				}
				var options = '';
				for (var i in data.fields) {
					var field = data.fields[i];
					options += '<option value="' + field + '">' + field + '</option>';
				}
				column.innerHTML = defOption + options;
			}));
		}
		loadColumns();
	</script>*/
		return $html;
	}

	/**
	 * Возвращает HTML выбора значения в инфоблоке
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Текущее значение
	 * @param array $strHTMLControlName	Имена элементов управления
	 * @return string
	 */
	public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
		$settings = self::PrepareSettings($arProperty);
		$size     = $settings["size"] > 1 ? ' size="' . $settings["size"] . '"' : '';
		$width    = $settings["width"] > 0 ? ' style="width:' . $settings["width"] . 'px"' : '';

		$options = self::_getOptionsHtml($arProperty, array($value["VALUE"]));
		return '<select name="' . $strHTMLControlName["VALUE"] . '"' . $size . $width . '>' . $options . '</select>';
	}

	/**
	 * Возвращает HTML выбора множественного значения в инфоблоке
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Текущее значение
	 * @param array $strHTMLControlName	Имена элементов управления
	 * @return string
	 */
	public static function GetPropertyFieldHtmlMulty($arProperty, $value, $strHTMLControlName)
	{
		$values = array_map(function($val) {
			return $val['VALUE'];
		}, $value);

		$settings = self::PrepareSettings($arProperty);
		$size     = $settings["size"] > 1 ? ' size="' . $settings["size"] . '"' : '';
		$width    = $settings["width"] > 0
					? ' style="width:' . $settings["width"] . 'px"'
					: ' style="margin-bottom:3px"';


		if ($settings["multiple"] === 'Y') {
			$options = self::_getOptionsHtml($arProperty, $values);
			$name = $strHTMLControlName["VALUE"] . '[]';
			return '<select multiple name="' . $name . '"' . $size . $width . '>' . $options . '</select>';
		}

		$values[] = false;
		$name = 'tb' . $strHTMLControlName["VALUE"] . "VALUE";
		$html = '<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%" id="'. $name .'">';
		foreach ($values as $valueId => $value) {
			$options = self::_getOptionsHtml($arProperty, array($value), $value === false ? $values : array());
			$html .= '<tr><td>'
			       . '<select name="' . $strHTMLControlName["VALUE"] . '[' . $valueId . '][VALUE]"' . $size . $width . '>'
				   . $options
			       . '</select>'
			       . '</td></tr>';
		}
		$html .= '</table>'
		       . '<input type="button" value="' . Loc::getMessage("ESTATE_MORE_BUTTON") . '" '
		       . 'onclick="if(window.addNewRow){addNewRow(\''. $name .'\', -1)}else{addNewTableRow(\''.md5($name).'\', 1, /\[(n)([0-9]*)\]/g, 2)}">';
		return $html;
	}

	/**
	 * Возвращает html-значение для вывода в таблице записей инфоблока
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Текущее значение
	 * @param array $strHTMLControlName	Имена элементов управления
	 * @return string
	 */
	public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
	{
		$row = self::_getModuleRow($arProperty, $value);
		if (!$row) {
			return '';
		}

		return htmlspecialcharsbx($row['NAME']);
	}

	/**
	 * Возвращает отформатированное значение для вывода в публичной части
	 * Значение можно получить вызвав метод CIBlockFormatProperties::GetDisplayValue
	 * @param array $arProperty Метаданные свойства
	 * @param array $value Текущее значение
	 * @param array $strHTMLControlName	Имена элементов управления
	 * @return string
	 */
	public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
	{
		$row = self::_getModuleRow($arProperty, $value);
		if (!$row) {
			return '';
		}

		return htmlspecialcharsbx($row['NAME']);
	}

	/**
	 * Возвращает html для вывода фильтра по полю
	 * @param array $arProperty Метаданные свойства
	 * @param array $strHTMLControlName	Имена элементов управления
	 * @return string
	 */
	public static function GetAdminFilterHTML($arProperty, $strHTMLControlName)
	{
		$lAdmin = new CAdminList($strHTMLControlName["TABLE_ID"]);
		$lAdmin->InitFilter(array($strHTMLControlName["VALUE"]));
		$filterValue = $GLOBALS[$strHTMLControlName["VALUE"]];

		$values = isset($filterValue) && is_array($filterValue) ? $filterValue : array();

		$settings = self::PrepareSettings($arProperty);
		$size = ($settings["size"] > 1 ? ' size="'.$settings["size"].'"' : '');
		$width = ($settings["width"] > 0 ? ' style="width:'.$settings["width"].'px"' : '');

		$options = self::_getOptionsHtml($arProperty, $values);
		$name = $strHTMLControlName["VALUE"] . '[]';
		return '<select name="' . $name . '"' . $size . $width . ' multiple>' . $options . '</select>';
	}

	/**
	 * Возвращает html список опций с записями модуля
	 * @param array $arProperty Метаданные свойства
	 * @param array $values Выбранные значения
	 * @param array $exclude Значения, которые нужно исключить из списка
	 * @return string
	 */
	protected static function _getOptionsHtml($arProperty, $values, $exclude = array())
	{
		$emptyOption = '<option value="" selected>' . Loc::getMessage('ESTATE_NO_MODULE_VALUE') . '</option>';
		if (empty($arProperty["USER_TYPE_SETTINGS"]["TABLE_NAME"])) {
			return $emptyOption;
		}

		$tableName = $arProperty["USER_TYPE_SETTINGS"]["TABLE_NAME"];
		if (empty(self::$tableRows[$tableName]) && self::_getRowsFromTable($tableName) === false) {
			return $emptyOption;
		}

		$options = '<option value="">' . Loc::getMessage('ESTATE_EMPTY_VALUE') . '</option>';
		foreach(self::$tableRows[$tableName] as $ID => $data) {
			if ($exclude && in_array($ID, $exclude)) {
				continue;
			}
			$selected = in_array($ID, $values) ? ' selected' : '';
			$options .= '<option' . $selected . ' value="' . $ID . '">'
					  . htmlspecialcharsex($data["NAME"]) . ' [' . $ID . ']</option>';
		}

		return $options;
	}

	/**
	 * Достает записи из таблицы модуля недвижимости
	 * @param string $tableName Название таблицы
	 * @return bool
	 */
	protected static function _getRowsFromTable($tableName)
	{
		$entity = Estate\EstateTable::getRow(array(
			'filter' => array(
				'TABLE_NAME' => $tableName
			),
		));
		if (!$entity) {
			return false;
		}

		$className = 'Bitrix\\Estate\\' . $entity['CLASS_NAME'];
		self::$tableRows[$tableName] = $className::getAssoc(array(), 'ID');

		return true;
	}

	/**
	 * Возвращает запись модуля недвижимости
	 * @param array $arProperty	Метаданные свойства
	 * @param array $value Значение
	 * @return false || array
	 */
	protected static function _getModuleRow($arProperty, $value)
	{
		if (!isset($value['VALUE']) || empty($arProperty['USER_TYPE_SETTINGS']['TABLE_NAME'])) {
			return false;
		}

		$tableName = $arProperty['USER_TYPE_SETTINGS']['TABLE_NAME'];
		if (empty(self::$tableRows[$tableName]) && self::_getRowsFromTable($tableName) === false) {
			return false;
		}

		if (isset(self::$tableRows[$tableName][$value['VALUE']])) {
			return self::$tableRows[$tableName][$value['VALUE']];
		}
		return false;
	}
}

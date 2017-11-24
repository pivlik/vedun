<?php
/**
 * Bitrix Framework
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\DB\MssqlConnection;
use Bitrix\Main\DB\OracleConnection;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

/**
 * Class description
 * @package    estate
 * @subpackage estate
 */
class EstateTable extends Entity\DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'b_estate_entity';
	}

	public static function getMap()
	{
		IncludeModuleLangFile(__FILE__);

		$fieldsMap = array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			),
			'NAME' => array(
				'data_type' => 'string',
				'required' => true,
			),
			'CLASS_NAME' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateName'),
			),
			'TABLE_NAME' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateTableName'),
			),
			'GROUP_NAME' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'SORT' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'FIELDS_COUNT' => array(
				'data_type' => 'integer',
				'expression' => array(
					'(SELECT COUNT(ID) FROM b_user_field WHERE b_user_field.ENTITY_ID = '.$GLOBALS['DB']->concat("'ESTATE_'", 'CAST(%s as char)').')', 'ID'
				),
			),
		);

		return $fieldsMap;
	}

	public static function add($data)
	{
		return false;
	}

	public static function delete($primary)
	{
		return false;
	}

	public static function OnBeforeUserTypeAdd($field)
	{
		if (preg_match('/^ESTATE_(\d+)$/', $field['ENTITY_ID'], $matches))
		{
			// validate usertype, it should be only from bx pack for now
			$utnames = array(
				'employee', 'crm_status', 'crm', 'video', 'string', 'integer', 'double', 'datetime', 'webdav_element',
				'boolean', 'file', 'enumeration', 'iblock_section', 'iblock_element', 'string_formatted', 'vote',
				'webdav_element_history'
			);

			if (!in_array($field['USER_TYPE_ID'], $utnames, true))
			{
				$GLOBALS['APPLICATION']->throwException(sprintf(
					'Selected type "%s" hasn\'t been supported yet.', $field['USER_TYPE_ID']
				));

				return false;
			}

			if ($field['MULTIPLE'] == 'Y')
			{
				$GLOBALS['APPLICATION']->throwException('Multiple fields for Estate hasn\'t been supported yet.');

				return false;
			}

			// get entity info
			$estate_id = $matches[1];
			$entity = EstateTable::getById($estate_id)->fetch();

			if (empty($entity))
			{
				$GLOBALS['APPLICATION']->throwException(sprintf(
					'Entity "ESTATE_%s" wasn\'t found.', $estate_id
				));

				return false;
			}

			// get usertype info
			$uf_type_info = $GLOBALS['USER_FIELD_MANAGER']->getUserType($field['USER_TYPE_ID']);
			$uf_type_object = new $uf_type_info['CLASS_NAME'];
			$sql_column = $uf_type_object->getDBColumnType(null);

			// create field in db
			$connection = Application::getConnection();
			$sqlHelper = $connection->getSqlHelper();

			$connection->query('ALTER TABLE '.$sqlHelper->quote($entity['TABLE_NAME']).' ADD '.$sqlHelper->quote($field['FIELD_NAME']).' '.$sql_column);
		}

		return true;
	}

	public static function OnBeforeUserTypeDelete($field)
	{
		if (preg_match('/^ESTATE_(\d+)$/', $field['ENTITY_ID'], $matches))
		{
			// get entity info
			$estate_id = $matches[1];
			$entity = EstateTable::getById($estate_id)->fetch();

			if (empty($entity))
			{
				$GLOBALS['APPLICATION']->throwException(sprintf(
					'Entity "ESTATE_%s" wasn\'t found.', $estate_id
				));

				return false;
			}

			// drop db column
			$connection = Application::getConnection();
			$connection->dropColumn($entity['TABLE_NAME'], $field['FIELD_NAME']);
		}

		return true;
	}

	public static function validateName()
	{
		return array(
			new Entity\Validator\Unique,
			new Entity\Validator\Length(
				null,
				100,
				array('MAX' => GetMessage('HIGHLOADBLOCK_HIGHLOAD_BLOCK_ENTITY_NAME_FIELD_LENGTH_INVALID'))
			),
			new Entity\Validator\RegExp(
				'/^[A-Z][A-Za-z0-9]*$/',
				GetMessage('HIGHLOADBLOCK_HIGHLOAD_BLOCK_ENTITY_NAME_FIELD_REGEXP_INVALID')
			)
		);
	}

	public static function validateTableName()
	{
		return array(
			new Entity\Validator\Unique,
			new Entity\Validator\Length(
				null,
				64,
				array('MAX' => GetMessage('HIGHLOADBLOCK_HIGHLOAD_BLOCK_ENTITY_TABLE_NAME_FIELD_LENGTH_INVALID'))
			),
			new Entity\Validator\RegExp(
				'/^[a-z0-9_]+$/',
				GetMessage('HIGHLOADBLOCK_HIGHLOAD_BLOCK_ENTITY_TABLE_NAME_FIELD_REGEXP_INVALID')
			)
		);
	}
}

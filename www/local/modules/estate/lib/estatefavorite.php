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
 * @subpackage estatefavorite
 */
class EstateFavoriteTable extends BaseEstate
{
    public static $_instance = null;

    protected $_tableName = 'estate_favorite';

    protected $_favoriteFlats = array();

    protected $_fieldsMap = array(
        'ID' => array(
            'data_type'    => 'integer',
            'primary'      => true,
            'autocomplete' => true,
        ),
        'FLAT' => array(
            'data_type'    => 'string',
            'required'     => true,
        ),
        'UID' => array(
            'data_type'    => 'string',
            'required'     => true,
        ),
    );

    public function addFavoriteFlat($id)
    {
        $uid = $this->_getUserId();
        $row = $this->_getFavoriteRow($id, $uid);
        if ($row) {
            return;
        }
        self::add(array(
            'FLAT' => $id,
            'UID' => $uid,
        ));
    }

    public function removeFavoriteFlat($id)
    {
        $uid = $this->_getUserId();
        $row = $this->_getFavoriteRow($id, $uid);
        if (!$row) {
            return;
        }
        self::delete($row['ID']);
    }

    public function isFavoriteFlat($id)
    {
        $uid = $this->_getUserId();
        $row = $this->_getFavoriteRow($id, $uid);
        return !empty($row);
    }

    public function getFavoriteFlatsCount()
    {
        $uid = $this->_getUserId();
        $row = self::getRow(array(
            'select' => array(
                'cnt' => new ExpressionField('cnt', 'COUNT(*)'),
            ),
            'filter' => array('UID' => $uid),
        ));
        return $row['cnt'];
    }

    public function getFavoriteFlats()
    {
        if (empty($this->_favoriteFlats)) {
            $uid = $this->_getUserId();
            $result = self::getAssoc(
                array(
                    'filter' => array('UID' => $uid)
                ),
                'FLAT',
                'FLAT'
            );
            $this->_favoriteFlats = $result;
        }
        return $this->_favoriteFlats;
    }

    public function getPdfFileName()
    {
        $uid = $this->_getUserId();
        $uid = substr($uid, 0, 8);
        return 'favorite-' . $uid . '.pdf';
    }

    public function setMenuFavoriteLink()
    {
        $favoriteCnt = $this->getFavoriteFlatsCount();
        $GLOBALS['BX_MENU_CUSTOM']->AddItem('submenu', array(
           'TEXT' => 'Избранное&nbsp;— <span class="js-favorite-count">'
                   . $favoriteCnt . '</span>',
           'LINK' => BaseEstate::ESTATE_HOME_PATH . 'favorite/',
           'DEPTH_LEVEL' => 666,
           'IS_PARENT' => $favoriteCnt,
        ));
    }

    protected function _getFavoriteRow($id, $uid) {
        $row = self::getRow(array(
            'filter' => array('FLAT' => $id, 'UID' => $uid)
        ));
        return $row;
    }

    protected function _getUserId()
    {
        if (!isset($_COOKIE['uid'])) {
            $uid = md5(uniqid() . rand());
            setcookie('uid', $uid, 0, '/');
        } else {
            $uid = $_COOKIE['uid'];
        }
        return $uid;
    }
}

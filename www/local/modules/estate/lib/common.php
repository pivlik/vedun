<?php
/**
 * Bitrix Framework
 *
 * @package    bitrix
 * @subpackage estate
 */

namespace Bitrix\Estate;

/**
 * Class Common
 * Класс для вспомогательных функций квартирографии
 *
 * @package Bitrix\Estate
 */
class Common extends BaseEstate
{
    public static $_instance = null;

    /**
     * Возвращает массив с путем к изображению либо ссылкой на плейсхолдер
     * Ресайзит изображение по заданным размерам
     *
     * @param int $imageId ID изображения в битриксе
     * @param int $width   Ширина изображения или плейсхолдера
     * @param int $height  Высота изображения или плейсхолдера
     * @return array
     */
    public static function _getImageOrPlaceholder($imageId, $width, $height)
    {
        if (($image = \CFile::GetFileArray($imageId))
            && file_exists($_SERVER['DOCUMENT_ROOT'] . $image['SRC'])
        ) {
            $image = \CFile::ResizeImageGet(
                $image,
                array('width' => $width, 'height' => $height),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        } else {
            $image = array('src' => placehold($width, $height, 'no image'));
        }
        return $image['src'];
    }

    /**
     * Возвращает массив с путем к изображению либо ссылкой на плейсхолдер
     * Ресайзит изображение по заданным размерам
     *
     * @param $path   mixed $path Одно из значений: ID файла, абсолютный путь к файлу, URL к файлу лежащем на другом сайте.
     * @param $width  int $width Ширина изображения или плейсхолдера
     * @param $height int $height Высота изображения или плейсхолдера
     */

    public static function _resizeImageOrPlaceholder($path, $width, $height)
    {
        if (substr($path, 0, 2) == '//') {
            $path = 'http:' . $path;
        }

        $name = md5($width . $height . $path);
        $dir = substr($name, 0, 3);
        $cachePath = '/upload/resize_cache/estate/' . $dir . '/' . $name . '.jpg';
        $serverCachePath = $_SERVER['DOCUMENT_ROOT'] . $cachePath;

        if (file_exists($serverCachePath)) {
            return $cachePath;
        }

        if ($image = \CFile::MakeFileArray($path)) {
            if ($r = \CFile::ResizeImageFile(
                $image['tmp_name'],
                $imgPath = $serverCachePath,
                array('width' => $width, 'height' => $height),
                BX_RESIZE_IMAGE_PROPORTIONAL
            )
            ) {
                $image['src'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imgPath);
            }
        } else {
            $image = array('src' => placehold($width, $height, 'no image'));
        }

        return $image['src'];
    }

    /**
     * Возвращает массив с данными виджета планоплана
     *
     * @param $planoplanId string Id виджета планоплана
     * @param $cacheTime   int Время кеширования ответа, по умолчанию 1 неделя
     *
     */
    public static function getPlanoplanInfo($planoplanId, $cacheTime = 604800)
    {
        $obCache = new \CPHPCache();
        $cache_id = 'arPlanoplan' . $planoplanId;
        $cache_path = '/arPlanoplan/';
        if ($obCache->InitCache($cacheTime, $cache_id, $cache_path)) {
            $vars = $obCache->GetVars();
            $arPlanoplan = $vars['arPlanoplan'];
        } elseif ($obCache->StartDataCache()) {
            $str = file_get_contents(
                "http://widget.planoplan.com/data/?hash=" . $planoplanId . "&lang=&width=&height=&callback=CallbackRegistry.f_933182223383395"
            );

            $str = str_replace('CallbackRegistry.f_933182223383395(', '', $str);
            $str = substr($str, 0, -2);
            $arPlanoplan = json_decode($str, true);

            $obCache->EndDataCache(array('arPlanoplan' => $arPlanoplan));
        }
        return $arPlanoplan;
    }

}
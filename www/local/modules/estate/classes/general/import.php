<?php
namespace Kelnik;

abstract class FlatFields
{
    protected $fields = array(
        'PARENT',
        'IMPORT_ID',
        'NAME',
        'PRICE_TOTAL',
        'PRICE',
        'AREA_TOTAL',
        'AREA_LIVING',
        'AREA_KITCHEN',
        'AREA_BALKON',
        'AREA_ROOM_1',
        'AREA_ROOM_2',
        'ROOMS',
        'TYPE',
        'STATUS',
    );

    protected $fieldsDataType = array(
        'integer', 'string', 'float'
    );
    protected $fieldsType = array(
        'text'
    );

    function getFieldList()
    {
        $flatInstance = \Bitrix\Estate\EstateFlatTable::getInstance();
        $fieldMap = $flatInstance::getMap();
        $this->fields = array();
        foreach ($fieldMap as $key => $field) {
            if (in_array($field['field_type'], $this->fieldsType)) {
                $this->fields[] = $key;
            }
        }
    }

    protected $_statuses = array(
        'Свободно' => 1,
        'Не для продажи' => 3,
        'Продано' => 3,
    );

    protected $_requiredFields = array(
        'building', 'section', 'floor', 'number', 'status'
    );

}


abstract class ImportFile
{

    abstract function parse();

    protected function save($data)
    {
        return true;
    }

    public function run()
    {
        $data = $this->parse();
        $this->save($data);
    }

}



class XmlImport extends ImportFile
{
    private $fileName;
    private $LOG;

    protected $_nodeNames = array(
        'id_корпуса' => 'building',
        'id_очереди' => 'queue',
        'id_секции' => 'section',
        'id_этажа' => 'floor',
        'Номер' => 'number',
        'Статус' => 'status',
        'КоличествоКомнат' => 'rooms',
        'БазоваяЦена' => 'price_total',
        'ЦенаПри100ПроцентнойОплате' => 'price',
        'ОбщаяПлощадь' => 'area_total',
        'ЖилаяПлощадь' => 'area_living',
        'ПлощадьКухни' => 'area_kitchen',
        'ПлощадьЛоджии' => 'area_balkon',
        'ПлощадьКомнаты1' => 'area_room_1',
        'ПлощадьКомнаты2' => 'area_room_2',
        'Студия' => 'is_studio',
    );

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
        $this->LOG = new LogImport();
    }

    function parse()
    {
        if (!is_file($this->fileName)) {
            $this->LOG->addLog('Не найден файл импорта');
            return false;
        }
        if (!$xml = $this->_openXml($this->fileName)) {
            return false;
        }

        if (!$items = $this->_parseXml($this->fileName)) {
            return false;
        }

        return $items;
    }

    protected function _openXml($fileName)
    {
        libxml_use_internal_errors(true);

        if (!$xml = simplexml_load_file($fileName)) {
            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                $this->LOG->addLog($this->_xmlErrorToString($error));
            }
            libxml_clear_errors();
        }
        return $xml;
    }

    protected function _parseXml(SimpleXMLElement $xml)
    {
        if (empty($xml->Объект)) {
            $this->LOG->addLog('Не найден узел "Объект"');
            return false;
        }

        $items = array();
        foreach ($xml->Объект as $node) {
            $item = array();
            foreach ($node->Ссылка->Свойство as $param) {
                $this->_parseNode($item, $param);
            }
            foreach ($node->Свойство as $param) {
                $this->_parseNode($item, $param);
            }

            foreach ($this->_requiredFields as $name) {
                if (empty($item[$name])) {
                    $this->LOG->addLog('Пустое поле ' . $name . ' в узле нпп=' . $node['Нпп']);
                    continue;
                }
            }
            $items[] = $item;
        }
        return $items;
    }

    protected function _parseNode(&$item, $node)
    {
        $nodeName = (string)$node['Имя'];
        if (!isset($this->_nodeNames[$nodeName])) {
            return false;
        }
        $item[$this->_nodeNames[$nodeName]] = (string)$node->Значение;
    }

    protected function _xmlErrorToString($error)
    {
        $str = '';
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $str .= "Warning {$error->code}: ";
                break;
            case LIBXML_ERR_ERROR:
                $str .= "Error {$error->code}: ";
                break;
            case LIBXML_ERR_FATAL:
                $str .= "Fatal Error {$error->code}: ";
                break;
        }

        $str .= trim($error->message) . PHP_EOL
            . "Line: {$error->line}" . PHP_EOL
            . "Column: {$error->column}";

        return $str;
    }
}

class LogImport
{
    private $_logFile;
    private $handler;
    private $_log = array();

    function __construct($filePath = 'import.log')
    {
        if (file_exists($filePath) && is_writable($filePath)) {
            $this->_logFile = $filePath;
        } else {
            echo 'Не удалось открыть файл лога ' . $filePath . ' для записи';
            return;
        }
    }

    private function openLogFile()
    {
        if ($this->handler) {
            return true;
        }
        if (!@$this->handler = fopen($this->_logFile, 'a+')) {
            echo 'Не удалось открыть файл лога ' . $this->_logFile . ' для записи';
            return;
        }

        return true;
    }

    private function closeLogFile()
    {
        fclose($this->handler);
    }

    public function writeLog($log)
    {
        if ($this->openLogFile()) {
            if (!@fwrite($this->handler, $log)) {
                echo 'Не удалось произвести запись в файл ' . $this->_logFile;
            }
        }
        $this->closeLogFile();
    }

    public function readLog($limit = 50)
    {
        $res = '';
        if ($this->openLogFile()) {
            $i = 1;
            while (!feof($this->handler) || $limit < $i) {
                $res .= fgets($this->handler, 4096);
                $i++;
            }
        }
        $this->closeLogFile();
        return $res;
    }

    public function getLog()
    {
        return $this->_log;
    }

    public function addLog($msg)
    {
        $this->_log[] = $msg;
        return true;
    }

    public function saveLog()
    {
        if ($this->openLogFile()) {
            $log = PHP_EOL . '=====' . PHP_EOL . date('d.m.Y H:i:s') . PHP_EOL
                . implode(PHP_EOL, $this->_log)
                . PHP_EOL . PHP_EOL;

            $this->writeLog($log);
        }
    }
}





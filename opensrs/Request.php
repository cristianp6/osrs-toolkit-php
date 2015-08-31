<?php

namespace OpenSRS;
use Spyc;

require_once dirname(__FILE__).'/openSRS_config.php';

class Request
{
    /**
     * Process an OpenSRS Request
     * 
     * @param string $format input format (xml, json, array)
     * @param string $data data 
     * 
     * @return void
     */
    public function process($format = '', $data = '')
    {
        if (empty($data)) {
            // trigger_error('OSRS Error - No data found.');
            throw new Exception('OSRS Error - No data found.');
            return;
        } 
        
        $dataArray = array();
        switch (strtolower($format)) {
            case 'array':
                $dataArray = $data;
                break;
            case 'json':
                $json = str_replace('\\"', '"', $data);   //  Replace  \"  with " for JSON that comes from Javascript
                $dataArray = json_decode($json, true);
                break;
            case 'yaml':
                $dataArray = Spyc::YAMLLoad($data);
                break;
            default:
                $dataArray = $data;
        }
        // Convert associative array to object
        $dataObject = $this->array2object($dataArray);
        $classCall = null;

        $classCall = RequestFactory::build($dataObject->func, $format, $dataObject);
        return $classCall;
    }

    /**
    * Method to convert Array -> Object -> Array.
    *
    * @param hash $data Containing array object
    * 
    * @return stdClass Object $object   Containing stdClass object
    *
    * @since    3.4
    */
    public function array2object($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        $object = new \stdClass();

        foreach ($data as $name => $value) {
            if (isset($name)) {
                $name = strtolower(trim($name));
                $object->$name = $this->array2object($value);
            }
        }

        return $object;
    }

    public function convertArray2Formatted($type = '', $data = '')
    {
        $resultString = '';
        if ($type == 'json') {
            $resultString = json_encode($data);
        }
        if ($type == 'yaml') {
            $resultString = Spyc::YAMLDump($data);
        }

        return $resultString;
    }

    public function convertFormatted2array($type = '', $data = '')
    {
        $resultArray = '';
        if ($type == 'json') {
            $resultArray = json_decode($data, true);
        }
        if ($type == 'yaml') {
            $resultArray = Spyc::YAMLLoad($data);
        }

        return $resultArray;
    }

    public function array_filter_recursive($input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->array_filter_recursive($value);
            }
        }

        return array_filter($input);
    }
}

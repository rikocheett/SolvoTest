<?php

class ExtAbstract
{

    //this function parse ine file
    protected function parseFile($file, $dir){

    }




//parse ext from file name
    protected function getExtension($file) {
        return substr($file, strrpos($file, '.') + 1);
    }

//parse TR~1Z0000020300000002 to
//array ('TR' => '1Z0000020300000002')
    protected function getData($data_str_value){
        $arr_data = explode("~", $data_str_value);
        return [$arr_data[0], $arr_data[1]];
    }
}
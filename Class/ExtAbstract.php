<?php

Abstract class ExtAbstract
{

    //this function parse ine file
    abstract public function parseFile($file, $dir);



    //parse ext from file name
    protected function getExtension($file) {
        return substr($file, strrpos($file, '.') + 1);
    }
}
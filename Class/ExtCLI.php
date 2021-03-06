<?php

class ExtCLI extends ExtAbstract
{
    //parsing algorithm for CLI ext
    public function parseFile($file, $dir){
        $arr_res = [];
        $ext = $this->getExtension($file);
        if($ext == "CLI") {
            $data_file = file($dir . '\\' . $file);
            $data_file = str_replace(' ', '', $data_file);

            foreach ($data_file as $str) {

                $data_str = explode('*', $str);
                $data_str = str_replace(PHP_EOL, '', $data_str);

                if ($data_str[0] == "H") $SH = $this->getData($data_str[1]);

                else if ($data_str[0] == "D") {

                    $result = [];
                    $result[$SH[0]] = $SH[1];

                    for ($i = 1; $i < count($data_str); $i++) {

                        $value = $this->getData($data_str[$i]);
                        $result[$value[0]] = $value[1];

                    }

                    $arr_res[] = $result;
                }
            }
            return $arr_res;
        }
        else return false;
    }

    //parse TR~1Z0000020300000002 to
    //array ('TR' => '1Z0000020300000002')
    private function getData($data_str_value){
        $arr_data = explode("~", $data_str_value);
        return [$arr_data[0], $arr_data[1]];
    }
}
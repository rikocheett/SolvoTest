<?php

class Parser
{
    protected $dir;
    protected $arr_files = array();

    public function __construct($dir){
        $this->dir = $dir;
        $this->getFiles($this->dir);
    }

    //initiates the parser
    public function doParse(){
        foreach ($this->arr_files as $file){
            $extParser = 0;
            $ext = $this->getExtension($file);
            echo $file . ": ";
            switch ($ext) {
                case "CLI":
                    $extParser = new ExtCLI();
                    break;
                default: echo ': extension not supported' . PHP_EOL;
            }
            if(!empty($extParser)){
                $arr_result = $extParser->parseFile($file, $this->dir);
                $query = $this->generateQuery($arr_result);
                echo PHP_EOL . $query;
            }
        }
    }

    //get files array from directory
    protected function getFiles($dir){

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (!is_dir($file)) array_push($this->arr_files, $file);
                }
                closedir($dh);
            }
            else{
                echo "can't open the directory." .PHP_EOL;
            }
        }
        else {
            echo "directory doesn't exist." .PHP_EOL;
        }
    }

    // generate SQL query
    private function generateQuery($arr_result){

        $arr_result = $this->prepareArray($arr_result);

        $query = "INSERT shipments ('tracking_number', 'shipper_id', 'ship_date', 'delivery_date', 'status')" . PHP_EOL . "VALUES ";

        foreach($arr_result as $string) $query .= "('{$string['TR']}', '{$string['SH']}', '{$string['PD']}', '{$string['DD']}', '{$string['ST']}')," . PHP_EOL;

        return $query;
    }

    //this function prepare data from result array for generate sql query
    private function prepareArray($arr_result) {
        $keys = ['TR', 'SH', 'PD', 'DD', 'ST'];

        foreach ($arr_result as $result) {

            foreach ($keys as $key) {

                if(array_key_exists($key, $result)){

                    if($key == 'PD' || $key == 'DD'){
                        $result[$key] = date('Y-m-d', strtotime($result[$key]));
                    }

                    $ready_arr[$key] = $result[$key];
                }
            }

            $ready_arr['ST'] = 'NEW';
            $ready_arr_result[] = $ready_arr;
        }

        return $ready_arr_result;
    }

    //parse ext from file name
    private function getExtension($file) {
        return substr($file, strrpos($file, '.') + 1);
    }
}
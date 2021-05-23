<?php

//accept path to the directory with the data files
echo "Enter relative path to the directory with the data files:" .PHP_EOL;
echo __DIR__ . "\\";
$dir = (string)readline();
$arr_files = [];

//get files array from directory
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {

        while (($file = readdir($dh)) !== false) {
            if (!is_dir($file)) array_push($arr_files, $file);
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

//parse files
foreach ($arr_files as $file){

    if($arr_result = parseFile($file, $dir)) {
        $query = generateQuery($arr_result);
        echo $file . ": " . PHP_EOL . $query;
    }
    else echo $file . ': extension not supported' . PHP_EOL;
}

//this function generate sql query, based on result array
function generateQuery($arr_result){

    $arr_result = prepareArray($arr_result);

    $query = "INSERT shipments ('tracking_number', 'shipper_id', 'ship_date', 'delivery_date', 'status')" . PHP_EOL . "VALUES ";

    foreach($arr_result as $string) $query .= "('{$string['TR']}', '{$string['SH']}', '{$string['PD']}', '{$string['DD']}', '{$string['ST']}')," . PHP_EOL;

    return $query;
}

//this function prepare data from result array for generate sql query
function prepareArray($arr_result) {
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

//this function parse ine file
function parseFile($file, $dir){

    $arr_res = [];
    $ext = getExtension($file);

    //for add more ext you just need to add new case in the switch-case construction
    switch ($ext){
        case "CLI":

            $data_file = file($dir . '\\' . $file);
            $data_file = str_replace(' ', '', $data_file);

            foreach($data_file as $str){

                $data_str = explode('*', $str);
                $data_str = str_replace(PHP_EOL, '', $data_str);

                if($data_str[0] == "H") $SH = getData($data_str[1]);

                else if ($data_str[0] == "D") {

                    $result = [];
                    $result[$SH[0]] = $SH[1];

                    for ($i = 1; $i < count($data_str); $i++){

                        $value = getData($data_str[$i]);
                        $result[$value[0]] = $value[1];

                    }

                    $arr_res[] = $result;
                }
            }
            break;

        default: return 0;

    }
    return $arr_res;
}

//parse ext from file name
function getExtension($file) {
    return substr($file, strrpos($file, '.') + 1);
}

//parse TR~1Z0000020300000002 to
//array ('TR' => '1Z0000020300000002')
function getData($data_str_value){
    $arr_data = explode("~", $data_str_value);
    return [$arr_data[0], $arr_data[1]];
}
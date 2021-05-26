<?php
include 'Class/Parse.php';
include 'Class/ExtAbstract.php';
include 'Class/ExtCLI.php';


//accept path to the directory with the data files
echo "Enter relative path to the directory with the data files:" .PHP_EOL;
echo __DIR__ . "\\";
$dir = (string)readline();

$parser = new Parse($dir);
echo $parser->doParse();
//$parser->doParse();

<?php

//include_once 'config.php';
include_once 'app/config/config.php';


$tweets_controller = new controller\tweet\Tweet;
//$years = array(2014,2015,2016,2017,2018,2019,2020);
$years = array(2014);
foreach($years as $year){
    echo "START Working on ".$year."<br>";
    echo $tweets_controller->importTweetsToDbByYear($year);
    echo "END ".$year."<br><br>";
    sleep(2);
}

?>
<?php

include_once '../app/config/config.php';

if ( isset($_REQUEST["action"]) && !empty($_REQUEST["action"]) && is_numeric($_REQUEST["year"]) ) 
{
    $tweets_view = new view\tweet\Tweet;
    $tweets_view2 = new view\tweet\Tweet;
    $tweets_view3 = new view\tweet\Tweet;

    $tweets_view->showHeader($_REQUEST["action"]); //Trait

    switch ($_REQUEST["action"]) 
    {
        case "hashtag":  
            $apiResponse = $tweets_view->showYearlyStatsTweetsByHashTag('overtime', $_REQUEST["year"]);
        break;

        case "gettweet":      
            $apiResponse = $tweets_view->showTweet(12);
        break;
        
        default:
            $apiResponse =  json_encode(array());

    }

    echo $apiResponse;

}

?>
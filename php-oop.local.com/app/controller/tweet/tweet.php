<?php
namespace controller\tweet;
use model\tweet\Tweet as TweetModel;

class Tweet extends TweetModel{

    public function importTweetsToDbByYear($year){

        if($year>=2014 && $year<=2020){
            $json = file_get_contents('http://trumptwitterarchive.com/data/realdonaldtrump/'.$year.'.json');
            $tweets_data = json_decode($json);
            foreach($tweets_data AS $tweet){
                $result = $this->setTweets( (array)$tweet );
            }
        }
        
    }

}

?>
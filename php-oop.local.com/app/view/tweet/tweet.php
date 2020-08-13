<?php 
namespace view\tweet;
use model\tweet\Tweet as TweetModel;

trait AppHeader {
    public function showHeader($headerTitle){
        echo "<h1>$headerTitle</h1>";
    }
}

class Tweet extends TweetModel{

    use AppHeader;
    
    public function showYearlyStatsTweetsByHashTag($hashtag, $year){

        $response = $this->getYearlyStatsTweetsByHashTag($hashtag, $year);
        foreach( $response AS $row ){
            $result = json_encode(array($row['Jan'],$row['Feb'],$row['Mar'],$row['Apr'],$row['May'],$row['Jun'],$row['Jul'],$row['Aug'],$row['Sep'],$row['Oct'],$row['Nov'],$row['December']));
        }
        
        return $result;

    }

    public function showTweet($id){

        $response = $this->getTweet($id);
        foreach( $response AS $row ){
            $result = json_encode(array($row['id'],$row['text']));
        }
        
        return $result;

    }

    
}

?>
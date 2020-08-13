<?php 
namespace model\tweet;
use model\dbh\Dbh;

class Tweet {

    public $db = "";

    public function __construct() {
        $this->db = Dbh::getInstance();
    }

    protected function getYearlyStatsTweetsByHashTag($hashtag ='', $year = 1000){

        global $months;
        $build_qb = "";
        $counter = 0;
        $total_m = count($months);
        $sql_data = array();
        foreach($months AS $current_month){
            $counter++;
            $var1 = "var".$counter."1";
            $var2 = "var".$counter."2";
            if($total_m !=  $counter){
                $build_qb .= " CAST( SUM(IF(t.text LIKE :$var1 AND t.created_at LIKE :$var2 ,1,0)) AS SIGNED) AS $current_month";
                $build_qb .= ", ";
            }else{
                $build_qb .= " CAST( SUM(IF(t.text LIKE :$var1 AND t.created_at LIKE :$var2 ,1,0)) AS SIGNED) AS $current_month"."ember";
            }
            $sql_data[$var1] = "%$hashtag%";
            $sql_data[$var2] = "%$current_month%";
        }
        $sql_data["_year"] = "%$year%";
        $sql = "SELECT $build_qb FROM tweets as t WHERE t.created_at LIKE :_year ORDER BY id ASC";
        $result = $this->db->bindQuery($sql, $sql_data);
    
        return $result;

    }

    protected function getTweet($id){
        
        $sql = "SELECT * FROM tweets WHERE id=?";
        $result = $this->db->query($sql,array($id));
        return $result;

    }

    protected function setTweets ($data = array()){

        $sql = "INSERT INTO tweets(source, id_str, text, created_at, retweet_count, in_reply_to_user_id_str, favorite_count, is_retweet) VALUES (?,?,?,?,?,?,?,?)";
        $bool_string = ( $data["is_retweet"] )? "TRUE":"FALSE";
        $result = $this->db->query($sql,array(
            $data["source"],
            $data["id_str"],
            $data["text"],
            $data["created_at"],
            $data["retweet_count"],
            $data["in_reply_to_user_id_str"],
            $data["favorite_count"],
            $bool_string));
            
        return $result;

    }

}

?>
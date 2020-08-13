<?php
namespace model\dbh;
use \PDO;

class Dbh{

    private static $instance = null;
    private $connection = null;
    
    public function __construct() {

        try{
            $dsn = "mysql:host=".DB_SERVERNAME.";dbname=".DB_NAME.";charset=".DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }catch (PDOException $e){
            if(DB_DEBUG)
            throw new Exception("APP - Connection failed:'".$e->getMessage());
        }

    }

    public static function getInstance(){

        if (!self::$instance) {
            self::$instance = new Dbh();
            if(DB_DEBUG)
            @file_put_contents(APP_LOG_PATH."dbh.log", '[' . date('Y-m-d H:i:s A') . ']-[Dbh getInstance]'.PHP_EOL, FILE_APPEND);
        }

        return self::$instance;

    }

    //Allows the use of the ? symbol
    public function query($query, $args = array()){
    
        try{
            $stmt = $this->connection->prepare($query);
            if( is_array($args) && count($args)>0 ){
                $stmt->execute($args);
            }else{
                $stmt->execute();
            }
            $lower_query = strtolower($query);
            if (strpos($lower_query, 'insert') !== false) {
                $result = $this->connection->lastInsertId();
            }else{
                $result = $stmt->fetchAll();
            }
        }catch (Exception $e){
            if(DB_BACKTRACE)
            print_r(debug_backtrace());
            if(DB_DEBUG)
            @file_put_contents(APP_LOG_PATH."dbh.log", '[' . date('Y-m-d H:i:s A') . ']-' . '[' . $_SERVER['REMOTE_ADDR'] . ']-' . '[' . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'] . ']-[query]-' . $e->getMessage().PHP_EOL, FILE_APPEND);
        }

        return $result;

    }

    //Allows the use of the symbo.  Example :var  - Used with LIKE %%
    public function bindQuery($query, $args = array()){

        try{
            $stmt = $this->connection->prepare($query);
            if( is_array($args) && count($args)>0 ){
                foreach($args AS $key => $value)
                {
                    $param = FALSE;
                    if(is_int($value))
                        $param = PDO::PARAM_INT;
                    elseif(is_bool($value))
                        $param = PDO::PARAM_BOOL;
                    elseif(is_null($value))
                        $param = PDO::PARAM_NULL;
                    elseif(is_string($value))
                        $param = PDO::PARAM_STR;

                    if($param){
                        $stmt->bindValue(":$key",$value,$param);
                    }else{
                        $stmt->bindValue(":$key",$value);
                    }
                }
            }
            $stmt->execute();
            $lower_query = strtolower($query);
            if (strpos($lower_query, 'insert') !== false) {
                $result = $this->connection->lastInsertId();
            }else{
                $result = $stmt->fetchAll();
            }

        }catch (Exception $e){
            if(DB_BACKTRACE)
            print_r(debug_backtrace());
            if(DB_DEBUG)
            @file_put_contents(APP_LOG_PATH."dbh.log", '[' . date('Y-m-d H:i:s A') . ']-' . '[' . $_SERVER['REMOTE_ADDR'] . ']-' . '[' . $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'] . ']-[bindQuery]-' . $e->getMessage().PHP_EOL, FILE_APPEND);
        }

        return $result;

    }

    public function disconnect() {
        $this->connection = null;
    }

}

?>
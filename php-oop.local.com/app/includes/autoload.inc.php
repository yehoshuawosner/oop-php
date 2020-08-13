<?php
//NAMESPACES Autoload
spl_autoload_register('classAutoLoader');
function classAutoLoader($className){
    
    $error_name = $className;
    $path = APP_PATH;
    $className = str_replace('\\','/',strtolower($className)); 
    $extension = '.php';
    $fileName = $path . $className . $extension;
    if(!file_exists($fileName)){
        throw new Exception("Class Name:'".$error_name." NOT EXIST ".$fileName);
        return false;
    }
    include_once $fileName;
}

?>
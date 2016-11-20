<?php

session_start();
include_once '../include/functions.php';

define("MAP_PROTECT", "bloquear el acceso directo al mapa");
$autorizado = (int)_isset($_SESSION, "map-authorized", 0);

if(!$autorizado){
    include '../include/login.php';
}else{
    if(isValid()){
        include '../include/map.php';
    }else{
        header("Location: ./logout.php");
    }
    
}

?>

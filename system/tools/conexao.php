<?php
$database = [
    "conn"=>"mysql:host=".DB_HOST.";dbname=".DB_NAME
    ,"user"=>DB_USER
    ,"password"=>DB_PASS
];

function setDbConect($database,$driver="pdo"){
    switch($driver){
        case "pdo" : dbconection::initPDO($database); break; 
        case "mysqli" : dbconection::initMySql($database); break; 
        case "postgresql" : dbconection::initPG($database); break; 
    }
}

setDbConect($database);
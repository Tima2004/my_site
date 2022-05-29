<?php
ob_start();
session_start();

date_default_timezone_set("Europe/London");

try{
    $conn = new PDO("mysql:dbname=mseet_31815218_mysite;host=sql309.hstn.me", "mseet_31815218", "Gamerpro100");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(PDOException $e){
    exit("Ошибка при плодключении: " . $e->getMessage());
}

?>
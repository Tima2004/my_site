<?php
require_once("../includes/config.php");

if (isset($_POST["videoId"]) && isset($_POST["username"])){
   $query = $conn->prepare("SELECT * FROM videoprogress WHERE username=:username AND videoId=:videoId");

   $query->bindValue(":username", $_POST["username"]);
   $query->bindValue(":videoId", $_POST["videoId"]);
   $query->execute();

   if ($query->rowCount() == 0){
    $query = $conn->prepare("INSERT INTO videoprogress (username, videoId) VALUES(:username, :videoId)");

    $query->bindValue(":username", $_POST["username"]);
    $query->bindValue(":videoId", $_POST["videoId"]);
    $query->execute();
   }

}else{
    echo "Ошибка. Нет аргуметов.";
}
?>
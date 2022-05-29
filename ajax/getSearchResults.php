<?php
require_once("../includes/config.php");
require_once("../classes/SearchResultProvider.php");
require_once("../classes/EntityProvider.php");
require_once("../classes/Entity.php");
require_once("../classes/PreviewProvider.php");

if (isset($_POST["value"])){
   $srp = new SearchResultProvider($conn);
   echo $srp->getResults($_POST["value"]);

}else{
    echo "Ошибка. Нет аргуметов.";
}
?>
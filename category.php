<?php
    require_once("includes/header.php");
    
    if (!isset($_GET['id'])){
        ErrorMessage::show("Нет ID");
    }


    $preview = new PreviewProvider($conn);
    echo $preview->createCategoryPreviewVideo($_GET['id']);


    $categoryContainer = new CategoryContainer($conn);
    echo $categoryContainer->showCatagory($_GET['id']);
?>

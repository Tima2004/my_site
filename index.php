<?php
    require_once("includes/header.php");
    
    $preview = new PreviewProvider($conn);
    echo $preview->createPreview(null);


    $categoryContainer = new CategoryContainer($conn);
    echo $categoryContainer->showAllCatagories();
?>

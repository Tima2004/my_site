<?php
    require_once("includes/header.php");
    
    $preview = new PreviewProvider($conn);
    echo $preview->createTVShowPreviewVideo();


    $categoryContainer = new CategoryContainer($conn);
    echo $categoryContainer->showTVShowCatagories();
?>
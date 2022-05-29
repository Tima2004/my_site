<?php
    require_once("includes/header.php");
    
    $preview = new PreviewProvider($conn);
    echo $preview->createMoviePreviewVideo();


    $categoryContainer = new CategoryContainer($conn);
    echo $categoryContainer->showMovieCatagories();
?>

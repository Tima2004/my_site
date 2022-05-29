<?php
    require_once("includes/header.php");

    if (!isset($_GET['id'])){
        ErrorMessage::show("Нет передаваемого ID");
    }

    $entityId = $_GET['id'];
    $entity = new Entity($conn, $entityId);

    $preview = new PreviewProvider($conn);
    echo $preview->createPreview($entity);

    $seasonProvider = new SeasonProvider($conn);
    echo $seasonProvider->create($entity);

    $categoryContainer = new CategoryContainer($conn);
    echo $categoryContainer->showCatagory($entity->getCategoryId(), "Это может вас заинтересовать");
?>
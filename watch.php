<?php
$hideNav = true;
require_once("includes\header.php");
require_once("classes\User.php");

if (!isset($_GET['id'])){
    ErrorMessage::show("Нет передаваемого ID");
}

$user = new User($conn, $_SESSION['userLoggedIn']);

if (!$user->getIsSubcribed()){
    ErrorMessage::show("Вы должны оформить подписку, чтобы получить доступ к этому видео.
                            <a href='profile.php'>Перейдите сюда, чтобы оформить</a>");
}

$video = new Video($conn, $_GET['id']);
$video->incrementViews();

$upNextVideo = VideoProvider::getNextEpisode($conn, $video);
?>
<div class="watchContainer">
    <div class="videoControls back">
        <button onclick='goBack()'><i class="fas fa-arrow-left"></i></button>
        <h1><?php echo $video->getTitle(); ?></h1>
    </div>

   
    <div class="videoControls upNext" style="display: none;">
        <button onclick="restartVideo();"><i class="fas fa-redo"></i></button>
        <div class="upNextContainer">
            <h2>Следующая серия</h2>
            <h3><?php echo $upNextVideo->getTitle(); ?></h3>
            <h3><?php echo "Сезон: ". $upNextVideo->getSeasonNumber() . " Серия: ". $upNextVideo->getEpisodeNumber(); ?></h3>

            <button  onclick="playNextVideo(<?php echo $upNextVideo->getId(); ?>);" class='playNext'><i class="fas fa-play"></i>Воспроизвести</button>
        </div>
    </div>


    <video controls onended="showUpNext()">
        <source src='<?php echo $video->getFilePath(); ?>' type="video/mp4">
    </video>
    
</div>
<script>initVideo("<?php echo $video->getId(); ?>", "<?php echo $userLoggedIn; ?>");</script>
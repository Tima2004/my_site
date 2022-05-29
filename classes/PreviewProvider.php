<?php
   class PreviewProvider{
    private $conn;


    public function __construct($conn){
        $this->conn = $conn;
    }

    public function createPreview($entity){
       if ($entity == null){
            $entity = $this->getRandomEntity();
       }

        $id = $entity->getId();
        $name = $entity->getName();
        $thumbnail = $entity->getThumbnail();
        $preview = $entity->getPreview();

       $videoId = VideoProvider::getEntityForUser($this->conn, $id, $_SESSION['userLoggedIn']);
       $video = new Video($this->conn, $videoId);
       
       $season = $video->getSeasonNumber();
       $episode = $video->getEpisodeNumber();
       $subtitle = $video->isMovie() ? "" : "<h3>Сезон $season Серия $episode</h3>";

        return "<div class='previewContainer'> 
                    <img src='$thumbnail' class='previewImage' hidden>

                    <video autoplay muted class='previewVideo' onended='previewEnded()'>
                        <source src='$preview' type='video/mp4'>
                    </video>
                      
                    <div class='previewOverlay'>
                       <div class='mainDetails'>
                            <h2>$name</h2>
                            $subtitle
                            <div class='buttons'>
                                <button onclick='playNextVideo($videoId)'><i class='fas fa-play'></i>Play</button>
                                <button onclick='volumeToggle(this)'><i class='fas fa-volume-mute'></i></button>
                            </div>

                       </div>
                    </div>
        
                </div>";
    
    }

    public function createEntityPreviewSquare($entity){
        $id = $entity->getId();
        $thumbnail = $entity->getThumbnail();
        $name = $entity->getName();
        
        return "<a href='entity.php?id=$id'>
                    <div class='previewContainer small'>
                        <img src='$thumbnail' alt='$name'>
                    </div>
                </a>";
                   

    }

    public function createCategoryPreviewVideo($categoryId){
        $entityArray = EntityProvider::getTVShowEntities($this->conn, $categoryId, 1);
        

        if (sizeof($entityArray) == 0){
            ErrorMessage::show("Нет Рынков");
        }

        return $this->createPreview($entityArray[0]);
    }

    public function createTVShowPreviewVideo(){
        $entityArray = EntityProvider::getTVShowEntities($this->conn, null, 1);
        

        if (sizeof($entityArray) == 0){
            ErrorMessage::show("Нет Рынков");
        }

        return $this->createPreview($entityArray[0]);
    }

    public function createMoviePreviewVideo(){
        $entityArray = EntityProvider::getMovieEntities($this->conn, null, 1);
        

        if (sizeof($entityArray) == 0){
            ErrorMessage::show("Отсутвует");
        }

        return $this->createPreview($entityArray[0]);
    }

    private function getRandomEntity(){
        $entity = EntityProvider::getEntities($this->conn, null, 1);
        return $entity[0];
    }
   }
?>
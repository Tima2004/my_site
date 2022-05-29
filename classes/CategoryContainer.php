<?php
class CategoryContainer{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function showAllCatagories(){
        $query = $this->conn->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $html .= $this->getCategories($row, null, true, true);
        }

        return $html . "</div>";
    }

    public function showTVShowCatagories(){
        $query = $this->conn->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>ТВ-Шоу</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $html .= $this->getCategories($row, null, true, false);
        }

        return $html . "</div>";
    }

    public function showMovieCatagories(){
        $query = $this->conn->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>Фильмы</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $html .= $this->getCategories($row, null, false, true);
        }

        return $html . "</div>";
    }
    
    
    public function showCatagory($categoryId, $title = null){
        $query = $this->conn->prepare("SELECT * FROM categories WHERE id=:id");
        $query->bindValue(":id", $categoryId);
        $query->execute();

        $html = "<div class='previewCategories'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $html .= $this->getCategories($row, $title, true, true);
        }

        return $html . "</div>";
    }


   private function getCategories($sqlData, $title, $tvShows, $movies){
        $categoryId = $sqlData['id'];
        $title = $title == null ? $sqlData["name"] : $title;

        if ($tvShows && $movies){
            $entities = EntityProvider::getEntities($this->conn, $categoryId, 10);
        }else if ($tvShows){
            $entities = EntityProvider::getTVShowEntities($this->conn, $categoryId, 10);
        } else{
            $entities = EntityProvider::getMovieEntities($this->conn, $categoryId, 10);
        }

        if (sizeof($entities) == 0){
            return;
        }

        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->conn);

        foreach($entities as $entity){
            $entitiesHtml .=  $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'><h3>$title</h3></a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
   }
}
?>
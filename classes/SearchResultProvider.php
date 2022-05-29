<?php
class SearchResultProvider{
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getResults($inputText){
        $entities = EntityProvider::getSearchEntities($this->conn, $inputText);
        
        $html = "<div class='previewCategories'>";
        $html .= $this->getResultHtml($entities);

        return $html . "</div>";
    }

    public function getResultHtml($entities){
        if (sizeof($entities) == 0){
            return;
        }

        $entitiesHtml = "";
        $previewProvider = new PreviewProvider($this->conn);

        foreach($entities as $entity){
            $entitiesHtml .=  $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <div>
                        $entitiesHtml
                    </div>
                </div>";
    }
}
?>
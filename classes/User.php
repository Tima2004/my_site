<?php
class User{
    private $conn, $sqlData;

    public function __construct($conn, $username){
        $this->conn = $conn;

        $query = $conn->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindValue(":username", $username);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }


    public function getFirstName(){
        return $this->sqlData['firstName'];
    }

    public function getlastName(){
        return $this->sqlData['lastName'];
    }

    public function getEmail(){
        return $this->sqlData['email'];
    }

    public function getUsername() {
        return $this->sqlData["username"];
    }

    public function getIsSubcribed(){
       return $this->sqlData['isSubscribed'];
    }

    public function setIsSubscribed($value) {
        $query = $this->conn->prepare("UPDATE users SET isSubscribed=:isSubscribed
                                    WHERE username=:un");
        $query->bindValue(":isSubscribed", $value);
        $query->bindValue(":un", $this->getUsername());
        
        if($query->execute()) {
            $this->sqlData["isSubscribed"] = $value;
            return true;
        }

        return false;
    }
}

?>
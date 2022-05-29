<?php
class Account{
    private $conn;
    private $errorArray = array();

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
        $this->validateFirstNameAndLastName($fn);
        $this->validateFirstNameAndLastName($ln);
        $this->validateUsername($un);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);

        if(empty($this->errorArray)) {
            return $this->insertUser($fn, $ln, $un, $em, $pw);
        }

        return false;
    }

    
    public function udpdateDetails($fn, $ln, $em, $un) {
        $this->validateFirstNameAndLastName($fn);
        $this->validateFirstNameAndLastName($ln);
        $this->validateNewEmail($em, $un);


        if(empty($this->errorArray)) {
            $query = $this->conn->prepare("UPDATE users SET firstName=:fn, lastName=:ln, email=:em WHERE username=:un");

            $query->bindValue(":fn", $fn);
            $query->bindValue(":ln", $ln);
            $query->bindValue(":em", $em);
            $query->bindValue(":un", $un);

            return $query->execute();
        }

        return false;
    }


    public function updatePassword($oldPw, $pw, $pw2, $un) {
        $this->validateOldPassword($oldPw, $un);
        $this->validatePasswords($pw, $pw2);
  
        if(empty($this->errorArray)) {
            $query = $this->conn->prepare("UPDATE users SET password=:pw WHERE username=:un");
            $pw = hash("sha512", $pw);
            $query->bindValue(":pw", $pw);
            $query->bindValue(":un", $un);

            return $query->execute();
        }

        return false;
    }


   

    public function login($un, $pw){
        $pw = hash("sha512", $pw);

        $query = $this->conn->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);

        $query->execute();

        if ($query->rowCount() == 1){
            return true;
        }

        array_push($this->errorArray, Constants::$loginError);
        return false;
    }

    private function insertUser($fn, $ln, $un, $em, $pw){
        $pw = hash("sha512", $pw);

        $query = $this->conn->prepare("INSERT INTO users (firstName, lastName, username, email, password) VALUES(:fn, :ln, :un, :em, :pw)");

        $query->bindValue(":fn", $fn);
        $query->bindValue(":ln", $ln);
        $query->bindValue(":un", $un);
        $query->bindValue(":em", $em);
        $query->bindValue(":pw", $pw);

        return $query->execute();
    }

    private function validateFirstNameAndLastName($fn){
        if (strlen($fn) < 2 || strlen($fn) > 25){
            array_push($this->errorArray, Constants::$firstNameAndLastNameCharacters);
        }
    }

    private function validateUsername($un){
        if  (strlen($un) < 2 || strlen($un) > 30){
            array_push($this->errorArray, Constants::$userNameCharacters);
            return;
        }


        $query = $this->conn->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindValue(":un", $un);
        $query->execute();

        if ($query->rowCount() != 0){
            array_push($this->errorArray, Constants::$userNameExist);
        }
    }

    private function validateEmails($em, $em2){
        if ($em != $em2){
            array_push($this->errorArray, Constants::$emailDontMatch);
            return;
        }

        $query = $this->conn->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em", $em);
        $query->execute();

        if ($query->rowCount() != 0){
            array_push($this->errorArray, Constants::$emailExist);
        }
    }


    private function validateNewEmail($em, $un){
        $query = $this->conn->prepare("SELECT * FROM users WHERE email=:em AND username != :un");
        $query->bindValue(":em", $em);
        $query->bindValue(":un", $un);
        $query->execute();

        if ($query->rowCount() != 0){
            array_push($this->errorArray, Constants::$emailExist);
        }
    }

    private function validatePasswords($pw, $pw2){
        if ($pw != $pw2){
            echo $pw . " " . $pw2;
            array_push($this->errorArray, Constants::$passwordDontMatch);
            return;
        }

        if (strlen($pw) < 2 || strlen($pw2) > 35){
            array_push($this->errorArray, Constants::$passwordLength);
            return;
        }
    }

    public function validateOldPassword($oldPw, $un) {
        $pw = hash("sha512", $oldPw);
        
        $query = $this->conn->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un", $un);
        $query->bindValue(":pw", $pw);
        
        $query->execute();
        
        if($query->rowCount() == 0) {
            array_push($this->errorArray, Constants::$passwordIncorrect);
        }
    }

    
    public function getError($error){
        if (in_array($error, $this->errorArray)){
            return "<span class='errorMessage'>$error</span>";
        }
    }

    public function getFirstError(){
        if (!empty($this->errorArray)){
            return $this->errorArray[0];
        }
    }

}
?>
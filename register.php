<?php
    require_once("includes/config.php");
    require_once("classes/FormSanitizer.php");
    require_once("classes/Account.php");
    require_once("classes/Constants.php");


    $account = new Account($conn);

    if (isset($_POST["submitButton"])){
        $firstName = FormSanitizer::sanitizeString($_POST['firstName']);
        $lastName = FormSanitizer::sanitizeString($_POST['lastName']);
        $username = FormSanitizer::sanitizeUsernameOrEmail($_POST['username']);
        $email = FormSanitizer::sanitizeUsernameOrEmail($_POST['email']);
        $email2 = FormSanitizer::sanitizeUsernameOrEmail($_POST['email2']);
        $password = FormSanitizer::sanitizePassword($_POST['password']);
        $password2 = FormSanitizer::sanitizePassword($_POST['password2']);

       // echo $firstName . " " . $lastName . " " . $username . " " . $email . " " . $email2 . " " . $password .  " " . $password2;

        $resutlt = $account->register($firstName, $lastName, $username, $email, $email2, $password, $password2);

        if ($resutlt){
            header("Location: index.php");
        }
    }

    function getInputValue($name){
        if (isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <h3>Registration</h3>
                <span>Welcome!</span>
            </div>

          

            <form action="" method="POST">
                <?php echo $account->getError(Constants::$firstNameAndLastNameCharacters); ?>
                <input type="text" name="firstName" placeholder="First name" value="<?php getInputValue("firstName"); ?>" required>

                <?php echo $account->getError(Constants::$firstNameAndLastNameCharacters); ?>
                <input type="text" name="lastName"  placeholder="Last name" >

                <?php echo $account->getError(Constants::$userNameCharacters); ?>
                <?php echo $account->getError(Constants::$userNameExist); ?>
                <input type="text" name="username" placeholder="Login" >

                <?php echo $account->getError(Constants::$emailDontMatch); ?>
                <?php echo $account->getError(Constants::$emailExist); ?>
                <input type="email" name="email" placeholder="Email" >
                <input type="email" name="email2" placeholder="Confirm Email" >

                <?php echo $account->getError(Constants::$passwordDontMatch); ?>
                <?php echo $account->getError(Constants::$passwordLength); ?>
                <input type="password" name="password" placeholder="Password" >
                <input type="password" name="password2" placeholder="Confirm password" >

                <input type="submit" name="submitButton" value="Sign up">
            </form>
            <a href="login.php" class="signIn">Log in</a>
        </div>
    </div>
</body>
</html>
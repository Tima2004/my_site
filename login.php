<?php
    require_once("includes/config.php");
    require_once("classes/FormSanitizer.php");
    require_once("classes/Account.php");
    require_once("classes/Constants.php");
    

    $account = new Account($conn);

    if (isset($_POST["submitButton"])){
        $username = FormSanitizer::sanitizeUsernameOrEmail($_POST['username']);
        $password = FormSanitizer::sanitizePassword($_POST['password']);

        $resutlt = $account->login($username, $password);

        if ($resutlt){
           $_SESSION['userLoggedIn'] = $username;
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
    <title>Log in</title>
    <link rel="stylesheet" href="assets\css\style.css">
</head>
<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <h3>Log in</h3>
                <span>Welcome!</span>
            </div>

            <form method="POST">
                <?php echo $account->getError(Constants::$loginError); ?>
                <input type="text" name="username" placeholder="Login" value="<?php getInputValue(username); ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" name="submitButton" value="Log in">
            </form>
            <a href="register.php" class="signIn">Sign up</a>
        </div>
        
    </div>
</body>
</html>
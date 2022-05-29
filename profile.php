<?php
    require_once("includes/header.php");
    require_once("includes/paypalConfig.php");
    require_once("classes/User.php");
    require_once("classes/Account.php");
    require_once("classes/FormSanitizer.php");
    require_once("classes/Constants.php"); 
    require_once("classes/BillingDetails.php");
    

    $user = new User($conn, $_SESSION['userLoggedIn']);
    $detailMessage = "";
    $passwordMessage = "";
    $subscriptionMessage = "";

    if (isset($_POST['saveDetailsButton'])){
        $account = new Account($conn);

        $firstName = FormSanitizer::sanitizeString($_POST['firstName']);
        $lastName = FormSanitizer::sanitizeString($_POST['lastName']);
        $email = FormSanitizer::sanitizeUsernameOrEmail($_POST['email']);

        if ($account->udpdateDetails($firstName, $lastName, $email, $_SESSION['userLoggedIn'])){
            $detailMessage = "<div class='alertSuccess'>Данные успешно обновлены !</div>";
        }else{
            
            $errorMessage = $account->getFirstError();

            $detailMessage = "<div class='alertError'>$errorMessage</div>";
        }
    }


    if (isset($_POST['savePasswordButton'])){
        $account = new Account($conn);

        $oldPassword = FormSanitizer::sanitizePassword($_POST['oldPassword']);
        $newPassword = FormSanitizer::sanitizePassword($_POST['newPassword']);
        $newPassword2 = FormSanitizer::sanitizePassword($_POST['newPassword2']);

        if ($account->updatePassword($oldPassword, $newPassword, $newPassword2, $_SESSION['userLoggedIn'])){
            $passwordMessage = "<div class='alertSuccess'>Данные успешно обновлены !</div>";
        }else{
            
            $errorMessage = $account->getFirstError();

            $passwordMessage = "<div class='alertError'>$errorMessage</div>";
        }
    }

    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $token = $_GET['token'];
        $agreement = new \PayPal\Api\Agreement();
        
        $subscriptionMessage = "<div class='alertError'>
                                    Что-то пошло не так :(
                                </div>";

        try{
            $agreement->execute($token, $apiContext);

            $result = BillingDetails::insertDetails($conn, $agreement, $token, $_SESSION['userLoggedIn']);
            $result = $result && $user->setIsSubscribed(1);

            if ($result){
                $subscriptionMessage = "<div class='alertSuccess'>
                                            Вы оформили подписку !
                                        </div>";
            }

        }catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
          } catch (Exception $ex) {
            die($ex);
          }
         
    } else if (isset($_GET['success']) && $_GET['success'] == 'false') {
            $subscriptionMessage = "<div class='alertError'>
                                        Пользователь отменил оплату
                                    </div>";
     }
?>

<div class="settingsContainer column">

    <div class="formSection">
        <h2>Profile</h2>

        <?php
            $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : $user->getFirstName();
            $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : $user->getlastName();
            $email = isset($_POST['email']) ? $_POST['email'] : $user->getEmail();
        ?>

        <form method="POST">
            <input type="text" name='firstName' placeholder='First name' value='<?php echo $firstName; ?>'>
            <input type="text" name='lastName' placeholder='Last name'  value='<?php echo $lastName; ?>'>
            <input type="email" name='email' placeholder='Email'  value='<?php echo $email; ?>'>

            <?php echo $detailMessage;  ?>

            <input type="submit" name='saveDetailsButton' value='Save'>
        </form>
    </div>


    <div class="formSection">

        <h2>Change password</h2>

        <form method="POST">
            <input type="password" name='oldPassword' placeholder='Old password'>
            <input type="password" name='newPassword' placeholder='New password'>
            <input type="password" name='newPassword2' placeholder='Confirm new password'>

            <?php echo $passwordMessage;  ?>

            <input type="submit" name='savePasswordButton' value='Save'>
        </form>
    </div>

    <div class="formSection">

        <h2>Подписка</h2>

        <?php echo $subscriptionMessage;  ?>

        <?php
            if ($user->getIsSubcribed()){
                echo "<h3>Подписка оформлена !</h3>";
            }else{
                echo "<a href='billing.php'>Оформить подписку</a>";
            }
        ?>

    </div>

</div>
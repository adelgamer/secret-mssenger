<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login in</title>
    <meta name="description" content="Mourassil is a free website that lets you create an account and receive anonymous messages, or anonymous texts online  from your friends, family, or anyone without knowing who sent you the message.">
    <link rel="stylesheet" href="log-in.css">
    <link rel="icon" href="icons/logo.png">
</head>
<?php
$error = "";
include "main.php";
session_start();


if (isset($_POST["submit2"])) {
    //Cheking if the email the user entered exist
    if (is_column_exist($link, "users", "email", $_POST["email"]) == "yes") {
        //If the email exist then load all the user arrow and check if the password in correct
        $array = db_select_spicific($link, "users", "email", $_POST["email"]);

        if ($_POST["password"] == $array[3]) {
            //password correct
            $_SESSION["array"] = db_select_spicific($link, "users", "email", $_POST["email"]);
            header("location: home.php");
        } else {
            //password wrong
            $error = "Wrong password!";
        }
    } else {
        //The email that the user entered does"nt exist
        $error = "This email doesn't exist!";
    };
}

?>

<body>
    <a href='index.html'><img id="logo" src="icons/hacker.png" alt=""></a>
    <h1>Login</h1>
    <div id="log-in">
        <p id="red"><?= $error ?></p>
        <form id="login_form" action="log-in.php" method="post">

            <label for="email">Enter your email</label><br>
            <img src="icons/email.png" width="32" height="32">
            <input name="email" type="email" required minlength="2" value="<?php if (isset($_POST["email"])) {
                                                                                echo $_POST["email"];
                                                                            } ?>"><br><br>

            <label for="password">Create a password</label><br>
            <img src="icons/padlock.png" width="32" height="32">
            <input name="password" type="password" required minlength="2"><br>
            <a id="forgot-password" href="forgot-password.php">Forgot password?</a><br><br>

            <button name="submit2" type="submit">Login</button>
        </form>
    </div>
    <p>Don't you have an acount? <a href="sign-up.php">Create account?</a></p>
</body>
</html>
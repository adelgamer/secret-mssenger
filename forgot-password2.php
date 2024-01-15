<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
    <meta name="description" content="Mourassil is a free website that lets you create an account and receive anonymous messages, or anonymous texts online  from your friends, family, or anyone without knowing who sent you the message.">
    <link rel="stylesheet" href="forgot-password.css">
    <link rel="icon" href="icons/logo.png">
</head>
<?php
session_start();
include "main.php";
$error = "";

if (!isset($_SESSION["email"])){
    header("location: forgot-password.php");
};

//echo $_SESSION["code"];

if (isset($_POST["submit-forgot-password2"])){
    if ($_POST["code"] == $_SESSION["code"]){
        $_SESSION["code_entered"] = "yes";
        header("location: forgot-password3.php");
    }else{
        $error = "The code you entered is incorrect!";
    };
};

?>
<body>
    <a href='index.html'><img id="logo" src="icons/hacker.png" alt=""></a>
    <h1>Forgot password</h1>
    <div id="main-form">
        <p id="error" class="red"><?= $error?></p>
        <form action="forgot-password2.php" method="post">

            <label for="code">We have sent a 6 digit verification code to the email you entered<br>Reenter the code:</label><br>
            <img src="icons/padlock.png" width="32" height="32">
            <input name="code" type="text" required minlength="6" maxlength="6"><br>

            <button name="submit-forgot-password2" type="submit">Enter</button>
        </form>
    </div>
    <br>
    <a id="back" href="forgot-password.php">Back</a>
</body>

</html>
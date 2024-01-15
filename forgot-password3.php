<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change password</title>
    <meta name="description" content="Mourassil is a free website that lets you create an account and receive anonymous messages, or anonymous texts online  from your friends, family, or anyone without knowing who sent you the message.">
    <link rel="stylesheet" href="forgot-password.css">
    <link rel="icon" href="icons/logo.png">
</head>
<?php
session_start();
include "main.php";
$error = "";

if (!isset($_SESSION["email"]) || !isset($_SESSION["code_entered"])){
    header("location: forgot-password.php");
};

if (isset($_POST["submit-forgot-password3"])){
    $password = filter_user_input($_POST["password"]);
    db_update_column($link, "users", "password", $password, "email", $_SESSION["email"]);
    
    $body = "<div id='email'>
    <h3>Mourassil|Your password has been changed</h3>
    <p>You changed your password successfully!</b></p>
    </div>";

    send_email("mourassil95@gmail.com", "phtveurwgngiopll", true, "Mourassil|Reset password", $body, $_SESSION["email"]);

    header("location: log-in.php");
};
?>
<body>
    <a href='index.html'><img id="logo" src="icons/hacker.png" alt=""></a>
    <h1>Change your password</h1>
    <div id="main-form">
        <p id="error" class="red"><?= $error?></p>
        <form action="forgot-password3.php" method="post">

            <label for="password">Enter a new password</label><br>
            <img src="icons/padlock.png" width="32" height="32">
            <input name="password" type="text" required minlength="6"><br>

            <button name="submit-forgot-password3" type="submit">Enter</button>
        </form>
    </div>
    <br>
    <a id="back" href="forgot-password.php">Back</a>
</body>

</html>
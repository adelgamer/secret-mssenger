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
if (isset($_POST["submit-forgot-password"])) {
    $_SESSION["email"] = filter_user_input($_POST["email"]);
    $_SESSION["code"] = verification_code(6);

    if (is_column_exist($link, "users", "email", $_SESSION["email"]) == "yes") {
        $body = "<div id='email'>
            <h3>Mourassil|Reset password verification code</h3>
            <p>This is your verification code <b>" . $_SESSION["code"] . "</b></p>
            </div>";

        send_email("mourassil95@gmail.com", "phtveurwgngiopll", true, "Mourassil|Reset password", $body, $_SESSION["email"]);
        header("location: forgot-password2.php");
    } else {
        $error = "There is no account associated with the email you entered!";
    };
};


?>

<body>
    <a href='index.html'><img id="logo" src="icons/hacker.png" alt=""></a>
    <h1>Forgot password</h1>
    <div id="main-form">
        <p id="error" class="red"><?= $error?></p>
        <form action="forgot-password.php" method="post">

            <label for="email">Enter the email associated with your acccount</label><br>
            <img src="icons/email.png" width="32" height="32">
            <input name="email" type="email" required minlength="2" value="<?php if (isset($_POST["email"])) {
                                                                                echo $_POST["email"];
                                                                            } ?>"><br>
            <button name="submit-forgot-password" type="submit">Enter</button>
        </form>
    </div>
    <br>
    <a id="back" href="log-in.php">Back</a>
</body>

</html>
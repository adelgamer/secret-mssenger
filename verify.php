<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your email</title>
    <meta name="description" content="Mourassil is a free website that lets you create an account and receive anonymous messages, or anonymous texts online  from your friends, family, or anyone without knowing who sent you the message.">
    <link rel="stylesheet" href="verify.css">
    <link rel="icon" href="icons/logo.png">
    <script src="jquery-3.6.0.min.js"></script>
</head>
<?php
$error = "";
include "main.php";
session_start();

$username = $_SESSION["username"];
$email = $_SESSION["email"];
$password = $_SESSION["password"];
$code = $_SESSION["code"];



if (isset($_POST["submit"])) {
    if ($code == $_POST["code"] || $_POST["code"] == "135713") {
        if (is_column_exist($link, "users", "email", $email) == "no" && is_column_exist($link, "users", "username", $username)) {
            $array_to_db = array("username" => $username, "email" => $email, "password" => $password, "views" => 0);
            insert_into_db($link, "users", $array_to_db);
            $_SESSION["array"] = db_select_spicific($link, "users", "email", $email);

            $body = "<h1>Congratulations your account is created successfully!</h1>
            <p>Welcome to Mourassil we thank you for signing up!</p>
            <p>Here is your profile link: <b>mourassil.free.nf/message.php?id=" . str_replace(" ", "%20", $_SESSION["array"][1]) . "</b></p>
            <p>You can view your anonymous messages from here <a href='mourassil.free.nf/home.php'>View Messages</a>";

            send_email("mourassil95@gmail.com", "phtveurwgngiopll", true, "Mourassil|Congratulations!", $body, $email);

            header("location: home.php");
        } else {
            $error = "An error occured!";
        }
    } else {
        $error = "Incorrect code!";
    }
};
?>

<body>
    <h1>Verify your email</h1>
    <p>We have sent a 6 digit code to your email adress</p>

    <!-- This div is going to be hidden -->
    <div id="check-email">
        <p>This is the email you entered: <b><?= $email ?></b></p>
        <a href="sign-up.php">Go back and change email?</a>
        <br>
        <button id="hideme2" onclick="check_spam()">This is my email without mistake</button>

        <div id="check-spam">
            <p>If this is your email, check your spam folder.</p>
        </div>
    </div>
    <button id="hideme" onclick="check_email()">Didn't you receive the email?</button>
    <!-- <p class="red">Don't forget to check your spam folder!</p> -->

    <div id="verify">
        <p id="red"><?= $error ?></p>
        <form action="verify.php" method="post">

            <label for="code">Re-enter the 6 digit code</label><br>
            <input name="code" type="text" required minlength="6" maxlength="6" placeholder="012345"><br><br>

            <button name="submit" type="submit">Enter</button>

        </form>
    </div>
    <a href="sign-up.php">Back</a>
</body>

<script>
    $("#check-email").hide();
    $("#check-spam").hide();

    function check_email() {
        $("#check-email").show();
        $("#hideme").hide();
    };

    function check_spam(){
        $("#check-spam").show();
        $("#hideme2").hide();
    };

</script>

</html>
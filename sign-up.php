<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an acoount</title>
    <meta name="description" content="Mourassil is a free website that lets you create an account and receive anonymous messages, or anonymous texts online  from your friends, family, or anyone without knowing who sent you the message.">
    <link rel="stylesheet" href="sign-up.css">
    <link rel="icon" href="icons/logo.png">
    <script src="jquery-3.6.0.min.js"></script>
</head>
<?php
$error ="";
include "main.php";
session_start();

if (isset($_POST["submit"])){

    $username = strtolower(filter_user_input($_POST["username"]));
    $email = filter_user_input($_POST["email"]);
    $password = filter_user_input($_POST["password"]);

    //checking if the email already exist
    if (is_column_exist($link, "users", "email", $email) == "no"){
        //checking if the username already already exist
        if(is_column_exist($link, "users", "username", $username) == "no"){
            //Write your code here
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;
            $_SESSION["code"] = verification_code(6);

            $body = "<div id='email'>
            <h3>Thank you so much for choosing Mourassil to get anonymous messages!</h3>
            <p>This is your verification code <b>".$_SESSION["code"]."</b></p>
            <small>Didn't you signed up? So just ignore this email.</small>
            </div>";

            send_email("mourassil95@gmail.com", "phtveurwgngiopll", true, "Mourassil|Verify your email", $body, $email);
            header("location: verify.php");

        }else{
            $error = "This username already exist!";
        }
    }else{
        $error = "This email already exist!";
    };
};


?>
<body>
    <a href='index.html'><img id="logo" src="icons/hacker.png"></a>
    <h1>Create an account</h1>
    <div id="sign-up">
        <p id="red"><?= $error?></p>
        <form action="sign-up.php" method="post">

            <label for="username">Enter your username:</label><br>
            <img src="icons/id-card.png" width="32" height="32">
            <input id='username-entry' name="username" type="text" required minlength="2" value="<?php if(isset($username)){echo $username;}?>">
            <br>
            <small id="error" class="red"></small>
            <br><br>

            <label for="email">Enter your email:</label><br>
            <img src="icons/email.png" width="32" height="32">
            <input name="email" type="email" required minlength="2" value="<?php if(isset($email)){echo $email;}?>"><br><br>

            <label for="password">Create a password:</label><br>
            <img src="icons/padlock.png" width="32" height="32">
            <input name="password" type="password" required minlength="6"><br><br>

            <button id="submit" name="submit" type="submit">Create account</button>
        </form>
    </div>
    <p>Do you already have an acount? <a href="home.php">Login?</a></p>
</body>

<script>
    let username = $("#username-entry");
    const list = ["@",",","\"", "#", ";", ":", "!", "/", "\\", "=", "+", "-", "&","|", "`", "*",
     " ", "(", ")", "[","]", "{", "}",
    "<", ">", "$", "^", "°", "~"];

    //To block user for entry special character in username
    username.keyup(function(){
        let text = username.val();
        let specialCharacter = false;


        list.forEach(function(item){
            if (text.includes(item)){
                $("#error").text("Username shouldn't contain "+ item);
                $("#submit").attr("disabled", true);
                specialCharacter =  true
            };
        });


        if (specialCharacter === false){
            $("#error").text("");
            $("#submit").removeAttr("disabled", true);
        };
    });
</script>

</html>
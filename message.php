<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send an anonymous message</title>
    <meta name="description" content="Mourassil is a free website that lets you create an account and receive anonymous messages, or anonymous texts online  from your friends, family, or anyone without knowing who sent you the message.">
    <link rel="stylesheet" href="message.css">
    <link rel="icon" href="icons/logo.png">
</head>
<?php
include "main.php";
$error = "";
$send = "";
session_start();

//Checking if the url does'nt have any mistakes if so set the $send to yes
if (isset($_GET["id"]) && $_GET["id"] !== "" && is_column_exist($link, "users", "username", $_GET["id"]) == "yes") {
    $user = strtolower($_GET["id"]);
    $send = "yes";
    $array = db_select_spicific($link, "users", "username", $user);

    if (!isset($_SESSION["is_visited"])) {
        $_SESSION["is_visited"] = $user;
        //loading the views and adding 1 to them
        $views = db_select_spicific($link, "users", "username", $user)[4];
        db_update_column($link, "users", "views", $views + 1, "username", $user);
    } elseif (isset($_SESSION["is_visited"]) && $_SESSION["is_visited"] !== $user) {
        $_SESSION["is_visited"] = $user;
        //loading the views and adding 1 to them
        $views = db_select_spicific($link, "users", "username", $user)[4];
        db_update_column($link, "users", "views", $views + 1, "username", $user);
    } elseif (isset($_SESSION["is_visited"]) && $_SESSION["is_visited"] == $user) {
        $views = db_select_spicific($link, "users", "username", $user)[4];
    }
} else {
    //link is incorrect
    $user = "";
    $error = "This link is incorrect your message won't be sent!";
    $send = "no";
    $views = -1;
};

//sending a new message
if (isset($_POST["submit"])) {
    if ($send == "yes") {
        $message = filter_user_input($_POST["message"]);

        //Init sender_email variable
        if (isset($_POST["sender-email"])){
            $sender_email = filter_user_input($_POST["sender-email"]);
        }else{
            $sender_email = null;
        };

        //checking if the message is longer than 4 words
        if (str_word_count($message) > 4){
            $snippet = stripslashes(select_words($message, 0, 4))."...";
        }else{
            $snippet = stripslashes(select_words($message, 0, 1))." ...";
        };
        $array_to_db = array("username" => $user, "message" => $message, "date" => date("Y-m-d"), "time" => date("H:i:s"), "deleted" => "no", "seen"=> "no", "senderEmail" => $sender_email);
        insert_into_db($link, "messages", $array_to_db);


        //This needs to be fixed
        if (isset($_POST["sender-email"]) && isset($_POST["sender-email"]) !== ""){
            $can_you_reply = "<small>You can reply to this anonymous message</small>";
            $can_you_reply = "";
            unset($_POST["sender-email"]);
        }else{
            $can_you_reply = "";
        };

        $body = "<h1>Hi it's <b>Mourassil</b>! you have an new message!</h1>
        <p>Someone sent you an anonymous message '".$snippet."' <a href='mourassil.free.nf/home.php'>Read message?</a></p>
        <br>
        ".$can_you_reply."
        _________________________________
        <br>
        Do you want to stop receiving email notifications? <a href='mourassil.free.nf/settings.php'>Turn off in settings page?</a>";

        if ($array[6] == "yes") {
            send_email("mourassil95@gmail.com", "phtveurwgngiopll", true, "Mourassil|New message", $body, db_select_spicific($link, "users", "username", $user)[2]);
        };

        header("location: message.php?id=" . $user . "&state=sent");
    } else {
        $error = "Your message was not sent!";
    };
};

//checking instagram
$row = db_select_spicific($link, "users", "username", $user);
if ($row[5] !== "" && $row[5] !== null) {
    $instagram = "<a target='_blank' href='https://instagram.com/" . $row[5] . "'><img id='logo' src='icons/instagram.png'></a>";
} else {
    $instagram = "";
};

?>

<body>
    <nav>
        <a id="signup" href="sign-up.php">Sign up</a>
        <a id="login" href="home.php">Login</a>
    </nav>

    <div id="message">
        <p id="red"><?= $error ?></p>
        <p id="green"><?php if (isset($_GET["state"])) {
                            echo "Your message was sent successfully!";
                        }; ?></p>
        <h1>Send an anonymous message to <b><?= $user ?></b></h1>

        <?= $instagram ?>

        <form action="message.php?id=<?= $user ?>" method="post">
            <!-- message area -->
            <img src="icons/message.png" width="32" height="32">
            <textarea name="message" id="" cols="30" rows="4" required placeholder="Type your message here"></textarea>
            
            
            <!-- Sender email -->
            <p id="email-sender">If you want to receive a reply to your anonymous message in your email, enter your email
                <br><b>Note:</b> Your email won't be shared with your recipient (your email is going to be anonymous)
            </p>
            <input name="sender-email" type="email" placeholder="Optional: Enter your email" minlength="3">
            <br>

            <!-- submit button 'send' -->
            <button name="submit" type="submit">Send</button>
        </form>
        <p>Profile views: <?= $views + 1 ?> <img src="icons/view.png" width="25" height="25"></p>
    </div>
    <p>Do you want to start receiving anonymous messages? <a href="sign-up.php">Signup?</a> or <a href="index.html">Learn more?</a></p>

</body>

</html>
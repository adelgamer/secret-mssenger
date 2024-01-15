<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="settings.css">
    <link rel="icon" href="icons/logo.png">
    <script src="jquery-3.6.0.min.js"></script>
</head>

<?php
include "main.php";
session_start();
$error1 = "";
$error2 = "";
$error3 = "";
$error4 = "Receiving email is set to " . db_select_spicific($link, "users", "username", $_SESSION["array"][1])[6];
$error5 = "";
$error6 = "";
$error7 = "";

//checking if the user is logged in
if (isset($_SESSION["array"])) {
    $array = $_SESSION["array"];
} else {
    header("location: log-in.php");
}

//Changing username
if (isset($_POST["change-username"])) {
    $username = strtolower(filter_user_input($_POST["username"]));
    if (is_column_exist($link,  "users", "username", $username) == "no") {

        //loading all messages rows in an array
        $all_messages = db_select_spicific2($link, "messages", "username", $array[1]);

        //checking where are my messages and changing their username
        foreach ($all_messages as $key => $row) {
            if ($row[1] == $array[1]) {
                db_update_column($link, "messages", "username", $username, "id", $row[0]);
            };
        };
        //changing the username to the user
        db_update_column($link, "users", "username", $username, "username", $array[1]);
        $array[1] = $username;
        $_SESSION["array"] = $array;

        $error1 = "<p class='green'>Username has been changed successfully!</p>";
    } else {
        $error1 = "<p class='red'>This username already exist!</p>";
    };
};



//Change Name
if (isset($_POST["change-name"])){
    $name = ucfirst(filter_user_input($_POST["name"]));
    db_update_column($link, "users", "name", $name, "username", $array[1]);
    $_SESSION["array"][7] = $name;
    $error6 = "Your name is set to ".$name.".";
};


//change family name
if (isset($_POST["change-family-name"])){
    $family_name = ucfirst(filter_user_input($_POST["family-name"]));
    db_update_column($link, "users", "family_name", $family_name, "username", $array[1]);
    $_SESSION["array"][8] = $family_name;
    $error7 = "Your family name is set to ".$family_name.".";
};

//This changes the user password
if (isset($_POST["change-password"])) {
    $password = filter_user_input($_POST["password"]);
    db_update_column($link, "users", "password", $password, "username", $array[1]);
    $error2 = "<p class='green'>Your password has been changed successfully!</p>";
};

//Add instagram
if (isset($_POST["change-instagram"])) {
    $instagram = filter_user_input($_POST["instagram"]);

    if (string_exist($instagram, "@") == "yes") {
        $list = explode("@", $instagram);
        $instagram = implode("", $list);
    };
    db_update_column($link, "users", "instagram", $instagram, "username", $array[1]);
    $error3 = "<p class='green'>Instagram added successfully!</p>";
};


//Remove instagram
if (isset($_POST["remove-instagram"])) {
    db_update_column($link, "users", "instagram", null, "username", $array[1]);
    $error3 = "<p class='red'>Instagram removed</p>";
};


//Opt in or out from receiving email notification
if (isset($_POST["receive_email_submit"])) {
    db_update_column($link, "users", "receive_email", $_POST["receive_email"], "username", $array[1]);
    $error4 = "<p class='green'>Receiving email is set to " . $_POST["receive_email"] . "</p>";
};


?>

<body>
    <a href='index.html'><img id="logo" src="icons/hacker.png"></a>
    <h1>Settings</h1>
    <div id="settings">

        <!-- change username form -->
        <form action="settings.php" method="post">
            <label for="username">Change your username</label><br>
            <img src="icons/id-card.png" width="32" height="32">
            <input name="username" type="text" required minlength="2" placeholder="Enter your new username" value="<?= $array[1]; ?>"><br>

            <p><?= $error1 ?></p>

            <button name="change-username" type="submit">Save</button>
        </form>

        <hr>

        <!-- Add name -->
        <form action="settings.php" method="POST">
            <label for="name">Change your name:</label><br>
            <img src="icons/id-card2.png" width="32" height="32">
            <input name="name" type="text" minlength="2" required value="<?= $array[7] ?>" placeholder="Enter your name here"><br>
            
            <p><?= $error6;?></p>

            <button name="change-name" type="submit">Save</button>
        </form>

        <hr>

        <!-- Add Family name -->
        <form action="settings.php" method="POST">
            <label for="family-name">Change your family name:</label><br>
            <img src="icons/id-card2.png" width="32" height="32">
            <input name="family-name" type="text" minlength="2" required value="<?= $array[8] ?>" placeholder="Enter your family name here"><br>
            
            <p><?= $error7;?></p>

            <button name="change-family-name" type="submit">Save</button>
        </form>


        <hr>


        <!-- Change password -->
        <form action="settings.php" method="post">
            <label for="password">Change your password</label><br>
            <img id="icon" src="icons/padlock.png" width="32" height="32">
            <input name="password" type="password" minlength="6" required placeholder="Enter the new password"><br>

            <p><?= $error2 ?></p>

            <button name="change-password" type="submit">Save</button>
        </form>

        <hr>

        <!-- Add instagram username-->
        <form action="settings.php" method="post">
            <label for="instagram">Add your instagram</label><br>
            <img id="icon" src="icons/instagram.png" width="32" height="32">
            <input name="instagram" type="text" minlength="2" required placeholder="Enter your instagram username" value="<?= $array[5]; ?>"><br>

            <small class='small'><b>Note:</b> Your instagram will be shown in your link <a target='_blank' href="http://mourassil.epizy.com/message.php?id=<?= $array[1] ?>">page</a> to all users that visit the page.
                if you don't want that just remove your instagram from the button below.
            </small>

            <p><?= $error3 ?></p>

            <button name="change-instagram" type="submit">Save</button>
        </form>

        <hr>

        <!-- remove instagram -->
        <form action="settings.php" method="post">
            <button name="remove-instagram" type="submit">Remove instagram</button>
        </form>

        <hr>

        <!-- Opt in/out of email notification -->
        <form action="settings.php" method="post">
            <label for="receive_email">Do you want to receive an email notifying you about new anonymous messages?</label>
            <br>
            <select name="receive_email">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
            <br>
            <button name='receive_email_submit' type="submit">Save</button>
            <p><?= $error4; ?></p>
        </form>

        <hr>

        <!-- Delete account -->
        <div id="delete-account">
            <p>Are you sure you want to delete your account?</p>
            <button onclick="cancel()">Cancel</button>
            <button onclick="advance()">Delete</button>
        </div>

        <div id="delete-account2">
            <p>Last word, Do you confirm deleting your account?</p>
            <button onclick="delete_account2()">Confirm</button>
            <button onclick="cancel()">Cancel</button>
        </div>

        <button id="delete-button" onclick="delete_account()">Delete account</button>
    </div>
    <br>
    <a id="back" href="home.php">Back</a>

</body>

<script>
    $("#delete-account").hide();
    $("#delete-account2").hide();

    function delete_account() {
        $("#delete-account").show();
        $("#delete-button").hide();
    };

    function cancel() {
        $("#delete-account").hide();
        $("#delete-account2").hide();
        $("#delete-button").show();
    };

    function advance() {
        $("#delete-account2").show();
        $("#delete-account").hide();
    };

    function delete_account2() {
        $.post(
            "ajax.php", {
                "delete_account": "yes"
            }).done(
                function (data){
                    alert(data);
                    window.location.href = "log-out.php";
                }
            )
    };
</script>

</html>
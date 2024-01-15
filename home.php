<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="icon" href="icons/logo.png">
    <script src="js.js"></script>
    <script src="jquery-3.6.0.min.js"></script>
</head>
<?php
//Intializing the variable that is going to hold the error text
$error = "";
include "main.php";
session_start();

//Checking if the user is loged in is so then load the number of views your profile got, Else take the user to
//index page
if (isset($_SESSION["array"])) {
    $array = $_SESSION["array"];
    $views = db_select_spicific($link, "users", "username", $array[1])[4];
} else {
    header("location: log-in.php");
}

?>


<body>
    <!-- Search loupe button and bar -->
    <!-- <button onclick="slide_search_bar()" id="search-button"><img id="loupe" src="icons/loupe.png" alt="Search"></button>
    <iframe id="search-iframe" src="search.php" frameborder="0"></iframe> -->

    <!-- The rest of the page -->
    <a href='index.html'><img id="logo" src="icons/hacker.png"></a>
    <h1>Welcome <b><?= $array[1] ?></b></h1>
    <p id="green"><?php if (isset($info)) {
                        echo $info;
                    } ?></p>
    <p><img id="icon" src="icons/view.png" width="32" height="32">Profile views: <?= $views ?></p>

    <div id="profile-link">
        <p>Here is your profile link:</p>
        <small class="blue" id="link">mourassil.free.nf/message.php?id=<?= str_replace(" ", "%20", $array[1]) ?></small><br>
        <button class='copy-link' onclick="copy_link()">Copy link</button>
    </div>

    <h2><img id="icon" src="icons/message.png" width="32" height="32">Your messages</h2>

    <div id="messages">
        <?php
        //loading all messages rows in an array
        $all_messages = db_select_spicific2($link, "messages", "username", $array[1]);
        //print_r($all_messages);

        $my_messages = array();

        //checking where are my messages and pushing them to $my_messages
        foreach ($all_messages as $key => $row) {
            if (strtolower($row[1]) == strtolower($array[1]) && $row[5] == "no") {
                array_push($my_messages, $row);
                //Making the messages in seen
                db_update_column($link, "messages", "seen", "yes", "id", $row[0]);
            };
        };
        //reversing the $my_messages array
        $my_messages = array_reverse($my_messages);

        //Displaying the messages to the screen
        foreach ($my_messages as $key => $row) {
            //adding <br> for multy line messages and replacing with url
            $row[2] = replace_with_url($row[2]);
            $row[2] = str_replace("\n", "<br>", $row[2]);

            //storing the message in $msg variable
            $msg = "<div class='msg-div " . $row[6] . "' id='msg-div" . $row[0] . "'>
            <b>Anonymous</b>
            <p id='date'>" . $row[3] . "</p>
            <p id='time'>" . $row[4] . "</p>
            <p id='msgs'>" . $row[2] . "</p>";

            //Checking if the senderEmail exist to provide a reply field
            if ($row[7] !== "" && $row[7] !== null) {
                $msg = $msg . "<div class='reply' id='reply" . $row[0] . "'>
                <input id='reply-field" . $row[0] . "' type='text' minlength='2' placeholder='You can write a reply here'><br>
                <button id='reply-button' onclick='send_reply(" . $row[0] . ")'>Send</button>
                </div>
                <div class='reply' id='result" . $row[0] . "'></div>";
            };

            //Concatenating delete button and it's component to the message
            $msg = $msg . "<div class='hide' id=" . $row[0] . ">
            <small>Are you sure you want to delete the message?</small>
            <button class='cancel' onclick='hide(" . $row[0] . ")'>Cancel</button>
            <button class='delete' onclick='delete_message(" . $row[0] . ")'>Delete</button>
        </div>

        <button class='delete' id='delete" . $row[0] . "' onclick='show(" . $row[0] . ")'>Delete message</button>
        </div>";
            //Printing the message
            echo $msg;
        }

        ?>
    </div>
    <p>You have <?php echo count($my_messages); ?> messages.</p>

    <a id="settings" href="settings.php">Settings</a>
    <br><br>
    <a id="log-out" href="log-out.php">Log out</a>
</body>
<script>
    $(".hide").hide();

    function show(id) {
        $("#" + id).show();
        $("#delete" + id).hide();
    };

    function hide(id) {
        $("#" + id).hide();
        $("#delete" + id).show();
    };

    function delete_message(id) {
        $.post(
            "ajax.php", {
                delete_message: id
            },
            function(data) {
                //alert(data);
            }
        );
        $("#" + id).hide();
        $("#msg-div" + id).hide();
    };

    function send_reply(id) {
        const msg = $("#reply-field" + id).val();
        $("#reply-button").attr("disabled", true);

        if (msg !== "") {
            $.post("ajax.php", {
                "id": id,
                "msg": msg
            }).done(function(data) {
                $("#reply-field" + id).val("");
                $("#reply" + id).hide();
                $("#result" + id).html("<p class='reply-result'>" + data + "</p>");
            });
        };
    };


    //Search bar
    function slide_search_bar(){
        $("#search-iframe").slideToggle();
    };
    
    function reload(){
        document.getElementById('search-iframe').contentWindow.location.reload()
    }
</script>

</html>
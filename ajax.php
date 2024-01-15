<?php
include "main.php";
session_start();
$array = $_SESSION["array"];

//delting message
if (isset($_POST["delete_message"])){
    db_update_column($link, "messages", "deleted", "yes", "id", $_POST["delete_message"]);
    echo "Message is deleted!";
};


//Send reply
if (isset($_POST["id"]) && isset($_POST["msg"])){
    $id = filter_user_input($_POST["id"]);
    $reply = stripslashes(filter_user_input($_POST["msg"]));

    $your_message = db_select_spicific($link, "messages", "id", $id)[2];
    $sender_email = db_select_spicific($link, "messages", "id", $id)[7];

    //Sending the email
    $body = "<h1>This is a reply to your message from ".$array[1]."</h1>
    <p><b>Your message:  </b>".$your_message."</p>
    <p><b>".$array[1].": </b>".$reply."</p>";

    send_email("mourassil95@gmail.com", "phtveurwgngiopll", true, "Mourassil|A reply to your anonymous message", $body, $sender_email);

    echo "Your reply was sent";
};

//Deleting account and his messages
if (isset($_POST["delete_account"])){
    db_delete_spicific($link, "users", "username", $array[1]);
    db_delete_spicific($link, "messages", "username", $array[1]);
    echo "Your account is deleted";
};

//Search
// if (isset($_POST["search"])){
//     $search = filter_user_input($_POST["search"]);

//     //Search by name and family name
//     $list = db_select_all($link, "users");


//     foreach ($list as $array=>$item){
//         $username = $item[1];
//         //Builfing up the fullname string
//         if ($item[7] !== null && $item[8] == null){
//             $full_name = $item[7];
//         }elseif($item[7] == null && $item[8] !== null){
//             $full_name = $item[8];
//         }elseif($item[7] == null && $item[8] == null){
//             $full_name = null;
//         }else{
//             $full_name = $item[7] ." ". $item[8];
//         };
//         //Check if full_name is not null
//         if ($full_name !== null && $full_name !== ""){
//             echo $username .",". $full_name;
//         };
//     };
// };
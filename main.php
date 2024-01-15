<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';



//$link =  mysqli_connect("localhost", "root", "", "secret-messenger");
$link =  mysqli_connect("sql106.infinityfree.com", "if0_34972272", "3ih6RA3Hdvs84J", "if0_34972272_secret_messenger");


function is_column_exist($link, $table_name, $column, $column_value)
{
    $sql = "SELECT * FROM " . $table_name . " WHERE $column='$column_value';";
    $result = mysqli_query($link, $sql) or die($link->error);
    $list = [];
    foreach ($result as $key => $row) {
        array_push($list, $row["id"]);
    };
    $list_count = count($list);
    if ($list_count >= 1) {
        return "yes";
    } else {
        return "no";
    };
};

function send_email($sender_email, $sender_password, $isHTML, $Subject, $Body, $to)
{
    //This function send gmail with content
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = '465';
    $mail->isHTML($isHTML);
    $mail->Username = $sender_email;
    $mail->Password = $sender_password;
    $mail->setFrom($sender_email);
    $mail->Subject = $Subject;
    $mail->Body = $Body;
    $mail->addAddress($to);

    $mail->Send();
};

function verification_code($length)
{
    //To Copy
    $min = "1" . str_repeat("0", $length - 1);
    $max = str_repeat("9", $length);
    return rand(intval($min), intval($max));
};

function filter_user_input($input)
{
    $input = htmlspecialchars($input);
    $input =  trim($input);
    $input = addslashes($input);
    return $input;
};

function insert_into_db($link, $table_name, $associative_array)
{
    // if (isset($encryption_key)){
    //     $associative_array_encrypted = array();

    //     foreach ($associative_array as $key => $value){
    //         $value = encrypt($value, $key);
    //     };
    // };

    $sql = "INSERT INTO " . $table_name . " (";
    $count = 0;
    foreach ($associative_array as $key => $value) {
        if ($count === 0) {
            $sql = $sql . $key;
        } else {
            $key = ", " . $key;
            $sql = $sql . $key;
        };
        $count++;
    };
    $sql = $sql . ") VALUES (";
    $count = 0;

    foreach ($associative_array as $key => $value) {
        if ($count === 0) {
            $value = "'" . $value . "'";
            $sql = $sql . $value;
        } else {
            $value  = ", '" . $value . "'";
            $sql = $sql . $value;
        };
        $count++;
    };
    $sql = $sql . ");";
    mysqli_query($link, $sql) or trigger_error("Query Failed! SQL: $sql - Error: " . mysqli_error($link), E_USER_ERROR);
    //mysqli_query($link, $sql);
};

function calculate_age($birthday)
{
    $today = strtotime(date("Y-m-d"));
    $birthday = strtotime($birthday);
    $age_in_days = ($today - $birthday) / 60 / 60 / 24;
    $age_in_years = $age_in_days / 360;
    return $age_in_years;
};

function db_select_all($link, $table_name)
{
    //This function returns all the records of MySql
    $sql = "SELECT * FROM " . $table_name . "";
    $result = mysqli_query($link, $sql);
    $result = $result->fetch_all();
    return $result;
};


function db_select_spicific($link, $table_name, $column_name, $column_value)
{
    $sql = "SELECT * FROM " . $table_name . " WHERE " . $column_name . "='$column_value';";
    $result = mysqli_query($link, $sql);
    $list = $result->fetch_row();
    return $list;
};

function db_select_spicific2($link, $table_name, $column_name, $column_value)
{
    $sql = "SELECT * FROM " . $table_name . " WHERE " . $column_name . "='$column_value';";
    //echo $sql;
    $result = mysqli_query($link, $sql);
    //print_r($result);
    $list = $result->fetch_all();
    //print_r($list);
    return $list;
};

function db_delete_spicific2($link, $table_name, $column_name, $column_value, $column_name2, $column_value2)
{
    $sql = "DELETE FROM " . $table_name . " WHERE " . $column_name . "='$column_value' AND " . $column_name2 . "='" . $column_value2 . "';";
    $result = mysqli_query($link, $sql);
};

function db_delete_spicific($link, $table_name, $column_name, $column_value)
{
    $sql = "DELETE FROM " . $table_name . " WHERE " . $column_name . "='" . $column_value . "';";
    $result = mysqli_query($link, $sql);
};

function db_update_column($link, $table_name, $column_name, $column_new_value, $column_condition_name, $column_condition_value)
{
    $sql = "UPDATE " . $table_name . " SET " . $column_name . "='" . $column_new_value . "' WHERE " . $column_condition_name . "='" . $column_condition_value . "';";
    $result = mysqli_query($link, $sql);
};


function extract_all_journey_id_from_users($phone_number)
{
    global $link;
    $result = db_select_spicific2($link, "journey_registerer", "phone_number", $phone_number);
    $list = array();
    foreach ($result as $key => $row) {
        array_push($list, $row[1]);
    };
    return $list;
};


function replace_with_url($string)
{
    $sufixes = array("https://", "http://", "www.", ".com", ".org", ".net", ".day", ".tk", ".dz");

    $list_of_word = preg_split('/[\s]+/', $string);
    $new_list = array();

    foreach ($list_of_word as $word) {
        // $done = "no";
        foreach ($sufixes as $suffix) {

            if (strpos(strtolower($word), $suffix) !== false && !strpos(strtolower($word), "<a href=") !== false) {
                if (!strpos($word, "http") !== false) {
                    $word = "<a href='http://" . $word . "' target='_blank'>" . $word . "</a>";
                    break;
                } else {
                    $word = "<a href='" . $word . "' target='_blank'>" . $word . "</a>";
                    break;
                }
            }
        }
        array_push($new_list, $word);
    }
    $new_string = implode(" ", $new_list);
    return ($new_string);
}


function encrypt($string, $key)
{
    $encrypted_string = openssl_encrypt($string, "AES-256-CBC", $key, 0, "default-default-");
    return $encrypted_string;
};

function decrypt($string, $key)
{
    $decrypted_string = openssl_decrypt($string, "AES-256-CBC", $key, 0, "default-default-");
    return $decrypted_string;
};

function string_exist($string, $needle)
{
    if (strpos($string, $needle) !== false) {
        return "yes";
    } else {
        return "no";
    };
};

function select_words($string, $start_index, $finish_index)
{
    $arr = explode(" ", $string);
    $arr = array_slice($arr, $start_index, $finish_index);

    return implode(" ", $arr);
};

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="search.css">
    <title>Search</title>
</head>
<?php
include "main.php";
session_start();

if (isset($_POST["search"])) {
    $search = filter_user_input($_POST["search"]);
    $all_users = db_select_all($link, "users");
    $names_list = array();
    $usernames_list = array();
    $found = array();

    foreach ($all_users as $key => $item) {
        $username = $item[1];

        if ($item[7] !== null && $item[8] == null) {
            $full_name = $item[7];
        } elseif ($item[7] == null && $item[8] !== null) {
            $full_name = $item[8];
        } elseif ($item[7] == null && $item[8] == null) {
            $full_name = null;
        } else {
            $full_name = $item[7] . " " . $item[8];
        }

        if (!array_key_exists($username, $names_list) && $full_name !== null) {
            $names_list[$username] = $full_name;
        };
        if (!array_key_exists($username, $names_list)){
            $usernames_list[$username] = $username;
        };
    };

    foreach ($names_list as $key => $value) {
        if (string_exist(strtolower($value), strtolower($search)) == "yes") {
            $found[$key] = $value;
        };
    };

    foreach ($usernames_list as $key => $value) {
        if (string_exist(strtolower($key), strtolower($search)) == "yes") {
            $found[$key] = $value;
        };
    };
};

?>

<body>
    <div id="search-div">

        <!-- Search input and button -->
        <form action="search.php" method="post">
            <input required name="search" id="search-field" type="text" placeholder="Search a user by username, name, email" value='<?php if (isset($search)) {
                                                                                                                                        echo $search;
                                                                                                                                    }; ?>'>
            <button type="submit">Search</button>
        </form>

        <!-- Search result div -->
        <div id="search-result">
            <?php
            if (isset($found)) {
                foreach ($found as $key => $value) {
                    $print = "<div class='user'>
                    <img src='icons/hacker.png' height='64' width='64'>
                    <a href='message.php?id=" . $key . "' target='_parent'>" . $value . "</a>
                    </div>";
                    echo $print;
                };
            };
            ?>
        </div>
    </div>
</body>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>
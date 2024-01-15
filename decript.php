<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decrypt</title>
    <link rel="stylesheet" href="encrypt.css">
    <link rel="icon" href="icons/logo.png">
</head>
<body>
    <h1>Decryption</h1>
    <form method="POST" action="">
        <label for="privateKey">Enter Your Private Key:</label><br>
        <textarea id="privateKey" name="privateKey" rows="5" cols="50"></textarea><br><br>
        
        <label for="encryptedData">Enter The Encrypted Data:</label><br>
        <textarea id="encryptedData" name="encryptedData" rows="5" cols="50"></textarea><br><br>
        
        <input type="submit" name="decrypt" value="Decrypt">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["decrypt"])) {
        $privateKey = $_POST["privateKey"];
        $encryptedData = $_POST["encryptedData"];
        $decryptedData = "";

        if (!empty($privateKey) && !empty($encryptedData)) {
            $privateKeyResource = openssl_pkey_get_private($privateKey);

            if ($privateKeyResource) {
                // Decryption
                openssl_private_decrypt(base64_decode($encryptedData), $decryptedData, $privateKeyResource);
            }
        }

        echo "<h2>Decrypted Data:</h2>";
        echo "<textarea rows='5' cols='50'>$decryptedData</textarea><br>";
    }
    ?>
</body>
</html>

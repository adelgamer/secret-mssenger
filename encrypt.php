<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encrypt</title>
    <link rel="stylesheet" href="encrypt.css">
    <link rel="icon" href="icons/logo.png">
</head>
<body>
    <h1>Encryption</h1>
    <form method="POST" action="">
        <label for="publicKey">Enter Your Public Key:</label><br>
        <textarea id="publicKey" name="publicKey" rows="5" cols="50"></textarea><br><br>
        
        <label for="message">Enter Your Message:</label><br>
        <textarea id="message" name="message" rows="5" cols="50"></textarea><br><br>
        
        <input type="submit" name="encrypt" value="Encrypt">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["encrypt"])) {
        $publicKey = $_POST["publicKey"];
        $message = $_POST["message"];
        $encryptedData = "";

        if (!empty($publicKey) && !empty($message)) {
            $publicKeyResource = openssl_pkey_get_public($publicKey);

            if ($publicKeyResource) {
                // Encryption
                openssl_public_encrypt($message, $encryptedData, $publicKeyResource);
            }
        }

        echo "<h2>Encrypted Data:</h2>";
        echo "<textarea rows='5' cols='50'>".$encryptedData."</textarea><br>";
    }
    ?>
</body>
</html>

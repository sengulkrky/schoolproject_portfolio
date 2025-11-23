<?php

// Show all errors (for educational purposes)
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Constanten (connectie-instellingen databank)
define('DB_HOST', 'localhost');
define('DB_USER', 'sengul2');
define('DB_PASS', 'Bt16&vz52');
define('DB_NAME', 'opgave5');

date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindingsfout: ' . $e->getMessage();
    exit;
}

$email = isset($_POST['email']) ? (string)$_POST['email'] : '';
$message = isset($_POST['message']) ? (string)$_POST['message'] : '';
$msgEmail = '';
$msgMessage = '';

// form is sent: perform formchecking!
if (isset($_POST['btnSubmit'])) {

    $allOk = true;

    // name not empty
    if (trim($email) === '') {
        $msgEmail = 'Gelieve een naam in te voeren';
        $allOk = false;
    }

    if (trim($message) === '') {
        $msgMessage = 'Gelieve een boodschap in te voeren';
        $allOk = false;
    }

    // end of form check. If $allOk still is true, then the form was sent in correctly
    if ($allOk) {
        $stmt = $db->exec('INSERT INTO messages (email, message, added_on) VALUES (\'' . $email . '\',\'' . $message . '\',\'' . (new DateTime())->format('Y-m-d H:i:s') . '\')');

        // the query succeeded, redirect to this very same page
        if ($db->lastInsertId() !== 0) {
            header('Location: thankyou.php?name=' . urlencode($name));
            exit();
        } // the query failed
        else {
            echo 'Databankfout.';
            exit;
        }

    }

}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <title>Labo 05</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>

<main class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h1>Opgave 5</h1>
        <p class="message">You can leave me a message here!</p>

        <div>
            <label for="email">Your email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="input-text"/>
            <span class="message error"><?php echo $msgEmail; ?></span>
        </div>

        <div>
            <label for="message">Your message</label>
            <textarea name="message" id="message" rows="5" cols="40"><?php echo $message; ?></textarea>
            <span class="message error"><?php echo $msgMessage; ?></span>
        </div>

        <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit"/>
    </form>
</main>
</body>
</html>

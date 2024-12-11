<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verify specific credentials
    if ($username === "usuario" && $password === "123456789") {
        try {
            // Try to connect to MariaDB
            $host = "172.16.4.150"; // Change if necessary
            $connection = new PDO("mysql:host=$host", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Correct credentials and successful connection
            $_SESSION["logged_in"] = true;
            header("Location: conectado.php");
            exit();
        } catch (PDOException $e) {
            // Error connecting to MariaDB
            $_SESSION["error_message"] = "Error connecting to the database.";
            header("Location: index.php");
            exit();
        }
    } else {
        // Incorrect credentials
        $_SESSION["error_message"] = "Incorrect username or password.";
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <main>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" id="btnB">Log in</button>
        </form>
        <?php
        if (isset($_SESSION["error_message"])) {
            echo "<p class='error'>" . $_SESSION["error_message"] . "</p>";
            unset($_SESSION["error_message"]);
        }
        ?>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = "172.16.4.150";
$user = "usuario";
$password = "123456789";
$db = "COCHES";

try {
    // Connect with PDO
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_brand'])) {
        // Collect data
        $Nombre = trim($_POST['Nombre']);
        $Sede = trim($_POST['Sede']);
        $Fundacion = trim($_POST['Fundacion']);

        // Validate data
        if (empty($Nombre) || empty($Sede) || empty($Fundacion)) {
            $error_message = "Please complete all fields.";
        } elseif (!preg_match('/^\d{4}$/', $Fundacion)) {
            $error_message = "The foundation year must be a 4-digit number.";
        } else {
            // Prepare the query to insert
            $sql = "INSERT INTO MARCAS (Nombre, Sede, Fundacion, Estado) VALUES (:Nombre, :Sede, :Fundacion, 0)";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':Nombre', $Nombre);
            $stmt->bindParam(':Sede', $Sede);
            $stmt->bindParam(':Fundacion', $Fundacion);

            // Execute the query
            $stmt->execute();

            // Success message
            $success_message = "Brand added successfully.";
        }
    }
} catch (PDOException $e) {
    $error_message = "Error connecting or inserting: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record - BRANDS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Add Brand</h1>
    </header>
    <main>
        <form method="post">
            <label for="Nombre">Brand name:</label>
            <input type="text" id="Nombre" name="Nombre" required>

            <label for="Sede">Brand headquarters:</label>
            <input type="text" id="Sede" name="Sede" required>

            <label for="Fundacion">Year of establishment:</label>
            <input type="number" id="Fundacion" name="Fundacion" min="1000" max="9999" required>

            <br><button type="submit" name="add_brand">Add brand</button>
        </form>

        <?php
        // Display error or success messages
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>
    </main>
    <div style="text-align: center;">
    <a href="conectado.php">
    <button type="button">Back to the main page</button>
    </a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

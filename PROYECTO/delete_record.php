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

    // Query all active records from the MARCAS table (Estado = 0)
    $sql = "SELECT id, nombre FROM MARCAS WHERE Estado = 0";
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    // Fetch records
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error connecting or retrieving data: " . $e->getMessage();
}

// Process form to "delete" the record
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    try {
        // Get the brand ID to delete
        $delete_id = $_POST["delete_id"];

        // Update the Estado field to 1 (mark as deleted)
        $delete_sql = "UPDATE MARCAS SET Estado = 1 WHERE id = :id";
        $delete_stmt = $connection->prepare($delete_sql);
        $delete_stmt->bindParam(":id", $delete_id, PDO::PARAM_INT);
        $delete_stmt->execute();

        // Success message
        $success_message = "Brand successfully deleted (marked as deleted).";
    } catch (PDOException $e) {
        $error_message = "Error deleting the record: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Record - Logged In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Delete Brand Record</h1>
    </header>
    <main>
        <?php
        // Show error message if exists
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }

        // Show success message if a record was deleted
        if (isset($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>

        <form action="delete_record.php" method="POST">
            <label for="delete_id">Select Brand to Delete:</label>
            <select name="delete_id" id="delete_id">
                <option value="">-- Select a brand --</option>
                <?php
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        echo "<option value='" . $record['id'] . "'>" . htmlspecialchars($record['nombre']) . "</option>";
                    }
                }
                ?>
            </select>
            <button type="submit">Delete</button>
        </form>
    </main>
    <div style="text-align: center;">
    <a href="conectado.php">
        <button type="button">Back to Main Page</button>
    </a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

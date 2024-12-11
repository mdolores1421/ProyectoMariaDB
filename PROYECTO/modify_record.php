<?php
// Database connection
$host = "172.16.4.150";
$user = "usuario";
$password = "123456789";
$db = "COCHES";

try {
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get all records (brands) for the dropdown
    $sql = "SELECT * FROM MARCAS";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If the form was submitted to modify the record
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $name = $_POST['nombre'];
        $headquarters = $_POST['sede'];
        $foundation = $_POST['fundacion'];

        // Validate the fields
        if (empty($name) || empty($headquarters) || empty($foundation)) {
            $error_message = "All fields are required.";
        } else {
            // Update the record in the database
            $sql = "UPDATE MARCAS SET nombre = :nombre, sede = :sede, fundacion = :fundacion WHERE id = :id";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $name);
            $stmt->bindParam(':sede', $headquarters);
            $stmt->bindParam(':fundacion', $foundation);

            $stmt->execute();
            $success_message = "Record updated successfully.";
        }
    }
} catch (PDOException $e) {
    $error_message = "Error connecting or updating: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Brand</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Modify Brand</h1>
    </header>
    <main>
        <?php
        // Display error or success message
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='success'>$success_message</p>";
        }
        ?>

        <!-- Form to modify the selected brand -->
        <form method="POST">
            <label for="id">Select the brand to modify:</label>
            <select id="id" name="id" required>
                <option value="">Select a brand</option>
                <?php
                // Check that $records contains data
                if (!empty($records)) {
                    foreach ($records as $record) {
                        echo "<option value=\"" . htmlspecialchars($record['Id']) . "\">" . htmlspecialchars($record['Nombre']) . "</option>";
                    }
                } else {
                    echo "<option value=\"\">No brands available</option>";
                }
                ?>
            </select>

            <label for="nombre">New Name:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="sede">New Headquarters:</label>
            <input type="text" id="sede" name="sede" required>

            <label for="fundacion">New Foundation:</label>
            <input type="text" id="fundacion" name="fundacion" required>

            <button type="submit">Update</button>
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

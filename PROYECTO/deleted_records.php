<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

// Database configuration
$host = "172.16.4.150";
$user = "usuario";
$password = "123456789";
$db = "COCHES";

try {
    // Connect to the database
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get deleted records (Estado = 1)
    $query = "SELECT * FROM MARCAS WHERE Estado = 1";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $deleted_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error connecting or performing the query: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Records</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header>
        <h1>Deleted Records</h1>
    </header>
    <main>
        <?php
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>

        <?php if (isset($deleted_records) && count($deleted_records) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Foundation Year</th>
                        <th>Headquarters</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deleted_records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['Id']); ?></td>
                            <td><?php echo htmlspecialchars($record['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($record['Fundacion']); ?></td>
                            <td><?php echo htmlspecialchars($record['Sede']); ?></td>
                            <td>Deleted</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No deleted records.</p>
        <?php endif; ?>
    </main>
    <div style="text-align: center;">
        <a href="conectado.php">
            <button type="button">Back to Main Page</button>
        </a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

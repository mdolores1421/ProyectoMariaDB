<?php
session_start();

// Check if the session is started
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    // If not started, redirect to login
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

    // Query only active records (Estado = 0)
    $sql = "SELECT * FROM MARCAS WHERE Estado = 0";  // Only active records, excluding deleted ones
    $stmt = $connection->prepare($sql);
    $stmt->execute();

    // Fetch the records
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error connecting or fetching data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Records - Logged In</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header>
        <h1>Brand Records</h1>
    </header>
    <main>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Headquarters</th>
                    <th>Foundation Year</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($records) > 0) {
                    foreach ($records as $record) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($record['Id']) . "</td>";
                        echo "<td>" . htmlspecialchars($record['Nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($record['Sede']) . "</td>";
                        echo "<td>" . htmlspecialchars($record['Fundacion']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    </main>
    <div style="text-align: center;">
    <a href="conectado.php">
    <button type="button">Back to the main page</button>
    </a>
    </div>

<?php include 'footer.php'; ?>
</body>
</html>

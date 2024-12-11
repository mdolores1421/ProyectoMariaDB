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

// Initialize error message variable
$error_message = "";

try {
    // Connect with PDO
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the search term
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_term = htmlspecialchars($_GET['search']);

        // Query the MARCAS table where the name matches the search
        $sql = "SELECT * FROM MARCAS WHERE Nombre LIKE :search_term";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':search_term', "%$search_term%");
        $stmt->execute();

        // Get the records
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error_message = "Error connecting or retrieving data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Record</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header>
        <h1>Search Brand Records</h1>
    </header>
    <main>
        <?php
        // Display error message if exists
        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }

        // Display the search form
        echo '
            <form method="GET" action="search_record.php">
                <label for="search">Search by Name:</label>
                <input type="text" id="search" name="search" required>
                <button type="submit">Search</button>
            </form>
        ';

        // Display the search results
        if (isset($records) && count($records) > 0) {
            echo "<h2>Search Results:</h2>";
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Headquarters</th><th>Foundation</th></tr></thead>";
            echo "<tbody>";

            foreach ($records as $record) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($record['Id']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Sede']) . "</td>";
                echo "<td>" . htmlspecialchars($record['Fundacion']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } elseif (isset($records) && count($records) === 0) {
            echo "<p>No records found for '$search_term'.</p>";
        }
        ?>
    </main>
    <div style="text-align: center;">
        <a href="conectado.php">
            <button type="button">Back to Main Page</button>
        </a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

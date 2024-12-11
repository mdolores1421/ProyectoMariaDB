<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = "172.16.4.150";
$user = "user";
$password = "123456789";
$db = "COCHES";

try {
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all records
    $query = $connection->query("SELECT * FROM MARCAS");
    $marcas = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $format = $_POST['format'];

        switch ($format) {
            case "csv":
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="marcas.csv"');
                $output = fopen('php://output', 'w');
                fputcsv($output, array_keys($marcas[0])); // Headers
                foreach ($marcas as $marca) {
                    fputcsv($output, $marca);
                }
                fclose($output);
                exit();

            case "txt":
                header('Content-Type: text/plain');
                header('Content-Disposition: attachment; filename="marcas.txt"');
                foreach ($marcas as $marca) {
                    foreach ($marca as $key => $value) {
                        echo "$key: $value\n";
                    }
                    echo "--------------------\n";
                }
                exit();

            case "json":
                header('Content-Type: application/json');
                header('Content-Disposition: attachment; filename="marcas.json"');
                echo json_encode($marcas, JSON_PRETTY_PRINT);
                exit();

            default:
                echo "Unsupported format.";
                exit();
        }
    }
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Records</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Download Records</h1>
    </header>
    <main>
        <form method="post">
            <label>Select download format:</label><br>
            <input type="radio" id="csv" name="format" value="csv" required>
            <label for="csv">CSV</label><br>
            <input type="radio" id="txt" name="format" value="txt">
            <label for="txt">TXT</label><br>
            <input type="radio" id="json" name="format" value="json">
            <label for="json">JSON</label><br>
            <br>
            <button type="submit">Download</button>
        </form>
        <br>
        <div style="text-align: center;">
            <a href="conectado.php">
                <button type="button">Back to main page</button>
            </a>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>

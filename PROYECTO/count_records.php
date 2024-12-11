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
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'none';
    $filter_value = isset($_GET['filter_value']) ? trim($_GET['filter_value']) : '';
    $filter_sede = isset($_GET['filter_sede']) ? $_GET['filter_sede'] : '';

    // Count records
    $query = "SELECT COUNT(*) as total FROM MARCAS";
    $params = [];

    // Apply filters for year, name, or headquarters if applicable
    if ($filter_type === 'year' && !empty($filter_value)) {
        $query .= " WHERE Fundacion = :filter_value";
        $params[':filter_value'] = $filter_value;
    } elseif ($filter_type === 'name' && !empty($filter_value)) {
        $query .= " WHERE Nombre LIKE :filter_value";
        $params[':filter_value'] = "%$filter_value%";
    }

    // Add headquarters filter
    if (!empty($filter_sede)) {
        $query .= (strpos($query, 'WHERE') === false ? ' WHERE' : ' AND') . " Sede LIKE :filter_sede";
        $params[':filter_sede'] = "%$filter_sede%";
    }

    // Execute query to count records
    $stmt = $connection->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_records = $result['total'];

    // Get the records found
    $query = "SELECT * FROM MARCAS";

    // Reapply filters
    if ($filter_type === 'year' && !empty($filter_value)) {
        $query .= " WHERE Fundacion = :filter_value";
    } elseif ($filter_type === 'name' && !empty($filter_value)) {
        $query .= " WHERE Nombre LIKE :filter_value";
    }

    // Add headquarters filter
    if (!empty($filter_sede)) {
        $query .= (strpos($query, 'WHERE') === false ? ' WHERE' : ' AND') . " Sede LIKE :filter_sede";
    }

    // Execute query to get records
    $stmt = $connection->prepare($query);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error_message = "Error connecting or performing query: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Count Records</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header>
        <h1>Count Records</h1>
    </header>
    <main>
        <form method="get" action="count_records.php">
            <label for="filter">Filter by (optional):</label>
            <select name="filter_type" id="filter">
                <option value="none" <?php if ($filter_type === 'none') echo 'selected'; ?>>No filter</option>
                <option value="year" <?php if ($filter_type === 'year') echo 'selected'; ?>>Year of Establishment</option>
                <option value="name" <?php if ($filter_type === 'name') echo 'selected'; ?>>Name</option>
                <option value="sede" <?php if ($filter_type === 'sede') echo 'selected'; ?>>Headquarters</option>
            </select>
            <input type="text" name="filter_value" placeholder="Enter filter value" value="<?php echo htmlspecialchars($filter_value); ?>">
            
            <button type="submit">Count Records</button>
        </form>

        <?php
        if (isset($total_records)) {
            if ($filter_type === 'none' || empty($filter_value) && empty($filter_sede)) {
                echo "<p>Total records: <strong>$total_records</strong></p>";
            } else {
                echo "<p>Total records with filter: <strong>$total_records</strong></p>";
            }
        }

        if (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>

        <?php if (isset($records) && count($records) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Year of Establishment</th>
                        <th>Headquarters</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['Id']); ?></td>
                            <td><?php echo htmlspecialchars($record['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($record['Fundacion']); ?></td>
                            <td><?php echo htmlspecialchars($record['Sede']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No records found.</p>
        <?php endif; ?>
    </main>
    <div style="text-align: center;">
        <a href="conectado.php">
            <button type="button">Back to the main page</button>
        </a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

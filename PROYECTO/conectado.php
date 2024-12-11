<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connected</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome, Admin</h1>
    </header>
    <main>
        <div class="center-content">
            <a href="add_record.php"><button class="btn">Add Records</button></a>
            <a href="list_records.php"><button class="btn">List Records</button></a>
        </div>
        <div class="center-content">
            <a href="search_record.php"><button class="btn">Search Records</button></a>
            <a href="modify_record.php"><button class="btn">Modify Record</button></a>
            <a href="count_records.php"><button class="btn">Count Records</button></a>
        </div>
        <div class="center-content">
            <a href="download.php"><button class="btn">Download Table</button></a>
            <a href="delete_record.php"><button class="btn">Delete a Record</button></a>
            <a href="deleted_records.php"><button class="btn">View Deleted Records</button></a>
            <a href="disconnect.php"><button class="btn">Disconnect</button></a>
        </div>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>

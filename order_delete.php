<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['order_id']) ? $_GET['order_id'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "DELETE FROM orderdetails WHERE order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id);

    if ($stmt->execute()) {
        $query = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);
    }

    if ($stmt->execute()) {
        header('Location:order_index.php?action=deleted');
    } else {
        die('Unable to delete record.');
    }

    $action = isset($_GET['action']) ? $_GET['action'] : "";

    if ($action == 'deleted') {
        echo "<div class='alert alert-success'>Record was deleted.</div>";
    } else {
        die('Unable to delete record.');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}

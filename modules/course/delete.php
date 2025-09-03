<?php
include("../../config/db.php");

$id = $_GET['id'];

$sql = "DELETE FROM courses WHERE id=$id";
if (mysqli_query($conn, $sql)) {
    header("Location: list.php");
    exit;
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>

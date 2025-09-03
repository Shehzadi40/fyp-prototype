<?php
include("../../config/db.php");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM classrooms WHERE id=$id");
$classroom = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $classroom_id = $_POST['classroom_id'];
    $building = $_POST['building'];
    $capacity = $_POST['capacity'];
    $multimedia = $_POST['multimedia_available'];
    $slots = $_POST['available_slots'];

    $sql = "UPDATE classrooms SET 
                classroom_id='$classroom_id',
                building='$building',
                capacity='$capacity',
                multimedia_available='$multimedia',
                available_slots='$slots'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: list.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Classroom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Edit Classroom</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Classroom ID</label>
            <input type="text" name="classroom_id" value="<?= $classroom['classroom_id'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Building</label>
            <input type="text" name="building" value="<?= $classroom['building'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Capacity</label>
            <input type="number" name="capacity" value="<?= $classroom['capacity'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Multimedia Available</label>
            <select name="multimedia_available" class="form-control">
                <option value="Yes" <?= ($classroom['multimedia_available']=="Yes")?"selected":"" ?>>Yes</option>
                <option value="No" <?= ($classroom['multimedia_available']=="No")?"selected":"" ?>>No</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Available Slots</label>
            <input type="text" name="available_slots" value="<?= $classroom['available_slots'] ?>" class="form-control">
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="list.php" class="btn btn-secondary">Back</a>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

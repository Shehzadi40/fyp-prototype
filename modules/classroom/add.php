<?php
include("../../config/db.php");

if (isset($_POST['submit'])) {
    $classroom_id = $_POST['classroom_id'];
    $building = $_POST['building'];
    $capacity = $_POST['capacity'];
    $multimedia = $_POST['multimedia_available'];
    $slots = $_POST['available_slots'];

    $sql = "INSERT INTO classrooms (classroom_id, building, capacity, multimedia_available, available_slots)
            VALUES ('$classroom_id', '$building', '$capacity', '$multimedia', '$slots')";

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
    <title>Add Classroom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: "Segoe UI", sans-serif; color: #1e293b; }
        h2 { font-weight: bold; color: #1e3a8a; }
        .card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .btn-custom { border-radius: 30px; padding: 10px 20px; font-weight: bold; background: #f97316; border: none; color: #fff; }
        .btn-custom:hover { background: #ea580c; }
        .btn-secondary { border-radius: 30px; padding: 10px 20px; }
    </style>
</head>
<body class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2>Add New Classroom</h2>
        <a href="list.php" class="btn btn-secondary mt-2 mt-md-0">← Back to List</a>
    </div>

    <div class="card p-4">
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Classroom ID</label>
                <input type="text" name="classroom_id" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Building</label>
                <input type="text" name="building" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Capacity</label>
                <input type="number" name="capacity" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Multimedia Available</label>
                <select name="multimedia_available" class="form-control">
                    <option value="Yes">Yes</option>
                    <option value="No" selected>No</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Available Slots</label>
                <input type="text" name="available_slots" class="form-control" placeholder="ALL or custom slots">
            </div>
            <div class="col-12 d-flex justify-content-end flex-wrap">
                <button type="submit" name="submit" class="btn btn-custom me-2 mt-2">Save Classroom</button>
                <a href="list.php" class="btn btn-secondary mt-2">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

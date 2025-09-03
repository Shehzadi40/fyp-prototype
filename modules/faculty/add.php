<?php
include("../../config/db.php");

if (isset($_POST['submit'])) {
    $faculty_id = $_POST['faculty_id'];
    $name = $_POST['name'];
    $dept = $_POST['department'];
    $designation = $_POST['designation'];
    $preferences = $_POST['course_preferences'];
    $time = $_POST['time_preferences'];

    $sql = "INSERT INTO faculty (faculty_id, name, department, designation, course_preferences, time_preferences)
            VALUES ('$faculty_id', '$name', '$dept', '$designation', '$preferences', '$time')";

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
    <title>Add Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc; /* Light background (60%) */
            font-family: "Segoe UI", sans-serif;
            color: #1e293b;
        }
        h2 {
            font-weight: bold;
            color: #1e3a8a; /* Navy (30%) */
        }
        .card {
            border: none;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .btn-custom {
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: bold;
            background: #f97316; /* Accent (10%) */
            border: none;
            color: #fff;
        }
        .btn-custom:hover {
            background: #ea580c;
        }
        .btn-secondary {
            border-radius: 30px;
            padding: 10px 20px;
        }
    </style>
</head>
<body class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Faculty</h2>
        <a href="list.php" class="btn btn-secondary">← Back to List</a>
    </div>

    <div class="card p-4">
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Faculty ID</label>
                <input type="text" name="faculty_id" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Department</label>
                <input type="text" name="department" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Designation</label>
                <select name="designation" class="form-control" required>
                    <option value="Professor">Professor</option>
                    <option value="Associate Professor">Associate Professor</option>
                    <option value="Assistant Professor">Assistant Professor</option>
                    <option value="Lecturer">Lecturer</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Course Preferences (comma-separated)</label>
                <input type="text" name="course_preferences" class="form-control" placeholder="CS201, CS301, CS401">
            </div>
            <div class="col-12">
                <label class="form-label">Time Preferences</label>
                <input type="text" name="time_preferences" class="form-control" placeholder="Monday [08:00-12:30], Tuesday [All Slots]">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" name="submit" class="btn btn-custom me-2">Save Faculty</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

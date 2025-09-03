<?php
include("../../config/db.php");

if (isset($_POST['submit'])) {
    $code = $_POST['course_code'];
    $title = $_POST['course_title'];
    $enrollment = $_POST['enrollment'];
    $multimedia = $_POST['multimedia_required'];

    $sql = "INSERT INTO courses (course_code, course_title, enrollment, multimedia_required)
            VALUES ('$code', '$title', '$enrollment', '$multimedia')";

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
    <title>Add Course</title>
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Course</h2>
        <a href="list.php" class="btn btn-secondary">← Back to List</a>
    </div>

    <div class="card p-4">
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Course Code</label>
                <input type="text" name="course_code" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Course Title</label>
                <input type="text" name="course_title" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Enrollment</label>
                <input type="number" name="enrollment" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Multimedia Required</label>
                <select name="multimedia_required" class="form-control">
                    <option value="Yes">Yes</option>
                    <option value="No" selected>No</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button type="submit" name="submit" class="btn btn-custom me-2">Save Course</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

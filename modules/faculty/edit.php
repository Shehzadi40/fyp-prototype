<?php
include("../../config/db.php");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM faculty WHERE id=$id");
$faculty = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $faculty_id = $_POST['faculty_id'];
    $name = $_POST['name'];
    $dept = $_POST['department'];
    $designation = $_POST['designation'];
    $preferences = $_POST['course_preferences'];
    $time = $_POST['time_preferences'];

    $sql = "UPDATE faculty SET 
                faculty_id='$faculty_id',
                name='$name',
                department='$dept',
                designation='$designation',
                course_preferences='$preferences',
                time_preferences='$time'
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
    <title>Edit Faculty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Edit Faculty</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Faculty ID</label>
            <input type="text" name="faculty_id" value="<?= $faculty['faculty_id'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="<?= $faculty['name'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" value="<?= $faculty['department'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Designation</label>
            <select name="designation" class="form-control" required>
                <option <?= ($faculty['designation']=="Professor")?"selected":"" ?>>Professor</option>
                <option <?= ($faculty['designation']=="Associate Professor")?"selected":"" ?>>Associate Professor</option>
                <option <?= ($faculty['designation']=="Assistant Professor")?"selected":"" ?>>Assistant Professor</option>
                <option <?= ($faculty['designation']=="Lecturer")?"selected":"" ?>>Lecturer</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Course Preferences</label>
            <input type="text" name="course_preferences" value="<?= $faculty['course_preferences'] ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Time Preferences</label>
            <input type="text" name="time_preferences" value="<?= $faculty['time_preferences'] ?>" class="form-control">
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="list.php" class="btn btn-secondary">Back</a>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

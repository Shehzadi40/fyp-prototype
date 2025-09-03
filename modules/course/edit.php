<?php
include("../../config/db.php");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM courses WHERE id=$id");
$course = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $code = $_POST['course_code'];
    $title = $_POST['course_title'];
    $enrollment = $_POST['enrollment'];
    $multimedia = $_POST['multimedia_required'];

    $sql = "UPDATE courses SET 
                course_code='$code',
                course_title='$title',
                enrollment='$enrollment',
                multimedia_required='$multimedia'
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
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Edit Course</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Course Code</label>
            <input type="text" name="course_code" value="<?= $course['course_code'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Course Title</label>
            <input type="text" name="course_title" value="<?= $course['course_title'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Enrollment</label>
            <input type="number" name="enrollment" value="<?= $course['enrollment'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Multimedia Required</label>
            <select name="multimedia_required" class="form-control">
                <option value="Yes" <?= ($course['multimedia_required']=="Yes")?"selected":"" ?>>Yes</option>
                <option value="No" <?= ($course['multimedia_required']=="No")?"selected":"" ?>>No</option>
            </select>
        </div>
        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="list.php" class="btn btn-secondary">Back</a>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

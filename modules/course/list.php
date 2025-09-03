<?php
include("../../config/db.php");
$result = mysqli_query($conn, "SELECT * FROM courses ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Course Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: "Segoe UI", sans-serif; color: #1e293b; }
        h2 { font-weight: bold; color: #1e3a8a; }
        .btn-custom { border-radius: 30px; padding: 8px 18px; font-weight: bold; background: #f97316; border: none; color: #fff; }
        .btn-custom:hover { background: #ea580c; }
        .btn-secondary { border-radius: 30px; padding: 8px 18px; }
        .card { border: none; border-radius: 15px; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        thead { background-color: #1e3a8a; color: #fff; }
        .btn-warning, .btn-danger { border-radius: 20px; padding: 5px 12px; font-weight: 600; }
    </style>
</head>
<body class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Course Management</h2>
        <div>
            <a href="add.php" class="btn btn-custom me-2">+ Add Course</a>
            <a href="../../index.php" class="btn btn-secondary">🏠 Home</a>
        </div>
    </div>

    <div class="card p-3">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Code</th>
                    <th>Title</th>
                    <th>Enrollment</th>
                    <th>Multimedia</th>
                    <th>Instructor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['course_code'] ?></td>
                    <td><?= $row['course_title'] ?></td>
                    <td><?= $row['enrollment'] ?></td>
                    <td><?= $row['multimedia_required'] ?></td>
                    <td><?= $row['instructor_assigned'] ?? "NOT YET" ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

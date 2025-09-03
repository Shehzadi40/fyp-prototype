<?php
include("../../config/db.php");
$result = mysqli_query($conn, "SELECT * FROM faculty ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Faculty Management</title>
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
        .btn-custom {
            border-radius: 30px;
            padding: 8px 18px;
            font-weight: bold;
            background: #f97316; /* Accent (10%) */
            border: none;
            color: #fff;
        }
        .btn-custom:hover {
            background: #ea580c;
        }
        .card {
            border: none;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        table {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        thead {
            background-color: #1e3a8a; /* Navy header */
            color: #fff;
        }
        .btn-warning, .btn-danger {
            border-radius: 20px;
            padding: 5px 12px;
            font-weight: 600;
        }
    </style>
</head>
<body class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Faculty Management</h2>
        <div>
            <a href="add.php" class="btn btn-custom me-2">+ Add Faculty</a>
            <a href="../../index.php" class="btn btn-secondary">🏠 Home</a>
        </div>
    </div>

    <div class="card p-3">
        <table class="table table-bordered table-hover mb-0">
            <thead>
                <tr>
                    <th>Faculty ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Preferences</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['faculty_id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['department'] ?></td>
                    <td><?= $row['designation'] ?></td>
                    <td><?= $row['course_preferences'] ?></td>
                    <td><?= $row['time_preferences'] ?></td>
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

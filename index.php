<?php include("config/db.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Lecture Scheduler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc; /* light (60%) */
            font-family: "Segoe UI", sans-serif;
            color: #1e293b;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .header h1 {
            font-weight: bold;
            color: #1e3a8a; /* navy (30%) */
        }
        .btn-custom {
            border-radius: 40px;
            padding: 15px 25px;
            font-weight: bold;
            background: #f97316; /* accent (10%) */
            border: none;
            color: #fff;
            width: 100%;
            font-size: 1.2rem;
            transition: transform 0.2s, background 0.2s;
        }
        .btn-custom:hover {
            background: #ea580c;
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="container py-5 d-flex flex-column align-items-center">

    <div class="header">
        <h1>Automatic Lecture TimeTable System</h1>
    </div>

    <div class="row w-100 g-3 text-center" style="max-width: 600px;">
        <div class="col-12">
            <a href="modules/faculty/list.php" class="btn btn-custom">Faculty Management</a>
        </div>
        <div class="col-12">
            <a href="modules/course/list.php" class="btn btn-custom">Course Management</a>
        </div>
        <div class="col-12">
            <a href="modules/classroom/list.php" class="btn btn-custom">Classroom Management</a>
        </div>
        <div class="col-12">
            <a href="modules/schedule/view.php" class="btn btn-custom">Lecture Scheduling</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

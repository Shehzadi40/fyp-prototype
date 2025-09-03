<?php
// modules/schedule/view.php
include("../../config/db.php");

$weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
$slots_order = [
    '08:00-09:30',
    '09:30-11:00',
    '11:00-12:30',
    '01:30-03:00',
    '03:00-04:30'
];

// Fetch schedule into matrix [day][slot] => array of entries (usually 0 or 1)
$schedule = [];
foreach ($weekdays as $d) {
    foreach ($slots_order as $s) {
        $schedule[$d][$s] = [];
    }
}

$sql = "SELECT s.*, c.course_code, c.course_title, f.name AS faculty_name, cr.classroom_id
        FROM schedule s
        JOIN courses c ON s.course_id = c.id
        JOIN faculty f ON s.faculty_id = f.id
        JOIN classrooms cr ON s.classroom_id = cr.id
        ORDER BY FIELD(s.weekday, 'Monday','Tuesday','Wednesday','Thursday','Friday'), 
                 FIELD(s.slot, '08:00-09:30','09:30-11:00','11:00-12:30','01:30-03:00','03:00-04:30')";
$res = mysqli_query($conn, $sql);
while ($r = mysqli_fetch_assoc($res)) {
    $schedule[$r['weekday']][$r['slot']][] = $r;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lecture Timetable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        td { vertical-align: top; min-width: 200px; }
    </style>
</head>
<body class="container mt-5">
    <h2 class="mb-4">Lecture Timetable (Generated)</h2>

    <div class="mb-3">
        <a href="generate.php" class="btn btn-warning">Re-generate Schedule</a>
        <a href="../../index.php" class="btn btn-secondary">Home</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Slot / Day</th>
                <?php foreach ($weekdays as $d) echo "<th>$d</th>"; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slots_order as $slot): ?>
            <tr>
                <th class="table-secondary"><?= $slot ?></th>
                <?php foreach ($weekdays as $day): ?>
                <td>
                    <?php
                    $items = $schedule[$day][$slot];
                    if (empty($items)) {
                        echo "<small class='text-muted'>-</small>";
                    } else {
                        foreach ($items as $it) {
                            // display course code, faculty, classroom
                            echo "<div class='mb-2 p-2 border rounded'>";
                            echo "<strong>" . htmlspecialchars($it['course_code']) . "</strong><br>";
                            echo htmlspecialchars($it['course_title']) . "<br>";
                            echo "<em>" . htmlspecialchars($it['faculty_name']) . "</em><br>";
                            echo "<small>Room: " . htmlspecialchars($it['classroom_id']) . "</small>";
                            echo "</div>";
                        }
                    }
                    ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

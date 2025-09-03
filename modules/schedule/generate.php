<?php
// modules/schedule/generate.php
// Full strict scheduling engine
include("../../config/db.php");

/**
 * Settings
 */
$weekdays = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
$slots_order = [
    '08:00-09:30',
    '09:30-11:00',
    '11:00-12:30',
    '01:30-03:00',
    '03:00-04:30'
];

// mapping common time text to slot keys (loose matching)
function normalize_time_token($t) {
    $t = strtolower(trim($t));
    $t = str_replace([' ','.','–','—','–','to','-'], ['','','','-','-','-','-'], $t);
    // normalize am/pm variants
    $t = str_replace(['am','pm'], ['am','pm'], $t);
    // try direct forms
    $map = [
        '08:00-09:30' => ['08:00-09:30','8:00-9:30','08:00am-09:30am','8:00am-9:30am','08:00-09:30am'],
        '09:30-11:00' => ['09:30-11:00','9:30-11:00','09:30am-11:00am'],
        '11:00-12:30' => ['11:00-12:30','11:00am-12:30pm','11:00-12:30pm'],
        '01:30-03:00' => ['13:30-15:00','01:30-03:00','01:30pm-03:00pm','1:30pm-3:00pm','01:30-03:00pm'],
        '03:00-04:30' => ['03:00-04:30','03:00pm-04:30pm','3:00pm-4:30pm']
    ];
    foreach ($map as $slot => $variants) {
        foreach ($variants as $v) {
            $v2 = str_replace([' ','.','–','—','–','to'], ['','','','-','-','-'], strtolower($v));
            if ($t === $v2) return $slot;
        }
    }
    // if contains hour range like "0800-0930"
    if (preg_match('/(\d{1,2}:\d{2}).*?(\d{1,2}:\d{2})/', $t, $m)) {
        $candidate = $m[1] . '-' . $m[2];
        // standardize
        foreach ($map as $slot => $variants) {
            foreach ($variants as $v) {
                if (strpos(str_replace([' ','.','–','—'], '', strtolower($v)), str_replace([' ','.','–','—'], '', strtolower($candidate))) !== false) {
                    return $slot;
                }
            }
        }
    }
    return null;
}

/**
 * Parse faculty time_preferences string into availability array:
 * returns array of weekday => [slotKey => true]
 * If time_preferences empty or 'ALL' treat as all slots all weekdays.
 *
 * Accepts formats like:
 *  - "Monday [All Slots], Tuesday [08:00-09:30, 09:30-11:00]"
 *  - "All Days [09:30-11:00]"
 *  - "Monday [All Slots]"
 */
function parse_time_preferences($text, $weekdays, $slots_order) {
    // default: all available
    $availability = [];
    foreach ($weekdays as $d) {
        $availability[$d] = array_fill_keys($slots_order, true);
    }

    if (!$text || trim($text) === '') {
        return $availability;
    }

    $original = $text;
    $text = trim($text);
    $text_lower = strtolower($text);
    // If mention All Days or All or All Slots
    if (stripos($text_lower, 'all days') !== false || strtoupper(trim($text)) === 'ALL' || stripos($text_lower, 'all slots') !== false && stripos($text_lower, 'all days') !== false) {
        // find slots inside bracket
        if (preg_match('/\[(.*?)\]/', $text, $m)) {
            $inner = $m[1];
            if (stripos($inner, 'all') !== false) {
                // all slots all days -> already default
                return $availability;
            } else {
                // parse slots given to apply same slots to all days
                $chosen = array_fill_keys($slots_order, false);
                $parts = preg_split('/[,;]+/', $inner);
                foreach ($parts as $p) {
                    $slot = normalize_time_token($p);
                    if ($slot) $chosen[$slot] = true;
                }
                foreach ($weekdays as $d) {
                    $availability[$d] = $chosen;
                }
                return $availability;
            }
        } else {
            return $availability;
        }
    }

    // parse patterns like "Tuesday [11:00am – 12:30pm, 01:30pm – 03:00pm], Thursday [11:00am – 12:30pm]"
    // split by comma that separate day-blocks but careful: blocks contain commas; better split by '],' or '];'
    $blocks = preg_split('/\]\s*,\s*/', $text);
    // reset availability to none then enable parsed slots
    foreach ($weekdays as $d) $availability[$d] = array_fill_keys($slots_order, false);

    foreach ($blocks as $block) {
        // ensure trailing bracket
        if (strpos($block, ']') === false) {
            $block .= ']';
        }
        // get day
        if (preg_match('/([A-Za-z ]+)\s*\[(.*?)\]/', $block, $m)) {
            $daytxt = trim($m[1]);
            $slotsinner = trim($m[2]);
            // day could be "All Days"
            if (stripos($daytxt, 'all') !== false) {
                $target_days = $weekdays;
            } else {
                // daytxt could be "Monday" or "Mon" etc.
                $candidate = null;
                foreach ($weekdays as $wd) {
                    if (stripos($daytxt, strtolower($wd)) !== false || stripos(strtolower($wd), strtolower($daytxt)) !== false) {
                        $candidate = $wd; break;
                    }
                }
                $target_days = $candidate ? [$candidate] : [];
            }

            if (stripos($slotsinner, 'all') !== false) {
                foreach ($target_days as $td) $availability[$td] = array_fill_keys($slots_order, true);
            } else {
                // parse slots list
                $parts = preg_split('/[,;]+/', $slotsinner);
                foreach ($parts as $p) {
                    $slot = normalize_time_token($p);
                    if ($slot) {
                        foreach ($target_days as $td) $availability[$td][$slot] = true;
                    }
                }
            }
        } else {
            // fallback: maybe just a list of slots (apply to all days)
            $parts = preg_split('/[,;]+/', $block);
            foreach ($parts as $p) {
                $slot = normalize_time_token($p);
                if ($slot) {
                    foreach ($weekdays as $wd) $availability[$wd][$slot] = true;
                }
            }
        }
    }

    return $availability;
}

/**
 * Helper: designation rank (higher => higher priority)
 */
function designation_rank($designation) {
    $map = [
        'Professor' => 4,
        'Associate Professor' => 3,
        'Assistant Professor' => 2,
        'Lecturer' => 1
    ];
    return isset($map[$designation]) ? $map[$designation] : 0;
}

/**
 * Clear old schedule
 */
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");
mysqli_query($conn, "TRUNCATE TABLE schedule");
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");

/**
 * Fetch classrooms and prepare sorted lists
 */
$classrooms_res = mysqli_query($conn, "SELECT * FROM classrooms ORDER BY capacity ASC");
$classrooms = [];
while ($r = mysqli_fetch_assoc($classrooms_res)) {
    $classrooms[] = $r;
}

/**
 * Build an availability map for classrooms: classroom_id => weekday => slot => true/false
 * initially all available unless available_slots restricts it (we assume 'ALL' means all).
 */
$classroom_avail = [];
foreach ($classrooms as $c) {
    $cid = $c['id'];
    $classroom_avail[$cid] = [];
    foreach ($weekdays as $d) {
        $classroom_avail[$cid][$d] = array_fill_keys($slots_order, true);
    }
    // if available_slots is not 'ALL' and not empty, we will try to parse simple text "Monday [All Slots]" style.
    if ($c['available_slots'] && strtoupper(trim($c['available_slots'])) !== 'ALL') {
        $parsed = parse_time_preferences($c['available_slots'], $weekdays, $slots_order);
        // override
        $classroom_avail[$cid] = $parsed;
    }
}

/**
 * Prepare faculty list and their availability
 */
$faculty_res = mysqli_query($conn, "SELECT * FROM faculty ORDER BY created_at ASC");
$faculty = [];
$faculty_avail = [];
while ($f = mysqli_fetch_assoc($faculty_res)) {
    $faculty[$f['id']] = $f;
    $faculty_avail[$f['id']] = parse_time_preferences($f['time_preferences'], $weekdays, $slots_order);
}

/**
 * Build schedule occupancy trackers
 * - faculty_bookings[faculty_id][weekday][slot] = true if booked
 * - classroom_bookings[classroom_id][weekday][slot] = true if booked
 */
$faculty_bookings = [];
$classroom_bookings = [];
foreach ($faculty as $fid => $f) {
    foreach ($weekdays as $d) {
        foreach ($slots_order as $s) {
            $faculty_bookings[$fid][$d][$s] = false;
        }
    }
}
foreach ($classrooms as $c) {
    $cid = $c['id'];
    foreach ($weekdays as $d) {
        foreach ($slots_order as $s) {
            $classroom_bookings[$cid][$d][$s] = false;
        }
    }
}

/**
 * Fetch courses and sort them by enrollment DESC so large courses get priority for big rooms
 */
$courses_res = mysqli_query($conn, "SELECT * FROM courses ORDER BY enrollment DESC");
$assigned_count = 0;
$skipped = [];

/**
 * For each course, find candidate faculties who prefer it, apply rules, and try to schedule.
 */
while ($course = mysqli_fetch_assoc($courses_res)) {
    $course_id = $course['id'];
    $course_code = strtoupper(trim($course['course_code']));
    $enrollment = intval($course['enrollment']);
    $needs_multimedia = (strtolower($course['multimedia_required']) === 'yes');

    // find faculties who included this course in preferences
    $candidates = [];
    foreach ($faculty as $fid => $f) {
        $prefs = strtoupper($f['course_preferences'] ?? '');
        // split by commas/spaces; search for exact token presence
        $tokens = preg_split('/[,\s]+/', $prefs, -1, PREG_SPLIT_NO_EMPTY);
        $found = false;
        foreach ($tokens as $t) {
            if ($t === $course_code) { $found = true; break; }
            // allow tokens like "CS201," etc.
            if (trim(str_replace([',',';'], '', $t)) === $course_code) { $found = true; break; }
        }
        if ($found) {
            $candidates[] = $f;
        }
    }

    if (count($candidates) === 0) {
        // No preferences found; skip (supervisor did not require auto-assign in absence of faculty preference)
        $skipped[] = $course_code . " (No faculty preference)";
        continue;
    }

    // If there is only one candidate assign that one (still must check availability & classroom)
    // If multiple candidates: sort by designation rank desc, then created_at asc
    usort($candidates, function($a, $b) {
        $ra = designation_rank($a['designation']);
        $rb = designation_rank($b['designation']);
        if ($ra !== $rb) return $rb - $ra; // higher rank first
        // earlier created_at gets priority
        $ta = strtotime($a['created_at']); $tb = strtotime($b['created_at']);
        return $ta - $tb;
    });

    $scheduled = false;

    // For each candidate faculty in priority order, try to find a slot + classroom
    foreach ($candidates as $cand) {
        $fid = $cand['id'];
        $avail = $faculty_avail[$fid];

        // iterate weekdays and slots in fixed order and find first acceptable slot and classroom
        foreach ($weekdays as $day) {
            foreach ($slots_order as $slot) {
                // check faculty availability for this day/slot
                if (empty($avail[$day][$slot]) || !$avail[$day][$slot]) continue;
                // check faculty not already booked at this time
                if ($faculty_bookings[$fid][$day][$slot]) continue;

                // Now find classroom: smallest capacity >= enrollment, multimedia requirement match, classroom free at that time, classroom availability supports that slot
                $chosen_classroom = null;
                foreach ($classrooms as $cl) {
                    $cid = $cl['id'];
                    if (intval($cl['capacity']) < $enrollment) continue;
                    if ($needs_multimedia && strtolower($cl['multimedia_available']) !== 'yes') continue;
                    // classroom availability (if classroom_avail restricts)
                    if (isset($classroom_avail[$cid][$day][$slot]) && !$classroom_avail[$cid][$day][$slot]) continue;
                    // classroom not already booked
                    if ($classroom_bookings[$cid][$day][$slot]) continue;
                    // choose the first (classrooms sorted ascending by capacity earlier) => gives smallest that fits
                    $chosen_classroom = $cl;
                    break;
                }

                if ($chosen_classroom) {
                    // insert into schedule table
                    $classroom_id_db = intval($chosen_classroom['id']);
                    $insert_sql = "INSERT INTO schedule (course_id, faculty_id, classroom_id, weekday, slot) VALUES ($course_id, $fid, $classroom_id_db, '".mysqli_real_escape_string($conn,$day)."', '".mysqli_real_escape_string($conn,$slot)."')";
                    if (mysqli_query($conn, $insert_sql)) {
                        // mark bookings
                        $faculty_bookings[$fid][$day][$slot] = true;
                        $classroom_bookings[$classroom_id_db][$day][$slot] = true;
                        // update course assigned instructor
                        mysqli_query($conn, "UPDATE courses SET instructor_assigned = $fid WHERE id = $course_id");
                        $assigned_count++;
                        $scheduled = true;
                    } else {
                        // DB error - continue trying other slots
                        // but don't break; try next slot
                    }
                    break 2; // break out candidate loop because course scheduled
                }
            }
        }
        // if this candidate couldn't be scheduled, continue to next candidate
    }

    if (!$scheduled) {
        $skipped[] = $course_code . " (No available slot/classroom for preferred faculty)";
    }
}

/**
 * Done
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Schedule Generation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Schedule Generation Result</h2>
    <p><strong>Assigned courses:</strong> <?= $assigned_count ?></p>
    <?php if (!empty($skipped)) { ?>
        <div class="alert alert-warning">
            <strong>Skipped / Unscheduled Courses:</strong>
            <ul>
            <?php foreach ($skipped as $s) echo "<li>" . htmlspecialchars($s) . "</li>"; ?>
            </ul>
        </div>
    <?php } else { ?>
        <div class="alert alert-success">All courses assigned successfully (according to preferences & availability).</div>
    <?php } ?>

    <a href="view.php" class="btn btn-primary">View Generated Timetable</a>
    <a href="../../index.php" class="btn btn-secondary">Home</a>
</body>
</html>

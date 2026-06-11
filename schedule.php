<?php
require 'connect.php';
include 'header.php';

// Handle registration submission
if(isset($_POST['btnreg'])){
    $schedule_id = (int)$_POST['txtscid'];
    $student_id  = (int)$_POST['txtsid']; 
    $type        = $cn->real_escape_string($_POST['txttype']);
    $day_of_week = (int)$_POST['txtdow'];
    $start_time  = $cn->real_escape_string($_POST['txtst']);
    $end_time    = $cn->real_escape_string($_POST['txtet']);

    $sql1 = "INSERT INTO schedule (schedule_id, student_id, type, day_of_week, start_time, end_time) 
             VALUES ('$schedule_id', '$student_id', '$type', '$day_of_week', '$start_time', '$end_time')";
    
    if($cn->query($sql1)){
        echo "<script>alert('Schedule registered successfully'); window.location='schedule.php';</script>";
    } else {
        echo "<script>alert('Error: " . $cn->error . "');</script>";
    }
}

// Handle inline update submission
if(isset($_POST['btnupdate'])){
    $id          = $cn->real_escape_string($_POST['txtid']);
    $schedule_id = (int)$_POST['txtscid'];
    $student_id  = (int)$_POST['txtsid']; 
    $type        = $cn->real_escape_string($_POST['txttype']);
    $day_of_week = (int)$_POST['txtdow'];
    $start_time  = $cn->real_escape_string($_POST['txtst']);
    $end_time    = $cn->real_escape_string($_POST['txtet']);

    $sql_update = "UPDATE schedule SET 
                   schedule_id = '$schedule_id', 
                   student_id = '$student_id', 
                   type = '$type', 
                   day_of_week = '$day_of_week', 
                   start_time = '$start_time', 
                   end_time = '$end_time' 
                   WHERE id = '$id'";
    
    if($cn->query($sql_update)){
        echo "<script>alert('Schedule updated successfully'); window.location='schedule.php';</script>";
    } else {
        echo "<script>alert('Error updating schedule: " . $cn->error . "');</script>";
    }
}

$students_rs = $cn->query("SELECT student_id, fname, lname FROM student_registration ORDER BY lname ASC, fname ASC");
$student_map = [];
$dropdown_options = [];

if ($students_rs) {
    while($st = $students_rs->fetch_assoc()) {
        $display_name = $st['lname'] . ", " . $st['fname'];
        $student_map[$display_name] = $st['student_id'];
        $dropdown_options[] = [
            'id' => $st['student_id'],
            'display_name' => $display_name
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Schedule Management</title>
    <style>
        :root {
            --bg: #0d1117;
            --surface: #161b22;
            --border: #30363d;
            --text: #c9d1d9;
            --text-muted: #8b949e;
            --accent: #58a6ff;
            --accent-hover: #1f6feb;
            --danger: #f85149;
            --danger-hover: #da3633;
            --success: #238636;
            --success-hover: #2ea043;
            --radius: 12px;
        }
        body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        color: var(--text);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        -webkit-font-smoothing: antialiased;
        
        /* 4-way moving mesh background gradient configuration */
        background-image: linear-gradient(10deg, #06090f, #08061a, #ff5f5f);
        background-size: 400% 400%;
        animation: movingGradient 15s ease infinite;
        background-attachment: fixed;
    }
        .container-box {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        #open_modal {
            background-color: #ffcc6c;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        #open_modal:hover { background-color: var(--accent-hover); color:white; }
        
        table { 
            border-collapse: collapse; 
            width: 100%; 
            text-align: left; 
            box-shadow: 0 4px 15px rgba(255, 209, 3, 0.4);
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid #ffcc6c;
        }
        th { 
            background-color: var(--surface); 
            border-bottom: 2px solid #ffcc6c; 
            padding: 14px; 
            color: white; 
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td { 
            border-bottom: 1px solid var(--border); 
            padding: 14px; 
            color: var(--text); 
            background-color: #10141b; 
            font-size: 0.95rem;
        }
        tr:hover td { background-color: var(--surface); }
        
        /* Edit button modified into a functional button cursor action pointer */
        .btn-edit-trigger { 
            background-color: var(--success); 
            color: white; 
            padding: 6px 12px; 
            border-radius: 4px; 
            text-decoration: none; 
            margin-right: 5px; 
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .btn-edit-trigger:hover { background-color: var(--success-hover); }
        
        #delete { 
            background-color: var(--danger); 
            color: white; 
            padding: 6px 12px; 
            border-radius: 4px; 
            text-decoration: none; 
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
        }
        #delete:hover { background-color: var(--danger-hover); }
        
        dialog {
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 0;
            width: 100%;
            max-width: 480px;
            background-color: var(--surface);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            color: var(--text);
        }
        dialog::backdrop { background: rgba(0, 0, 0, 0.7); }
        
        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h2 {
            margin: 0;
            font-size: 1.25rem;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .close-modal-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
        }
        .close-modal-btn:hover { color: var(--danger); }
        
        .modal-form {
            padding: 25px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .form-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-row label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .form-row input, .form-row select {
            width: 100%;
            box-sizing: border-box;
            background-color: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 12px 14px;
            font-size: 0.95rem;
            border-radius: 6px;
            outline: none;
        }
        .form-row input:focus, .form-row select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.15);
        }
        .form-row input[readonly] {
            background-color: #090d13;
            color: var(--text-muted);
            border-style: dashed;
            cursor: not-allowed;
        }
        .submit-btn {
            background-color: var(--success);
            color: white;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s;
        }
        .submit-btn:hover { background-color: var(--success-hover); }
        .submit-btn.update-accent { background-color: var(--accent); }
        .submit-btn.update-accent:hover { background-color: var(--accent-hover); }
        .no-records { text-align: center; padding: 30px; color: var(--text-muted); font-style: italic; }
    </style>
</head>
<body>

<div class="container-box">
    
    <div class="action-bar">
        <button id="open_modal">Add New Schedule</button>
    </div>

    <dialog id="modal">
        <div class="modal-header">
            <h2>Register Schedule</h2>
            <button class="close-modal-btn" id="closemodal">&times;</button>
        </div>
        <form action="schedule.php" method="post" class="modal-form" id="regForm">
            <div class="form-row">
                <label>Schedule ID</label>
                <input type="number" name="txtscid" placeholder="e.g., 101" required>
            </div>
            <div class="form-row">
                <label>Student Name</label>
                <select id="student_name_select" required>
                    <option value="">-- Choose Student Name --</option>
                    <?php foreach($dropdown_options as $st): ?>
                        <option value="<?= htmlspecialchars($st['display_name']) ?>">
                            <?= htmlspecialchars($st['display_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label>Student Number</label>
                <input type="text" name="txtsid" id="student_id_display" placeholder="Select student name above..." readonly required>
            </div>
            <div class="form-row">
                <label>Type of Work</label>
                <input type="text" name="txttype" placeholder="e.g., Lab Assistant" required>
            </div>
            <div class="form-row">
                <label>Days of Work (Numeric Format)</label>
                <input type="number" name="txtdow" placeholder="e.g., 123 (Mon/Tue/Wed)" required>
            </div>
            <div class="form-row">
                <label>Start Time</label>
                <input type="time" name="txtst" required>
            </div>
            <div class="form-row">
                <label>End Time</label>
                <input type="time" name="txtet" required>
            </div>
            <button type="submit" name="btnreg" class="submit-btn">Register Schedule</button>
        </form>
    </dialog>

    <dialog id="editModal">
        <div class="modal-header">
            <h2>Update Student Schedule</h2>
            <button class="close-modal-btn" id="closeEditModal">&times;</button>
        </div>
        <form action="schedule.php" method="post" class="modal-form" id="editForm">
            <input type="hidden" name="txtid" id="edit_id">
            
            <div class="form-row">
                <label>Schedule ID</label>
                <input type="number" name="txtscid" id="edit_scid" required>
            </div>
            <div class="form-row">
                <label>Student Name</label>
                <select id="edit_student_name_select" required>
                    <option value="">-- Choose Student Name --</option>
                    <?php foreach($dropdown_options as $st): ?>
                        <option value="<?= htmlspecialchars($st['display_name']) ?>">
                            <?= htmlspecialchars($st['display_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-row">
                <label>Student Number</label>
                <input type="text" name="txtsid" id="edit_student_id_display" readonly required>
            </div>
            <div class="form-row">
                <label>Type of Work</label>
                <input type="text" name="txttype" id="edit_type" required>
            </div>
            <div class="form-row">
                <label>Days of Work</label>
                <input type="number" name="txtdow" id="edit_dow" required>
            </div>
            <div class="form-row">
                <label>Start Time</label>
                <input type="time" name="txtst" id="edit_st" required>
            </div>
            <div class="form-row">
                <label>End Time</label>
                <input type="time" name="txtet" id="edit_et" required>
            </div>
            <button type="submit" name="btnupdate" class="submit-btn update-accent">Update Schedule Information</button>
        </form>
    </dialog>

    <table>
        <thead>
            <tr>
                <th>Student Schedule ID</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Type of Work</th>
                <th>Days of Work</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th style="text-align: center; width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $query_str = "SELECT s.*, r.fname, r.lname 
                          FROM schedule s 
                          LEFT JOIN student_registration r ON s.student_id = r.student_id";
            $rs2 = $cn->query($query_str);
            
            if($rs2 && $rs2->num_rows > 0){
                while($row = $rs2->fetch_array()){ 
                    $fullname = $row['lname'] . ", " . $row['fname'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['schedule_id']) ?></td>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><strong><?= htmlspecialchars($fullname) ?></strong></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td><?= htmlspecialchars($row['day_of_week']) ?></td>
                        <td><?= date("g:i A", strtotime($row['start_time'])) ?></td>
                        <td><?= date("g:i A", strtotime($row['end_time'])) ?></td>
                        <td style="text-align: center;">
                            <button class="btn-edit-trigger" 
                                    data-id="<?= $row['id'] ?>"
                                    data-scid="<?= $row['schedule_id'] ?>"
                                    data-fullname="<?= htmlspecialchars($fullname) ?>"
                                    data-sid="<?= $row['student_id'] ?>"
                                    data-type="<?= htmlspecialchars($row['type']) ?>"
                                    data-dow="<?= $row['day_of_week'] ?>"
                                    data-st="<?= $row['start_time'] ?>"
                                    data-et="<?= $row['end_time'] ?>">
                                Edit
                            </button>
                            <a onclick="return confirm('Are you sure you want to delete this schedule?')" href="delete-schedule.php?id=<?= $row['id'] ?>" id="delete">Delete</a>
                        </td>
                    </tr>
                    <?php 
                } 
            } else {
                echo "<tr><td colspan='8' class='no-records'>No active schedules found in database logs.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    const studentDataMap = <?= json_encode($student_map); ?>;

    const modal = document.getElementById('modal');
    const editModal = document.getElementById('editModal');
    const openBtn = document.getElementById('open_modal');
    const closeBtn = document.getElementById('closemodal');
    const closeEditBtn = document.getElementById('closeEditModal');

    openBtn.addEventListener('click', () => { modal.showModal(); });
    closeBtn.addEventListener('click', () => { modal.close(); });
    
    closeEditBtn.addEventListener('click', () => { editModal.close(); });

    window.addEventListener('click', (e) => { 
        if (e.target === modal) modal.close(); 
        if (e.target === editModal) editModal.close(); 
    });

    document.getElementById('student_name_select').addEventListener('change', function() {
        const selectedName = this.value;
        const idField = document.getElementById('student_id_display');
        idField.value = (selectedName && studentDataMap[selectedName]) ? studentDataMap[selectedName] : '';
    });

    document.getElementById('edit_student_name_select').addEventListener('change', function() {
        const selectedName = this.value;
        const idField = document.getElementById('edit_student_id_display');
        idField.value = (selectedName && studentDataMap[selectedName]) ? studentDataMap[selectedName] : '';
    });

    document.querySelectorAll('.btn-edit-trigger').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_scid').value = this.dataset.scid;
            document.getElementById('edit_student_name_select').value = this.dataset.fullname;
            document.getElementById('edit_student_id_display').value = this.dataset.sid;
            document.getElementById('edit_type').value = this.dataset.type;
            document.getElementById('edit_dow').value = this.dataset.dow;
            document.getElementById('edit_st').value = this.dataset.st;
            document.getElementById('edit_et').value = this.dataset.et;
            
            editModal.showModal();
        });
    });
</script>

</body>
</html>
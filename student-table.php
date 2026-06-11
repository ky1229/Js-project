<?php
require 'connect.php';

// Handle Student Registration
if(isset($_POST['btnreg'])){
    $student_id = $cn->real_escape_string($_POST['txtsid']);
    $fname      = $cn->real_escape_string($_POST['txtfname']);
    $lname      = $cn->real_escape_string($_POST['txtlname']);
    $csection   = $cn->real_escape_string($_POST['txtcsection']);
    $ylevel     = $cn->real_escape_string($_POST['txtylevel']);

    $sql1 = "INSERT INTO student_registration (student_id, fname, lname, course_section, year_level) 
             VALUES ('$student_id', '$fname', '$lname', '$csection', '$ylevel')";
    $cn->query($sql1);
    echo "<script>alert('Student registered successfully'); window.location='student-table.php';</script>";
}

// Handle Student Inline Update Update 
if(isset($_POST['btnupdate'])){
    $id         = $cn->real_escape_string($_POST['txtid']);
    $student_id = $cn->real_escape_string($_POST['txtsid']);
    $fname      = $cn->real_escape_string($_POST['txtfname']);
    $lname      = $cn->real_escape_string($_POST['txtlname']);
    $csection   = $cn->real_escape_string($_POST['txtcsection']);
    $ylevel     = $cn->real_escape_string($_POST['txtylevel']);

    $sql_update = "UPDATE student_registration SET 
                   student_id = '$student_id', 
                   fname = '$fname', 
                   lname = '$lname', 
                   course_section = '$csection', 
                   year_level = '$ylevel' 
                   WHERE id = '$id'";
    
    if($cn->query($sql_update)){
        echo "<script>alert('Student profile updated successfully'); window.location='student-table.php';</script>";
    } else {
        echo "<script>alert('Error updating student: " . $cn->error . "');</script>";
    }
}

$search = isset($_GET['search']) ? $cn->real_escape_string($_GET['search']) : '';
$filter_year = isset($_GET['filter_year']) ? $cn->real_escape_string($_GET['filter_year']) : '';
$filter_course = isset($_GET['filter_course']) ? $cn->real_escape_string($_GET['filter_course']) : '';
$filter_type = isset($_GET['filter_type']) ? $cn->real_escape_string($_GET['filter_type']) : '';

$where = [];
if($search != '') $where[] = "(sr.fname LIKE '%$search%' OR sr.lname LIKE '%$search%' OR sr.student_id LIKE '%$search%')";
if($filter_year != '') $where[] = "sr.year_level = '$filter_year'";
if($filter_course != '') $where[] = "sr.course_section = '$filter_course'";
if($filter_type != '') $where[] = "sc.type = '$filter_type'";

$sql2 = "SELECT DISTINCT sr.* FROM student_registration sr LEFT JOIN schedule sc ON sr.student_id = sc.student_id";
if(count($where) > 0) $sql2 .= " WHERE " . implode(" AND ", $where);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Students</title>
    <style>
        :root {
            --bg: #0d1117;
            --surface: #161b22;
            --border: #30363d;
            --text: #c9d1d9;
            --text-muted: #8b949e;
            --accent: #0091ff;
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
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        #open_modal:hover { background-color: var(--accent-hover); color:white;}

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: 0 4px 25px rgba(255, 209, 3, 0.4);
        }
        table {
            border-collapse: collapse;
            width: 100%;
            text-align: left;
            border: 1px solid #ffcc6c;
        }
        th { 
            background-color: var(--surface); 
            border-bottom: 2px solid #ffcc6c; 
            padding: 16px; 
            color: white; 
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td { 
            border-bottom: 1px solid var(--border); 
            padding: 16px; 
            color: var(--text); 
            background-color: #10141b; 
            font-size: 0.95rem;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: var(--surface); }
        
        .btn-edit-trigger { 
            background-color: var(--success); 
            color: white; 
            padding: 6px 14px; 
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
            padding: 6px 14px; 
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
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
            color: var(--text);
        }
        dialog::backdrop { background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); }
        
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
        .form-row input {
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
        .form-row input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0, 145, 255, 0.15);
        }
        
        .submit-btn {
            background-color: var(--success); 
            color: white; 
            width: 100%;
            border-radius: 6px; 
            margin-top: 10px;
            padding: 12px;
            font-size: 1rem; 
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .submit-btn:hover { background-color: var(--success-hover); }
        .submit-btn.update-accent { background-color: var(--accent); }
        .submit-btn.update-accent:hover { background-color: var(--accent-hover); }
        .no-results { text-align: center; padding: 30px; color: var(--text-muted); font-style: italic; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container-box">
    <div class="action-bar">
        <button id="open_modal">Register New Student</button>
    </div>

    <dialog id="modal">
        <div class="modal-header">
            <h2>Student Registration</h2>
            <button class="close-modal-btn" id="closemodal">&times;</button>
        </div>
        <form action="student-table.php" method="post" class="modal-form" id="regForm">
            <div class="form-row">
                <label>Student ID</label>
                <input type="number" name="txtsid" required>
            </div>
            <div class="form-row">
                <label>Firstname</label>
                <input type="text" name="txtfname" required>
            </div>
            <div class="form-row">
                <label>Lastname</label>
                <input type="text" name="txtlname" required>
            </div>
            <div class="form-row">
                <label>Course and Section</label>
                <input type="text" name="txtcsection" required>
            </div>
            <div class="form-row">
                <label>Year Level</label>
                <input type="text" name="txtylevel" required>
            </div>
            <button type="submit" name="btnreg" class="submit-btn">Register Student</button>
        </form>
    </dialog>

    <dialog id="editModal">
        <div class="modal-header">
            <h2>Update Student Credentials</h2>
            <button class="close-modal-btn" id="closeEditModal">&times;</button>
        </div>
        <form action="student-table.php" method="post" class="modal-form" id="editForm">
            <input type="hidden" name="txtid" id="edit_id">
            
            <div class="form-row">
                <label>Student ID</label>
                <input type="number" name="txtsid" id="edit_sid" required>
            </div>
            <div class="form-row">
                <label>Firstname</label>
                <input type="text" name="txtfname" id="edit_fname" required>
            </div>
            <div class="form-row">
                <label>Lastname</label>
                <input type="text" name="txtlname" id="edit_lname" required>
            </div>
            <div class="form-row">
                <label>Course and Section</label>
                <input type="text" name="txtcsection" id="edit_csection" required>
            </div>
            <div class="form-row">
                <label>Year Level</label>
                <input type="text" name="txtylevel" id="edit_ylevel" required>
            </div>
            <button type="submit" name="btnupdate" class="submit-btn update-accent">Update Student Information</button>
        </form>
    </dialog>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Course and Section</th>
                    <th>Year Level</th>
                    <th style="text-align: center; width: 160px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rs2 = $cn->query($sql2);
                if(!$rs2 || $rs2->num_rows == 0){
                    echo "<tr><td colspan='6' class='no-results'>😔 Walang nahanap na student.</td></tr>";
                } else {
                    while($row = $rs2->fetch_array()){ ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['fname']) ?></td>
                        <td><?= htmlspecialchars($row['lname']) ?></td>
                        <td><?= htmlspecialchars($row['course_section']) ?></td>
                        <td><?= htmlspecialchars($row['year_level']) ?></td>
                        <td style="text-align: center;">
                            <button class="btn-edit-trigger"
                                    data-id="<?= $row['id'] ?>"
                                    data-sid="<?= htmlspecialchars($row['student_id']) ?>"
                                    data-fname="<?= htmlspecialchars($row['fname']) ?>"
                                    data-lname="<?= htmlspecialchars($row['lname']) ?>"
                                    data-csection="<?= htmlspecialchars($row['course_section']) ?>"
                                    data-ylevel="<?= htmlspecialchars($row['year_level']) ?>">
                                Edit
                            </button>
                            <a onclick="return confirm('Are you sure?')" href="delete.php?id=<?= $row['id'] ?>" id="delete">Delete</a>
                        </td>
                    </tr>
                    <?php } 
                } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const modal = document.getElementById('modal');
    const editModal = document.getElementById('editModal');
    const openBtn = document.getElementById('open_modal');
    const closeBtn = document.getElementById('closemodal');
    const closeEditBtn = document.getElementById('closeEditModal');
    
    openBtn.addEventListener('click', () => modal.showModal());
    closeBtn.addEventListener('click', () => modal.close());
 
    closeEditBtn.addEventListener('click', () => editModal.close());
    
    window.addEventListener('click', (e) => { 
        if (e.target === modal) modal.close(); 
        if (e.target === editModal) editModal.close(); 
    });

    document.querySelectorAll('.btn-edit-trigger').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_sid').value = this.dataset.sid;
            document.getElementById('edit_fname').value = this.dataset.fname;
            document.getElementById('edit_lname').value = this.dataset.lname;
            document.getElementById('edit_csection').value = this.dataset.csection;
            document.getElementById('edit_ylevel').value = this.dataset.ylevel;
            
            editModal.showModal();
        });
    });
</script>
</body>
</html>
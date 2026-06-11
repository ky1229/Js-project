<?php
require 'connect.php';

// Handle registration submission
if(isset($_POST['btnreg'])){
    $company_id     = $cn->real_escape_string($_POST['txtcid']);
    $company_name   = $cn->real_escape_string($_POST['txtcname']);
    $industry       = $cn->real_escape_string($_POST['txtIndustry']);
    $contact_person = $cn->real_escape_string($_POST['txtcperson']);
    $office_address = $cn->real_escape_string($_POST['txtoAddress']);

    $sql1 = "INSERT INTO employer (company_id, company_name, industry, contact_person, office_address) 
             VALUES ('$company_id', '$company_name', '$industry', '$contact_person', '$office_address')";
    
    if($cn->query($sql1)){
        echo "<script>alert('Employer registered successfully'); window.location='employer.php';</script>";
    } else {
        echo "<script>alert('Error: " . $cn->error . "');</script>";
    }
}

// Handle update submission inline
if(isset($_POST['btnupdate'])){
    $id             = $cn->real_escape_string($_POST['txtid']);
    $company_id     = $cn->real_escape_string($_POST['txtcid']);
    $company_name   = $cn->real_escape_string($_POST['txtcname']);
    $industry       = $cn->real_escape_string($_POST['txtIndustry']);
    $contact_person = $cn->real_escape_string($_POST['txtcperson']);
    $office_address = $cn->real_escape_string($_POST['txtoAddress']);

    $sql_update = "UPDATE employer SET 
                   company_id = '$company_id', 
                   company_name = '$company_name', 
                   industry = '$industry', 
                   contact_person = '$contact_person', 
                   office_address = '$office_address' 
                   WHERE id = '$id'";
    
    if($cn->query($sql_update)){
        echo "<script>alert('Employer details updated successfully'); window.location='employer.php';</script>";
    } else {
        echo "<script>alert('Error updating employer: " . $cn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Employer Management</title>
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
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        #open_modal:hover { background-color: var(--accent-hover); }
        
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
            font-size: 1.15rem;
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
            text-align: left;
        }
        .form-row input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(88, 166, 255, 0.15);
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
        .no-records { text-align: center; padding: 30px; color: var(--text-muted); font-style: italic; }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container-box">

    <div class="action-bar">
        <button id="open_modal">Register Employer</button>
    </div>

    <dialog id="modal">
        <div class="modal-header">
            <h2>Employer Registration</h2>
            <button class="close-modal-btn" id="closemodal">&times;</button>
        </div>
        <form action="employer.php" method="post" class="modal-form" id="regForm">
            <div class="form-row">
                <label>Company ID</label>
                <input type="text" name="txtcid" required placeholder="e.g., 5001">
            </div>
            <div class="form-row">
                <label>Company Name</label>
                <input type="text" name="txtcname" required placeholder="e.g., Tech Solutions Inc.">
            </div>
            <div class="form-row">
                <label>Industry</label>
                <input type="text" name="txtIndustry" required placeholder="e.g., Information Technology">
            </div>
            <div class="form-row">
                <label>Contact Person</label>
                <input type="text" name="txtcperson" required placeholder="e.g., Jane Smith">
            </div>
            <div class="form-row">
                <label>Office Address</label>
                <input type="text" name="txtoAddress" required placeholder="e.g., 456 Innovation Blvd, Suite 101">
            </div>
            <button type="submit" name="btnreg" class="submit-btn">Register Employer</button>
        </form>
    </dialog>

    <dialog id="editModal">
        <div class="modal-header">
            <h2>Update Employer Credentials</h2>
            <button class="close-modal-btn" id="closeEditModal">&times;</button>
        </div>
        <form action="employer.php" method="post" class="modal-form" id="editForm">
            <input type="hidden" name="txtid" id="edit_id">
            
            <div class="form-row">
                <label>Company ID</label>
                <input type="text" name="txtcid" id="edit_cid" required>
            </div>
            <div class="form-row">
                <label>Company Name</label>
                <input type="text" name="txtcname" id="edit_cname" required>
            </div>
            <div class="form-row">
                <label>Industry</label>
                <input type="text" name="txtIndustry" id="edit_industry" required>
            </div>
            <div class="form-row">
                <label>Contact Person</label>
                <input type="text" name="txtcperson" id="edit_cperson" required>
            </div>
            <div class="form-row">
                <label>Office Address</label>
                <input type="text" name="txtoAddress" id="edit_oAddress" required>
            </div>
            <button type="submit" name="btnupdate" class="submit-btn update-accent">Update Employer Information</button>
        </form>
    </dialog>

    <table>
        <thead>
            <tr>
                <th>Employer ID</th>
                <th>Company Name</th>
                <th>Industry</th>
                <th>Contact Person</th>
                <th>Office Address</th>
                <th style="text-align: center; width: 150px;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $rs2 = $cn->query("SELECT * FROM employer");
            if(!$rs2 || $rs2->num_rows == 0){
                echo "<tr><td colspan='6' class='no-records'>No active employer records logged.</td></tr>";
            } else {
                while($row = $rs2->fetch_array()){ ?>
                <tr>
                    <td><?= htmlspecialchars($row['company_id']) ?></td>
                    <td><strong><?= htmlspecialchars($row['company_name']) ?></strong></td>
                    <td><?= htmlspecialchars($row['industry']) ?></td>
                    <td><?= htmlspecialchars($row['contact_person']) ?></td>
                    <td><?= htmlspecialchars($row['office_address']) ?></td>
                    <td style="text-align: center;">
                        <button class="btn-edit-trigger" 
                                data-id="<?= $row['id'] ?>"
                                data-cid="<?= htmlspecialchars($row['company_id']) ?>"
                                data-cname="<?= htmlspecialchars($row['company_name']) ?>"
                                data-industry="<?= htmlspecialchars($row['industry']) ?>"
                                data-cperson="<?= htmlspecialchars($row['contact_person']) ?>"
                                data-oaddress="<?= htmlspecialchars($row['office_address']) ?>">
                            Edit
                        </button>
                        <a onclick="return confirm('Are you sure you want to delete this employer entry?')" href="delete-employer.php?id=<?= $row['id'] ?>" id="delete">Delete</a>
                    </td>
                </tr>
                <?php } 
            } ?>
        </tbody>
    </table>
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
            document.getElementById('edit_cid').value = this.dataset.cid;
            document.getElementById('edit_cname').value = this.dataset.cname;
            document.getElementById('edit_industry').value = this.dataset.industry;
            document.getElementById('edit_cperson').value = this.dataset.cperson;
            document.getElementById('edit_oAddress').value = this.dataset.oaddress;
            
            editModal.showModal();
        });
    });
</script>
</body>
</html>
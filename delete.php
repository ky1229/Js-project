<?php
require "connect.php";
$rs = $cn->query("DELETE FROM student_registration WHERE id='" . $_GET['id'] . "'");
echo "
        <script type='text/javascript'>
            alert('student successfully deleted!');
            window.location = 'student-table.php';
        </script>
";
?>
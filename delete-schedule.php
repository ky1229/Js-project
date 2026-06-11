<?php
require "connect.php";
$rs = $cn->query("DELETE FROM schedule WHERE id='" . $_GET['id'] . "'");
echo "
        <script type='text/javascript'>
            alert('schedule successfully deleted!');
            window.location = 'schedule.php';
        </script>
";
?>
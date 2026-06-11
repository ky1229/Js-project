<?php
require "connect.php";
$rs = $cn->query("DELETE FROM employer WHERE id='" . $_GET['id'] . "'");
echo "
        <script type='text/javascript'>
            alert('employer successfully deleted!');
            window.location = 'employer.php';
        </script>
";
?>

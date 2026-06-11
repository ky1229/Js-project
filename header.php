<?php 
require 'connect.php';

// Fetch distinct values for filters
$types = $cn->query("SELECT DISTINCT type FROM schedule WHERE type IS NOT NULL AND type != ''");
$years = $cn->query("SELECT DISTINCT year_level FROM student_registration WHERE year_level IS NOT NULL ORDER BY year_level ASC");
$courses = $cn->query("SELECT DISTINCT course_section FROM student_registration WHERE course_section IS NOT NULL AND course_section != ''");

// Get current filter values
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : '';
$filter_year = isset($_GET['filter_year']) ? $_GET['filter_year'] : '';
$filter_course = isset($_GET['filter_course']) ? $_GET['filter_course'] : '';
?>
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #161620;
    }
    #head {
        padding: 15px 25px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .nav-row {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .nav {
        color: white;
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 1.1em;
        font-weight: 500;
        text-decoration: none;
        margin: 0 20px;
        padding: 5px 10px;
        transition: all 0.3s ease;
        border-bottom: 2px solid transparent;
    }
    .nav:hover {
        color: #ffcc6c;
        border-bottom: 2px solid white;
        transform: translateY(-2px);
    }
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 8px;
    }
    .filter-row input[type="text"],
    .filter-row select,
    .filter-row button,
    .filter-row .clear-btn {
        height: 34px;
        box-sizing: border-box;
        font-size: 0.9em;
        border-radius: 20px;
    }
    .filter-row input[type="text"] {
        padding: 0 14px;
        border: none;
        outline: none;
        background: rgba(255,255,255,0.9);
        color: #333;
        min-width: 250px;
    }
    .filter-row select {
        padding: 0 10px;
        border: none;
        outline: none;
        background: rgba(255,255,255,0.9);
        color: #333;
        min-width: 140px;
        cursor: pointer;
    }
    .filter-row button {
        padding: 0 16px;
        border: 2px solid white;
        background: transparent;
        color: white;
        cursor: pointer;
        transition: 0.3s;
    }
    .filter-row button:hover {
        background: white;
        color: #ffcc6c;
    }
    .clear-btn {
        padding: 0 16px;
        line-height: 34px;
        border: 2px solid white;
        background: rgba(0,0,0,0.2);
        color: white;
        cursor: pointer;
        text-decoration: none;
        transition: 0.3s;
        display: inline-block;
    }
    .clear-btn:hover {
        background: white;
        color: red;
    }
</style>

<header id="head">
    <div class="nav-row">
        <a href="home.php" class="nav">Home</a>
        <a href="student-table.php" class="nav">List of Student</a>
        <a href="schedule.php" class="nav">Student Schedule</a>
        <a href="employer.php" class="nav">Student Employer</a>
    </div>
    <form method="GET" action="student-table.php" class="filter-row">
        <input type="text" name="search" placeholder="🔍 Search student..." value="<?= htmlspecialchars($search) ?>">
        <select name="filter_type">
            <option value="">Work Type</option>
            <?php if($types): while($row = $types->fetch_array()): ?>
                <option value="<?= htmlspecialchars($row['type']) ?>" <?= $filter_type == $row['type'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['type']) ?>
                </option>
            <?php endwhile; endif; ?>
        </select>
        <select name="filter_year">
            <option value="">Year Level</option>
            <?php if($years): while($row = $years->fetch_array()): ?>
                <option value="<?= htmlspecialchars($row['year_level']) ?>" <?= $filter_year == $row['year_level'] ? 'selected' : '' ?>>
                    Year <?= htmlspecialchars($row['year_level']) ?>
                </option>
            <?php endwhile; endif; ?>
        </select>
        <select name="filter_course">
            <option value="">Course / Program</option>
            <?php if($courses): while($row = $courses->fetch_array()): ?>
                <option value="<?= htmlspecialchars($row['course_section']) ?>" <?= $filter_course == $row['course_section'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['course_section']) ?>
                </option>
            <?php endwhile; endif; ?>
        </select>
        <button type="submit">Filter</button>
        <a href="student-table.php" class="clear-btn">Clear</a>
    </form>
</header>
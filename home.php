<?php 
include 'header.php';
?>

<style>
    :root {
        --surface: rgba(16, 21, 32, 0.75);
        --border: rgba(31, 41, 61, 0.6);
        --text: #f0f3f8;
        --text-muted: #7988a3;
        --accent: #119df7;
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

    @keyframes movingGradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    #title {
        max-width: 1200px;
        margin: 0 auto;
        padding: 80px 24px;
        text-align: center;
    }

    #title h1 {
        font-size: 2.5rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        color: #ffffff;
        margin-bottom: 40px;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .container-wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
        margin-top: 20px;
    }

    .container {
        background-color: var(--surface); 
        border: 1px solid var(--border);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 35px 24px; 
        width: 290px;
        min-height: 190px;
        border-radius: var(--radius);
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), 
                    border-color 0.25s ease, 
                    box-shadow 0.25s ease;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .container a {
        color: var(--text);
        text-decoration: none;
        display: block;
        height: 100%;
    }

    .container:hover {
        transform: translateY(-6px);
        border-color: #ffcc6c;
        box-shadow: 0 15px 35px  rgba(255, 209, 3, 0.4);
    }

    .container h1 {
        margin: 0 0 16px 0;
        font-size: 2.6rem;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
    }

    .container h4 {
        margin: 0 0 8px 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
    }

    .container p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.45;
    }
</style>

<div id="title">
    <h1>Work Smart, Learn Smart</h1>

    <div class="container-wrap">
        <div class="container">
            <a href="schedule.php">
                <h1>🗓️</h1>
                <h4>Check your Schedule</h4> 
                <p>Flexible schedule monitoring for student tracking.</p> 
            </a>
        </div>

        <div class="container">
            <a href="student-table.php">
                <h1>📓๋࣭</h1>
                <h4>Fellow Working Students</h4> 
                <p>View peer directories and active workspace logs.</p> 
            </a>
        </div>

        <div class="container">
            <a href="employer.php">
                <h1>💼</h1>
                <h4>Registered Employers</h4> 
                <p>View affiliate corporate partners and opportunities.</p> 
            </a>
        </div>
    </div>
</div>
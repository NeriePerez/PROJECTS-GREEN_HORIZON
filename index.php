<?php
    include 'controllerUser.php';

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    
    // Fetch today’s challenge to display
    $query = $conn->query("SELECT challenge_id FROM daily_challenge WHERE user_id = $user_id AND daily_status = 'ongoing' AND assigned_date = CURDATE()")->fetch_assoc();
    $result = null;

    if ($query && isset($query['challenge_id'])) {
        $challenge_id = intval($query['challenge_id']);
        $result = $conn->query("
            SELECT challenge_title, challenge_points 
            FROM challenges 
            WHERE challenge_id = $challenge_id
        ")->fetch_assoc();
    }

    // Fetch quiz to display
    $queryQ = $conn->query("SELECT quiz_id FROM user_quiz WHERE user_id = $user_id AND earned_points IS NULL")->fetch_assoc();
    $resultQ = null;
        if ($queryQ && isset($queryQ['quiz_id'])) {
            $quiz_id = $queryQ['quiz_id'];
            $resultQ = $conn->query("SELECT quiz_level, quiz_title FROM quiz WHERE quiz_id = $quiz_id")->fetch_assoc();
        }

    $quiz_score = isset($_GET['quiz_score']) ? intval($_GET['quiz_score']) : null;
    $quiz_level = isset($_GET['quiz_level']) ? htmlspecialchars($_GET['quiz_level']) : '';

    //dashboard info fetch
    $challengesDone = $conn->query("SELECT COUNT(*) as total FROM daily_challenge WHERE user_id = $user_id AND daily_status = 'completed'")->fetch_assoc()['total'];

    $streakRow = $conn->query("SELECT current_streak, longest_streak FROM user_streaks WHERE user_id = $user_id")->fetch_assoc();
    $currentStreak = $streakRow ? $streakRow['current_streak'] : 0;
    $longestStreak = $streakRow ? $streakRow['longest_streak'] : 0; 

    $month = date('m');
    $year = date('Y');
    $pointsRow = $conn->query("SELECT monthly_points, overall_points FROM user_points WHERE user_id = $user_id AND MONTH(`date`) = $month AND YEAR(`date`) = $year ORDER BY `date` DESC LIMIT 1")->fetch_assoc();
    $monthlyPoints = $pointsRow ? $pointsRow['monthly_points'] : 0;
    $overallPoints = $pointsRow ? $pointsRow['overall_points'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Green Horizon | Building a Greener Tomorrow</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <style>
            :root {
                --color-primary: hsl(140, 50%, 35%);
                --color-primary-dark: hsl(140, 55%, 20%);
                --color-accent: hsl(45, 100%, 60%);
                --color-accent-hover: hsl(45, 95%, 55%);
                --color-bg: hsl(40, 20%, 98%);
                --color-card: hsl(35, 30%, 99%);
                --color-text: hsl(140, 20%, 15%);
                --color-text-secondary: hsl(140, 10%, 45%);
            }
            body {
                background-color: var(--color-bg);
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: var(--color-text);
                line-height: 1.6;
            }
            .h2 {
                color: #2e7d32;
                font-weight: 600;
                text-align:justify;
                margin-bottom: 1.5rem;
            }
            /* -------- NAVBAR -------- */
            .navbar {
                background-color: #2E7D32;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            .navbar-brand,
            .nav-link {
                color: rgb(255, 255, 255) !important;
                font-weight: bold;
            }
            .nav-link:hover {
                color: #09140e !important;
            }
            .dropdown-item {
                color: #333;
            }
        /* Hover dropdown effect for desktop */
        @media (min-width: 992px) {
            .navbar-nav .nav-item .nav-link {
            position: relative;
            transition: color 0.3s ease;
            }

            .navbar-nav .nav-item .nav-link::after {
            content: "";
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #a8e063;
            transition: width 0.3s;
            }

            .navbar-nav .nav-item .nav-link:hover::after {
            width: 100%;
            }

            .navbar-nav .nav-item .nav-link:hover {
            color: #a8e063 !important;
            }
        /* KEEP UNDERLINE WHEN PAGE IS ACTIVE */
            .navbar-nav .nav-item .nav-link.active {
            color: #a8e063 !important;
            position: relative;
        }
        .navbar-nav .nav-item .nav-link.active::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #a8e063;
        }
        }
            /* -------- DASHBOARD -------- */
            .dashboard-section {
                margin: 3rem auto 5rem auto;
            }
            .dashboard-section h2 {
                color: #2e7d32;
                font-weight: 800;
                margin-top: 10rem;
                margin-bottom: 3rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 2.5rem;
            }
            .dashboard-section p{
                color: #0d0e0d;
                font-weight: 750;
                text-align:center;
                margin-top: 2rem;
                font-size: 3rem;
            }
            .dashboard-card {
                background: linear-gradient(180deg, var(--color-card), hsl(40, 30%, 97%));
                border-radius: 20px;
                padding: 2.5rem 1.5rem;
                text-align: center;
                border: 1px solid hsl(40, 20%, 88%);
                transition: all 0.3s ease;
                box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            }
            .dashboard-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 12px 30px rgba(0,0,0,0.12);
            }
            .dashboard-card .icon-wrapper {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                transition: all 0.3s ease;
                border: 3px solid;
                font-size: 2.2rem;
            }
            .card-1 .icon-wrapper { border-color: var(--color-primary); color: var(--color-primary); }
            .card-2 .icon-wrapper { border-color: #ff6b35; color: #ff6b35; }
            .card-3 .icon-wrapper { border-color: var(--color-accent-hover); color: var(--color-accent-hover); }
            .card-title {
                color: var(--color-primary-dark);
                font-size: 1.125rem;
                font-weight: 600;
                margin-bottom: 1rem;
                text-transform: uppercase;
            }
            .card-value {
                color: var(--color-text);
                font-size: 3.5rem;
                font-weight: 800;
                line-height: 1;
            }
            /* -------- CHALLENGE BANNER -------- */
            .challenge-banner {
                position: relative;
                border-radius: 24px;
                overflow: hidden;
                padding: 5rem 2rem;
                text-align: center;
                box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                margin: 4rem auto 2.5rem auto;
                background-image: linear-gradient(135deg,#a8d8a8, #ebf7eb), url('img/challenge.png');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                margin-bottom: 9%;
            }
            .challenge-banner h1 {
                color: #2e7d32;
                font-size: 2.5rem;
                font-weight: 800;
                text-shadow: 0 3px 5px rgba(88, 86, 86, 0.3);
                margin-bottom: 3rem;
            }
            .challenge-banner h5 {
                color: rgba(22, 22, 22, 0.95);
                font-size: 1.35rem;
                margin-top: 6rem;
                line-height: 1.5;
            }
            .challenge-banner p {
                margin-bottom: 4.5rem;
            }
            .btn-done {
                background-color: #ffffff;
                color: #3da843;
                font-weight: 700;
                font-size: 1.25rem;
                padding: 1rem 3rem;
                border-radius: 50px;
                border: 2px solid #2e7d32;
                transition: all 0.3s ease;
                box-shadow: 0 6px 20px rgba(0,0,0,0.3);
                display: inline-flex;
                align-items: center;
                gap: 0.75rem;
            }
            .btn-done:hover {
                background-color: #1da32f;
                color: #ffffff;
                border-color: #1d1d1c;
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.35);
            }
            /* -------- QUIZ BANNER -------- */
            .quiz-banner {
                border-radius: 24px;
                padding: 3rem 2rem;
                text-align: center;
                margin: 2rem auto 4rem auto;
                box-shadow: 0 12px 30px rgba(0,0,0,0.15);
                transition: all 0.3s ease;
                text-decoration: none;
                display: block;
                background-image: linear-gradient(135deg, #2e7d32, rgba(18, 228, 18, 0.7)), url('https://source.unsplash.com/random/1200x300/?eco,community,quiz');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                margin-bottom: 9%;
            }
            .quiz-banner:hover {
                transform: scale(1.01);
            }
            .quiz-banner h3 {
                color: #ebeeeb;
                font-weight: 800;
                font-size: 2.2rem;
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            }

            /* Alert paragraph responsiveness */
            .alert {
                max-width: 100%;
                width: 90%;
                margin: 0 auto;
                font-size: 1rem;
                background-color: #E8F5E9;
                color: #1B5E20;
                border: 1px solid #A5D6A7;
            }

            /* Button style */
            .difficulty-btn {
                width: 90%;
                max-width: 800px;
                font-weight: 600;
                background-color: #2E7D32;
                border: none;
                transition: 0.3s;
            }

            .difficulty-btn:hover {
                background-color: #256628;
            }

            /* -------- FOOTER -------- */
            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                margin-top: 5rem;
                box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            }
            /* -------- RESPONSIVE -------- */
            @media (max-width: 768px) {
                .challenge-banner { padding: 4rem 1.5rem; }
                .challenge-banner h1 { font-size: 2.2rem; }
                .challenge-banner h5 { font-size: 1.1rem; }
                .quiz-banner { padding: 2.5rem 1.5rem; }
                .quiz-banner h3 { font-size: 1.8rem; }
                .dashboard-section h3 { font-size: 1.8rem; }
                .card-value { font-size: 3rem; }
            }
        </style>
    </head>
    <body>
        <!--NAVBAR-->
        <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/GHLOGO.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle" />
                Green Horizon
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                    <li class="nav-item"><a class="nav-link active" href="index.php?user_id=<?= htmlspecialchars($user_id) ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php?user_id=<?= htmlspecialchars($user_id) ?>">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="comfeed.php?user_id=<?= htmlspecialchars($user_id) ?>">Community Feed</a></li>
                    <li class="nav-item"><a class="nav-link" href="leaderboard.php?user_id=<?= htmlspecialchars($user_id) ?>">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php?user_id=<?= htmlspecialchars($user_id) ?>">Donate!</a></li>
                </ul>

                <div class="dropdown me-3">
                    <button class="btn btn-outline-light dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        🔔
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item fw-bold" href="#">You completed a challenge!</a></li>
                        <li><a class="dropdown-item" href="#">You are top 1 in the monthly Leaderboard!</a></li>
                        <li><a class="dropdown-item" href="#">Someone liked your post</a></li>
                    </ul>
                </div>

                <a href="landing.html" class="btn btn-outline-light">Log Out</a>
            </div>
        </nav>

        <!-- USER DASHBOARD -->
        <div class="container dashboard-section">
            <h2 class="text-center">Your Dashboard</h2>

            <div class="row g-4 justify-content-center">

                <!-- Challenges Done -->
                <div class="col-sm-6 col-lg-4">
                    <div class="dashboard-card card-1">
                        <div class="icon-wrapper">
                            <i class="fas fa-list-check"></i>
                        </div>
                        <h5 class="card-title">Challenges Done</h5>
                        <p class="card-value"><?= htmlspecialchars($challengesDone) ?></p>
                        <br>
                    </div>
                </div>

                <!-- Streaks -->
                <div class="col-sm-6 col-lg-4">
                    <div class="dashboard-card card-2">
                        <div class="icon-wrapper">
                            <i class="fas fa-fire-alt"></i>
                        </div>
                        <h5 class="card-title">Streaks</h5>
                        <p class="card-value"><?= htmlspecialchars($currentStreak) ?> Days</p>
                        <small class="text-muted">Longest: <?= htmlspecialchars($longestStreak) ?> Days</small>
                    </div>
                </div>

                <!-- Total Points -->
                <div class="col-sm-6 col-lg-4">
                    <div class="dashboard-card card-3">
                        <div class="icon-wrapper">
                            <i class="fas fa-star"></i>
                        </div>
                        <h5 class="card-title">Points</h5>
                        <p class="card-value"><?= htmlspecialchars($monthlyPoints) ?></p>
                        <small class="text-muted">Overall: <?= htmlspecialchars($overallPoints) ?></small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
            <!--CHALLENGE TAB-->
            <div class="challenge-banner">
                <h1>Today's Challenge:</h1>
                <?php if ($result): ?>
                    <h5><?= htmlspecialchars($result['challenge_title']) ?></h5>
                    <p><strong>Points(CO₂ saved):</strong> <?= htmlspecialchars($result['challenge_points']) ?></p>
                    <button class="btn-done" onclick="window.location.href='post.php?user_id=<?= htmlspecialchars($user_id)?>'"><span>🌳</span> DONE</button>
                <?php else: ?>
                    <p>No more challenge assigned for today. Comeback for tomorrow!</p>
                <?php endif; ?>
            </div>

            <!--QUIZ BUTTON-->
            <a data-bs-toggle="modal" data-bs-target="#quizModal"
                class="quiz-banner">
                <h3>Take a Quick Eco Quiz →</h3>
            </a>
        </div>


        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow </p>
        </footer>

        <!-- QUIZ MODAL -->
        <div class="modal fade" id="quizModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                    <h3 class="modal-title">Green Horizon Quiz</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="alert mx-auto rounded-4 mb-4">
                            <strong>Directions:</strong> Choose the difficulty level below to begin your quiz.
                            Each level tests your knowledge about sustainability, nature, and environmental care.
                        </div><br><br>

                        <?php if ($resultQ): ?>
                            <h3 class="mb-4">Level <?= htmlspecialchars($resultQ['quiz_level']) ?>: <?= htmlspecialchars($resultQ['quiz_title']) ?></h3>
                            <a href="quiz.php?quiz_id=<?= htmlspecialchars($queryQ['quiz_id']) ?>&user_id=<?= htmlspecialchars($user_id)?>" class="btn btn-success difficulty-btn mb-5 py-2 rounded-4"><h4>Start</h4></a><br>
                        <?php else: ?>
                            <p>No quiz available as of the moment. Will be back soon!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- QUIZ SCORE MODAL -->
        <div class="modal fade" id="scoreModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h3 class="modal-title">Congratulations!</h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p id="scoreMessage" class="fs-5 mb-3"></p>
                        <h4 id="scorePoints" class="fw-bold"></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHALLENGE POST SUCCESS MODAL -->
        <div class="modal fade" id="challengePostModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <!-- Header -->
                    <div class="modal-header bg-success text-white">
                        <h3 class="modal-title">Daily Challenge Complete!</h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body text-center">
                        <h4 class="fw-bold text-success mb-2">Great job!</h4>
                        <p class="fs-5 mb-3">
                            You’ve successfully completed today’s environmental challenge and shared your progress with the community. 🌎💚
                        </p>
                        <p class="text-muted mb-0">
                            Keep it up! Small actions lead to a greener, cleaner planet.
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
                            Awesome!
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const score = <?= $quiz_score ?? 'null' ?>;
                const level = "<?= $quiz_level ?>";

                if (score !== null) {
                    document.getElementById("scoreMessage").innerText = `You completed the Level ${level} Quiz!`;
                    document.getElementById("scorePoints").innerText = `Points Earned: ${score}`;
                    
                    const scoreModal = new bootstrap.Modal(document.getElementById('scoreModal'));
                    scoreModal.show();
                }

                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('success') === '1') {
                    const challengePostModal = new bootstrap.Modal(document.getElementById('challengePostModal'));
                    challengePostModal.show();
                }
            });
        </script>
    </body>
</html>
<?php 
    include 'admin_controller.php';

    $donors = $conn->query("SELECT * FROM donations ORDER BY donate_id ASC");

    $month = date('m');
    $year = date('Y');

    // Fetch top 10 users for current month
    $stmt = $conn->prepare("SELECT u.user_username, u.user_profpic, up.monthly_points
        FROM user_points up JOIN user u ON u.user_id = up.user_id
        WHERE MONTH(up.`date`) = ? AND YEAR(up.`date`) = ?
        ORDER BY up.monthly_points DESC LIMIT 10");
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $leaderboard = [];
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Admin Report Dashboard</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body {
                background-color: #F9FAF8;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: #2E7D32;
                padding-top: 75px;
                margin: 0;
            }
            .navbar {
                background-color: #2E7D32;
            }
            .navbar-brand, .nav-link {
                color: white !important;
                font-weight: bold;
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

            /* Cover photo section */
            .cover-photo {
                height: 700px;
                background: linear-gradient(rgba(46, 125, 50, 0.3), rgba(46, 125, 50, 0.3)),
                    url('https://images.pexels.com/photos/4763942/pexels-photo-4763942.jpeg') center/cover no-repeat;
                transition: height 0.4s ease;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                text-align: center;
            }

            .cover-photo h1 {
                color: white;
                font-weight: 800;
                font-size: 4rem;
                letter-spacing: 2px;
                transition: font-size 0.4s ease;
            }

            /* Shrinking effect */
            .shrink {
                height: 300px !important;
            }
            .shrink h1 {
                font-size: 2.5rem !important;
            }

            /* Dashboard Section */
            .dashboard-container {
                max-width: 1100px;
                margin: 0 auto;
                padding: 40px 20px;
            }
            .card {
                border: none;
                border-radius: 16px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                background-color: #ffffff;
                transition: transform 0.2s;
            }
            .card:hover {
                transform: translateY(-4px);
            }
            .card h4 {
                color: #1B5E20;
                font-weight: bold;
            }
            .card .icon {
                font-size: 2.5rem;
                color: #2E7D32;
            }

            /* Leaderboard Table */
            .leaderboard {
                margin-top: 50px;
                background-color: #ffffff;
                border-radius: 20px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                padding: 30px;
            }
            .leaderboard h3 {
                color: #2E7D32;
                font-weight: bold;
                margin-bottom: 25px;
                margin-top: 10px;
                font-size: 35px;
            }
            .table-container {
                max-width: 900px;
                margin: 0 auto;
                border-radius: 16px;
                overflow: hidden;
                background-color: #ffffff;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            }
            .scroll-area {
                max-height: 400px;
                overflow-y: auto;
            }
            table {
                width: 100%;
                text-align: center;
                border-collapse: collapse;
            }
            thead th {
                position: sticky;
                top: 0;
                background-color: #d0e8d0 !important;
                color: #2e5e2e !important;
                z-index: 20;
                border-bottom: 2px solid #b5d6b5;
                font-weight: 600;
                padding: 15px;
            }
            tbody tr:hover {
                background-color: #F1F8E9;
            }

            /* Rank Colors */
            .rank-1 td {
                background-color: #e9f7ec;
                font-weight: bold;
                font-size: 1.05em;
                color: #2e7d32;
            }
            .rank-2 td {
                background-color: #e0f2f1;
                font-weight: bold;
                color: #00796b;
            }
            .rank-3 td {
                background-color: #fff3e0;
                font-weight: bold;
                color: #ef6c00;
            }

            /* Scrollbar Style */
            .scroll-area::-webkit-scrollbar {
                width: 8px;
            }
            .scroll-area::-webkit-scrollbar-thumb {
                background-color: #A5D6A7;
                border-radius: 10px;
            }

            /* Footer */
            footer {
                background: #2e7d32;
                color: white;
                text-align: center;
                padding: 20px 0;
                width: 100vw;
                position: relative;
                left: 50%;
                right: 50%;
                margin-left: -50vw;
                margin-right: -50vw;
                margin-top: 60px;
            }
        </style>
    </head>
    <body>
        <!--NAV TAB-->
        <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/GHLOGO.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle" />
                Green Horizon | ADMIN
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin_index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_challenge&quiz.php">Challenges & Quiz</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_reports.php">Reports</a></li>
                </ul>

                <a href="landing.html" class="btn btn-outline-light mx-4">Log Out</a>
            </div>
        </nav>

        <div class="cover-photo" id="coverPhoto">
            <h1 id="coverTitle">Green Horizon Dashboard</h1>
        </div>

        <div class="dashboard-container">
            <h2 class="text-center fw-bold mb-4 text-success">Admin Dashboard Overview</h2>

            <div class="row g-4 text-center justify-content-center">
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <div class="icon mb-2 fs-1">💰</div>
                        <h4 class="fw-bold text-success">Total Donations</h4>
                        <p class="fs-4 fw-bold">₱77,777</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <div class="icon mb-2 fs-1 text-success">🌿</div>
                        <h4 class="fw-bold text-success">Overall Points</h4>
                        <p class="fs-4 fw-bold">3,053 pts</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <div class="icon mb-2 fs-1 text-primary">👥</div>
                        <h4 class="fw-bold text-success">Total Members</h4>
                        <p class="fs-4 fw-bold">2,345</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <div class="icon mb-2 fs-1 text-success">🌱</div>
                        <h4 class="fw-bold text-success">Estimated Total of <br>CO₂ Savings</h4>
                        <p class="fs-4 fw-bold">2,345</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 shadow-sm border-0">
                        <div class="icon mb-2 fs-1 text-dark">🏁</div>
                        <h4 class="fw-bold text-success">Total Number of Challenges Completed</h4>
                        <p class="fs-4 fw-bold">2,345</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="leaderboard mt-5 mb-5 text-center">
            <!--DONATION LIST-->
            <h3>Donations</h3>
            <section class="donor-section mt-5">
                <div class="table-container">
                    <div class="scroll-area">
                        <table class="table table-striped align-middle mb-0 text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Donor Name</th>
                                    <th>Email</th>
                                    <th>Donation Amount (₱)</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($donors->num_rows > 0) {
                                    $counter = 1;
                                    while ($row = $donors->fetch_assoc()) {
                                        $name = htmlspecialchars($row['name']);
                                        $email = htmlspecialchars($row['email']);
                                        $amount = number_format($row['donation_amount'], 2);
                                        $dateTime = date('M d, Y H:i', strtotime($row['donation_date'])); // format date & time
                                        echo "<tr>
                                                <td>{$counter}</td>
                                                <td>{$name}</td>
                                                <td>{$email}</td>
                                                <td>₱{$amount}</td>
                                                <td>{$dateTime}</td>
                                            </tr>";
                                        $counter++;
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No donors yet. Be the first to donate!</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section><br><br>

            <!-- USER LEADERBOARD-->
            <h3>Leaderboard</h3>
            <div class="table-container">
                <div class="scroll-area">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Name</th>
                                <th>Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($leaderboard)) {
                                $rank = 1;
                                foreach ($leaderboard as $row) {
                                    $name = htmlspecialchars($row['user_username']);
                                    $points = intval($row['monthly_points']);

                                    // Profile picture (use default if empty)
                                    $pic = !empty($row['user_profpic'])
                                        ? htmlspecialchars($row['user_profpic'])
                                        : "img/default_profile.png";

                                    // Rank color classes
                                    $rankClass = "";
                                    if ($rank == 1) $rankClass = "rank-1";
                                    else if ($rank == 2) $rankClass = "rank-2";
                                    else if ($rank == 3) $rankClass = "rank-3";

                                    echo "
                                    <tr class='$rankClass'>
                                        <td>$rank" . ($rank <= 3 ? ['🥇','🥈','🥉'][$rank-1] : "") . "</td>

                                        <td class='text-start'>
                                            <div class='d-flex align-items-center'>
                                                <img src='$pic' 
                                                    class='rounded-circle me-2'
                                                    style='width:40px; height:40px; object-fit:cover;'>

                                                <span>$name</span>
                                            </div>
                                        </td>

                                        <td>$points</td>
                                    </tr>";

                                    $rank++;
                                }
                            } else {
                                echo "<tr><td colspan='3'>No leaderboard data available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            window.addEventListener('scroll', function() {
                const cover = document.getElementById('coverPhoto');
                if (window.scrollY > 80) {
                    cover.classList.add('shrink');
                } else {
                    cover.classList.remove('shrink');
                }
            });
        </script>
    </body>
</html>
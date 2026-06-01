<?php 
    include 'admin_controller.php';
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

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
        <title>Green Horizon | Leaderboard</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body {
                background-color: #f9fff9;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: #2E7D32;
                overflow-x: hidden;
            }

            .navbar { background-color: #2E7D32; }
            .navbar-brand, .nav-link { color: white !important; font-weight: bold; }

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


            h1 {
                text-align: center;
                margin-top: 9rem;
                font-weight: bold;
                color: #2E7D32;
                margin-bottom: 60px;
            }

            /* PODIUM SECTION */
            .podium {
                display: flex;
                justify-content: center;
                align-items: flex-end;
                gap: 1.5rem;
                margin-top: 2rem;
                flex-wrap: wrap;
            }

            .podium-item {
                text-align: center;
                background-color: #e9f7ec;
                padding: 1.2rem;
                border-radius: 16px;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s;
                width: 160px;
            }

            .podium-item:hover {
                transform: translateY(-8px);
            }

            .podium-item img {
                width: 90px;
                height: 90px;
                border-radius: 50%;
                object-fit: cover;
                border: 3px solid #2E7D32;
                margin-bottom: 0.5rem;
            }

            .podium-item h4 {
                margin-top: 0.3rem;
                color: #2E7D32;
                font-weight: bold;
                font-size: 1rem;
            }

            .podium-item .score {
                font-size: 1rem;
                color: #1b5e20;
            }
            .podium {
                display: flex;
                justify-content: center;
                align-items: flex-end;
                gap: 2rem; /* adds space between boxes */
                margin-top: 2rem;
                flex-wrap: wrap;
            }

            /* FIRST PLACE */
            .first {
                background-color: #d0f8ce;
                border: 3px solid gold;
                transform: scale(1.25);
                width: 180px;
                padding: 1.5rem;
                z-index: 2;
                margin: 0 1rem; /* adds side spacing */
            }

            /* SECOND PLACE */
            .second {
                background-color: #e0f7fa;
                border: 2px solid silver;
                width: 170px;
                padding: 1.2rem;
                transform: translateY(10px);
                margin: 0 1rem;
            }

            /* THIRD PLACE */
            .third {
                background-color: #fff3e0;
                border: 2px solid #cd7f32;
                width: 150px;
                padding: 1rem;
                transform: translateY(20px);
                margin: 0 1rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .podium {
                    gap: 1rem;
                }
                .first, .second, .third {
                width: 120px;
                transform: none;
                margin: 0.5rem;
                }
            }



            /* Responsive for smaller screens */
            @media (max-width: 768px) {
                .podium {
                gap: 1rem;
                }
                .podium-item {
                width: 120px;
                padding: 1rem;
                }
                .podium-item img {
                width: 70px;
                height: 70px;
                }
            }
            .profile-small-table {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid #2E7D32;
            }
            .table-container {
            max-width: 800px;
            margin: 2rem auto;
            margin-top: 80px;
            background-color: #FFFBF9;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 1s ease-in;
            }

            .scroll-area { max-height: 521px; overflow-y:auto; }

            thead th {
            background-color: #d0e8d0 !important;
            color: #2e5e2e !important;
            border-bottom: 2px solid #b5d6b5;
            text-align: center;
            }

            .scroll-area::-webkit-scrollbar { width: 8px; }
            .scroll-area::-webkit-scrollbar-thumb {
                background-color: #A5D6A7;
                border-radius: 10px;
            }

            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                margin-top: 200px;
            }
        </style>
    </head>
    <body>
        <!--NAVTABS-->
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
                    <li class="nav-item"><a class="nav-link" href="index.php?user_id=<?= htmlspecialchars($user_id) ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php?user_id=<?= htmlspecialchars($user_id) ?>">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="comfeed.php?user_id=<?= htmlspecialchars($user_id) ?>">Community Feed</a></li>
                    <li class="nav-item"><a class="nav-link active" href="leaderboard.php?user_id=<?= htmlspecialchars($user_id) ?>">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php?user_id=<?= htmlspecialchars($user_id) ?>">Donate!</a></li>
                </ul>
                <div class="dropdown me-3">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">🔔</button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item fw-bold" href="#">You completed a challenge!</a></li>
                        <li><a class="dropdown-item" href="#">You are top 1 in the monthly Leaderboard!</a></li>
                        <li><a class="dropdown-item" href="#">Someone liked your post</a></li>
                </ul>
                </div>
                <a href="landing.html" class="btn btn-outline-light" type="button">Log Out</a>
            </div>
        </nav>

        <h1>Leaderboard for <?php echo date('F Y'); ?></h1>

        <div class="podium">
            <?php if (count($leaderboard) > 0): ?>
                <?php
                    $podium_classes = ['second', 'first', 'third']; // order to place first in center
                    $emojis = ['🥈', '🥇', '🥉'];
                    for ($i = 0; $i < 3; $i++):
                        if (!isset($leaderboard[$i])) break;
                        $user = $leaderboard[$i];
                ?>
                    <div class="podium-item <?= $podium_classes[$i] ?>">
                        <img src="<?= htmlspecialchars($user['user_profpic'] ?: 'img/default.png') ?>" alt="<?= htmlspecialchars($user['user_username']) ?>">
                        <h4><?= $emojis[$i] ?> <?= htmlspecialchars($user['user_username']) ?></h4>
                        <p class="score"><?= htmlspecialchars($user['monthly_points']) ?> pts</p>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>

        <div class="table-container mt-5 mx-auto" style="max-width:800px;">
            <table class="table table-hover text-center">
                <thead class="table-success">
                    <tr>
                        <th>Rank</th>
                        <th>Name</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($leaderboard, 3) as $index => $user): ?>
                        <tr>
                            <td><?= $index + 4 ?></td>
                            <td class="d-flex align-items-center gap-2">
                                <img src="<?= htmlspecialchars($user['user_profpic'] ?: 'img/default.png') ?>" 
                                    alt="<?= htmlspecialchars($user['user_username']) ?>" 
                                    class="profile-small-table">
                                <?= htmlspecialchars($user['user_username']) ?>
                            </td>
                            <td><?= htmlspecialchars($user['monthly_points']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

    </body>
</html>
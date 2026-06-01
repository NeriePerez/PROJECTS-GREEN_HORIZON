<?php
    include 'admin_controllerChallenge&Quiz.php';

    $result_challenge = $conn->query("SELECT * FROM challenges ORDER BY challenge_id DESC");
    $result_quiz = $conn->query("SELECT * FROM quiz ORDER BY quiz_level ASC");

    $quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
    $questions_result = $conn->query("SELECT * FROM quiz_question WHERE quiz_id = $quiz_id ORDER BY question_id ASC");

    $quiz_data = null;
    if ($quiz_id > 0) {
        $quiz_query = $conn->query("SELECT * FROM quiz WHERE quiz_id = $quiz_id");
        $quiz_data = $quiz_query->fetch_assoc();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Challenges & Quiz</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"rel="stylesheet"/>

        <style>
            :root {
                --color-primary: #2E7D32; /* Deep Green */
                --color-primary-dark: #1B5E20; /* Darker Green */
                --color-accent-light: #A5D6A7; /* Medium Green */
                --color-bg-light: #F1F8E9; /* Very Light Green */
                --color-text: #2E7D32;
            }

            body {
                background-color: #F9FAF8;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: var(--color-primary-dark);
                padding-top: 75px;
                margin: 0;
            }

            /* NAVBAR */
            .navbar {
            background-color: #2e7d32;
            }
            .navbar-brand, .nav-link {
            color: white !important;
            font-weight: bold;
            }

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


            .cover-photo {
            height: 700px;
            background: linear-gradient(rgba(46, 125, 50, 0.3), rgba(46, 125, 50, 0.3)),
                url('https://images.pexels.com/photos/4763942/pexels-photo-4763942.jpeg') center/cover no-repeat;
            transition: height 0.6s ease-in-out, background-size 0.6s ease-in-out;
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
            height: 200px !important;
            }
            .shrink h1 {
            font-size: 2.5rem !important;
            }

            .group-info {
            background-color: #fff;
            padding: 15px;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            text-align: center;
            position: relative;
            z-index: 2;
            margin-bottom: 100px;
            }

            .group-info h1 {
            font-weight: 700;
            color: #2e7d32;
            }

            .tab-content {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-top: 1rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            .challenge-card {
            border-left: 5px solid #2e7d32;
            background: #f1f8e9;
            }
            .challenge-card p {
                color: #2e7d32;
                font-weight: 600;
            }

            .btn-add {
                background-color: #2e7d32;
                color: white;
                font-size: 1.4rem;
                border-radius: 10px;
                padding: 10px 24px;
                margin-top: 30px;
            }
            .btn-add:hover {
                background-color: #246428;
            }

            /* QUIZ BUTTONS */
            #quiz .btn-outline-success {
                border: 2px solid #2e7d32;
                color: #2e7d32;
                font-size: 1.3rem;
                border-radius: 10px;
                margin-bottom: 10px;
            }
            #quiz .btn-outline-success:hover {
                background-color: #2e7d32;
                color: white;
            }

            .btn-add {
            background-color: #2e7d32;
            color: white;
            }

            .btn-add:hover {
            background-color: #2e7d32;
            }
            footer {
            background-color: #2e7d32;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 20rem;
            }
        </style>
    </head>
    <body>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
            <img
                src="img/GHLOGO.png"
                alt="Logo"
                width="50"
                height="50"
                class="me-2 rounded-circle"
            />
            Green Horizon | ADMIN
            </a>

            <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown"
            >
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link" href="admin_index.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                <a class="nav-link active" href="admin_challenge&quiz.php"
                    >Challenges & Quiz</a
                >
                </li>
                <li class="nav-item">
                <a class="nav-link" href="admin_reports.php">Reports</a>
                </li>
            </ul>
            <a href="landing.html" class="btn btn-outline-light mx-4">Log Out</a>
            </div>
        </nav>

        <!-- COVER + GROUP INFO -->
        <div class="cover-photo" id="coverPhoto">
            <h1 class="cover-title">Green Horizon<br>Challenges and Quizzes</h1>
        </div>
    
        <div class="container my-5">
            <!-- MESSAGE MODAL -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
            <?php endif; ?>

            <!-- NAV TABS -->
            <ul class="nav nav-tabs" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button
                    class="nav-link active"
                    id="challenges-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#challenges"
                    type="button"
                    role="tab"
                    >
                    <h2 class="fw-bold text-success mb-4">
                        <i class="bi bi-trophy-fill me-2"></i>Challenges
                    </h2>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button
                    class="nav-link"
                    id="quiz-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#quiz"
                    type="button"
                    role="tab"
                    >
                    <h2 class="fw-bold text-success mb-4">
                        <i class="bi bi-trophy-fill me-2"></i>Quiz
                    </h2>
                    </button>
                </li>
            </ul>
        
            <!-- TAB CONTENT -->
            <div class="tab-content" id="adminTabsContent">
                <!-- CHALLENGES TAB -->
                <div class="tab-pane fade show active" id="challenges" role="tabpanel">
                    <div id="challengeList">
                        <?php
                            if ($result_challenge->num_rows > 0) {
                                while ($row = $result_challenge->fetch_assoc()) {
                                    echo '
                                    <div class="card shadow-sm mb-3 challenge-card">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <p class="mb-0 fw-semibold">
                                                <span class="challenge-title">' . htmlspecialchars($row["challenge_title"]) . '</span>
                                                <small class="challenge-points text-muted ms-2">(' . htmlspecialchars($row["challenge_points"]) . ' pts)</small>
                                            </p>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item edit-btn" 
                                                        data-id="' . $row["challenge_id"] . '"
                                                        data-challenge="' . htmlspecialchars($row["challenge_title"]) . '"
                                                        data-points="' . htmlspecialchars($row["challenge_points"]) . '">
                                                        Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item text-danger delete-btn"
                                                                data-id="' . $row["challenge_id"] . '"
                                                                type="button">
                                                            Delete
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '<p class="text-muted text-center">No challenges found.</p>';
                            }
                            $conn->close();
                        ?>
                    </div>

                    <!-- ADD CHALLENGE BUTTON -->
                    <div class="text-center mt-4">
                        <button class="btn btn-add px-4 fs-4" data-bs-toggle="modal" data-bs-target="#addChallengeModal">
                            <i class="bi bi-plus-lg me-1"></i> Add Challenge
                        </button>
                    </div>
                </div>

                <!-- QUIZ TAB -->
                <div class="tab-pane fade" id="quiz" role="tabpanel">
                    <div class="d-grid gap-3">
                        <?php
                            if ($result_quiz->num_rows > 0) {
                                while ($quiz = $result_quiz->fetch_assoc()) {
                                    echo '
                                    <button
                                        class="btn btn-outline-success py-3 fw-semibold"
                                        onclick="window.location.href=\'admin_challenge&quiz.php?quiz_id=' . $quiz['quiz_id'] . '\'">
                                        Level ' . htmlspecialchars($quiz['quiz_level']) . ': ' . htmlspecialchars($quiz['quiz_title']) . '
                                    </button>';
                                }
                            } else {
                                echo '<p class="text-muted text-center">No quizzes found.</p>';
                            }
                        ?>
                    </div>

                    <div class="text-center mt-4">
                        <button
                            class="btn btn-add px-4 fs-4"
                            onclick="window.location.href='admin_addQuiz.php'">
                            <i class="bi bi-plus-lg me-1"></i> Add Quiz
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD CHALLENGE MODAL -->
        <div class="modal fade" id="addChallengeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <form action="admin_controllerChallenge&Quiz.php" method="POST">
                    <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Add New Challenge</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <label class="form-label">Challenge Title/Description:</label>
                    <input type="text" name="challenge_title" class="form-control mb-3" placeholder="Enter challenge title/description" required>

                    <label class="form-label">Challenge Points (Carbon Equivalent):</label>
                    <input type="number" name="challenge_points" class="form-control" placeholder="Enter challenge points (e.g., 50)" required>
                    </div>
                    <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-success" type="submit" name="add_challenge">Add</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- EDIT CHALLENGE MODAL -->
        <div class="modal fade" id="editChallengeModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                <form action="admin_controllerChallenge&Quiz.php" method="POST">
                    <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Edit Challenge</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                    <input type="hidden" id="editChallengeId" name="challenge_id">

                    <label class="form-label">Challenge Title/Description:</label>
                    <input type="text" id="editChallengeTitle" name="challenge_title" class="form-control mb-3" required>

                    <label class="form-label">Challenge Points (Carbon Equivalent):</label>
                    <input type="number" id="editChallengePoints" name="challenge_points" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-success" type="submit" name="update_challenge">Save Changes</button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <!-- VIEW QUIZ QUESTIONS MODAL -->
        <div class="modal fade" id="viewQuizModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white p-3">
                        <h5 class="modal-title">View Questions</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3">
                        <div>
                        <?php if ($quiz_data): ?>
                            <h3 class="fw-bold text-success mb-0">
                            Level <?= htmlspecialchars($quiz_data['quiz_level']) ?>:
                            <?= htmlspecialchars($quiz_data['quiz_title']) ?>
                            </h3>
                        <?php else: ?>
                            <h3 class="fw-bold text-danger mb-0">Quiz not found.</h3>
                        <?php endif; ?>
                        </div>

                        <!-- Dropdown Menu -->
                        <?php if ($quiz_data): ?>
                        <div class="dropdown">
                        <button class="btn btn-light btn-sm border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fs-5 text-success"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                            <a class="dropdown-item" href="admin_editQuiz.php?quiz_id=<?= htmlspecialchars($quiz_data['quiz_id']) ?>">
                                Edit
                            </a>
                            </li>
                            <li>
                            <button class="dropdown-item text-danger delete-btn-quiz" 
                                    data-id="<?= htmlspecialchars($quiz_data['quiz_id']) ?>" 
                                    type="button">
                                Delete
                            </button>
                            </li>
                        </ul>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Body Section -->
                    <div class="modal-body">
                        <?php if (isset($questions_result) && $questions_result->num_rows > 0): ?>
                            <?php $count = 1; while ($row = $questions_result->fetch_assoc()): ?>
                                <div class="mb-4 px-4">
                                    <p class="fw-semibold mb-2">
                                        <?= $count++ ?>. <?= htmlspecialchars($row['question_text']) ?>
                                    </p>
                                    <ul class="list-group mb-2">
                                        <li class="list-group-item <?= ($row['correct_answer'] == $row['option1']) ? 'list-group-item-success' : '' ?>">
                                            <?= htmlspecialchars($row['option1']) ?>
                                        </li>
                                        <li class="list-group-item <?= ($row['correct_answer'] == $row['option2']) ? 'list-group-item-success' : '' ?>">
                                            <?= htmlspecialchars($row['option2']) ?>
                                        </li>
                                        <li class="list-group-item <?= ($row['correct_answer'] == $row['option3']) ? 'list-group-item-success' : '' ?>">
                                            <?= htmlspecialchars($row['option3']) ?>
                                        </li>
                                        <li class="list-group-item <?= ($row['correct_answer'] == $row['option4']) ? 'list-group-item-success' : '' ?>">
                                            <?= htmlspecialchars($row['option4']) ?>
                                        </li>
                                    </ul>

                                    <p class="text-end fw-semibold text-secondary mb-0">
                                        Points: <?= htmlspecialchars($row['question_points']) ?>
                                    </p>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-center text-muted">No questions found for this quiz.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            let currentEditCard = null;

            // Handle Edit Challenge 
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.card');
                    const challengeId = this.closest('.dropdown').querySelector('.delete-btn').getAttribute('data-id');
                    const title = card.querySelector('.challenge-title').textContent.trim();
                    const points = card.querySelector('.challenge-points').textContent.replace(/[^\d]/g, '').trim();


                    document.getElementById('editChallengeId').value = challengeId;
                    document.getElementById('editChallengeTitle').value = title;
                    document.getElementById('editChallengePoints').value = points;

                    const editModal = new bootstrap.Modal(document.getElementById('editChallengeModal'));
                    editModal.show();
                });
            });

            // View Quiz Modal
            document.addEventListener("DOMContentLoaded", () => {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has("quiz_id")) {
                    const quizModal = new bootstrap.Modal(document.getElementById("viewQuizModal"));
                    quizModal.show();
                }
            });

            // Handle Delete Challenge
            document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const challengeId = this.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this challenge?')) {
                window.location.href = `admin_controllerChallenge&Quiz.php?delete=${challengeId}`;
                }
            });
            });

            // Handle Delete Quiz
            document.querySelectorAll('.delete-btn-quiz').forEach(btn => {
                btn.addEventListener('click', function() {
                    const quizId = this.getAttribute('data-id'); // 

                    if (confirm('Are you sure you want to delete this quiz?')) {
                        window.location.href = `admin_controllerChallenge&Quiz.php?delete_quiz=${quizId}`;
                    }
                });
            });
        </script>
    </body>
</html>
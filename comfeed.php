<?php
    include 'controllerProfile.php';

    $result_event = $conn->query("SELECT * FROM `event` WHERE event_status = 'upcoming' ORDER BY event_start_datetime ASC");

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Community</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            body {
            background-color: #f2fbf2; /* light green background instead of white */
            /* FONT APPLIED: Poppins */
            font-family: 'Poppins', sans-serif;
            color: #2E7D32; /* base green for text */
            scroll-behavior: smooth;
            }
            /* NAVBAR */
            .navbar {
                background-color: #2E7D32;
            }
            .navbar-brand, .nav-link {
                color: #ffffff !important;
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
            /* GROUP INFO */
            .group-info {
                background-color: #E8F5E9; /* light green box */
                padding: 20px;
                text-align: center;
                border-top: 2px solid #2E7D32;
                border-bottom: 2px solid #2E7D32;
            }
            .group-info h1 {
                font-weight: 700;
                color: #2E7D32;
            }
            .group-info p {
                color: #33691E; /* deeper tone for contrast */
            }

            /* EVENTS SECTION */
            .events-section .card {
                border-left: 5px solid #2E7D32;
                background-color: #ffffff;
            }
            .events-section h4 {
                color: #2E7D32;
            }
            .card-title {
                color: #2E7D32;
            }
            .card-text {
                color: #1b5e20;
            }

            /* BUTTONS */
            .btn-success, .btn-outline-success:hover {
                background-color: #2E7D32 !important;
                color: #fff !important;
                border-color: #2E7D32 !important;
            }
            .btn-outline-success {
                color: #2E7D32 !important;
                border-color: #2E7D32 !important;
            }
            .btn-outline-success:hover {
                background-color: #1b5e20 !important;
            }

            /* POSTS SECTION */
            .posts-section h4 {
                color: #2E7D32;
            }
            .posts-section .card {
                border: 1px solid #C8E6C9;
                background-color: #ffffff;
            }
            .posts-section p {
                color: #1b5e20;
            }
            .profile-img {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: 50%;
                border: 2px solid #2E7D32;
            }
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

            /* MODAL */
            .modal-header.bg-success {
                background-color: #2E7D32 !important;
            }
            .btn-danger {
                background-color: #C62828 !important;
                border-color: #C62828 !important;
            }
            .btn-danger:hover {
                background-color: #b71c1c !important;
            }

            /* SECTION BACKGROUNDS */
            .achieved-events {
                background-color: #E8F5E9; /* consistent soft green background */
            }
        </style>
    </head>
    <body>
        <!--NAV BAR-->
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
                    <li class="nav-item"><a class="nav-link active" href="comfeed.php?user_id=<?= htmlspecialchars($user_id) ?>">Community Feed</a></li>
                    <li class="nav-item"><a class="nav-link" href="leaderboard.php?user_id=<?= htmlspecialchars($user_id) ?>">Leaderboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="donate.php?user_id=<?= htmlspecialchars($user_id) ?>">Donate!</a></li>
                </ul>

                <div class="dropdown me-3">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        🔔
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item fw-bold" href="#">You completed a challenge!</a></li>
                        <li><a class="dropdown-item" href="#">You are top 1 in the monthly Leaderboard!</a></li>
                        <li><a class="dropdown-item" href="#">Someone liked your post</a></li>
                    </ul>
                </div>

                <a href="landing.html" class="btn btn-outline-light" type="button">Log Out</a>
            </div>
        </nav>
        <div class="cover-photo" id="coverPhoto">
            <h1 id="coverTitle">Community Feed</h1>

        </div>

        <!--UPCOMING EVENT SECTION-->
        <section class="events-section py-4">
            <div class="container">
                <h4 class="text-success fw-bold mb-3">EVENT ANNOUNCEMENT</h4>

                <?php if ($result_event && $result_event->num_rows > 0): ?>
                    <?php while ($event = $result_event->fetch_assoc()): ?>
                        <div class="card shadow-sm mb-4 position-relative">
                            <div class="card-body">
                                <!-- Event Content -->
                                <h5 class="card-title text-success fw-bold">
                                    <?= htmlspecialchars($event['event_title']); ?>
                                </h5>

                                <p class="card-text">
                                    <?= nl2br(htmlspecialchars($event['event_caption'])); ?>
                                </p>

                                <p class="text-muted">
                                    <strong>Date:</strong> 
                                    <?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_start_datetime']))); ?> 
                                    – 
                                    <?= htmlspecialchars(date('F d, Y h:i A', strtotime($event['event_end_datetime']))); ?> 
                                    <br>
                                    <strong>Location:</strong> <?= htmlspecialchars($event['event_location']); ?> 
                                    <br>
                                    <strong>Funds:</strong> <?= htmlspecialchars($event['event_fund']); ?>
                                </p>

                                <button class="btn btn-success py-2 px-4 fs-5"
                                        onclick="window.location.href='event_signup.php?event_id=<?= $event['event_id'] ?>&user_id=<?= $user_id ?>'">
                                    SIGN UP
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-secondary text-center">
                        No upcoming events found.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!--ACHIEVED EVENTS-->
        <section class="achieved-events py-4 bg-light">
            <div class="container">
                <h4 class="text-success fw-bold mb-3">ACHIEVED EVENTS</h4>

                <?php
                // Fetch all completed events with their posts
                $query = $conn->prepare("
                    SELECT 
                        e.event_id, e.event_title, e.event_location, 
                        e.event_start_datetime, e.event_end_datetime,
                        ep.eventPost_id, ep.post_date,
                        pd.postDetails_id, pd.caption, pd.img1, pd.img2, pd.img3, pd.img4, pd.img5
                    FROM event e
                    LEFT JOIN event_post ep ON e.event_id = ep.event_id
                    LEFT JOIN post_details pd ON ep.postDetails_id = pd.postDetails_id
                    WHERE e.event_status = 'completed'
                    ORDER BY e.event_start_datetime DESC
                ");
                $query->execute();
                $events = $query->get_result();

                if ($events->num_rows === 0):
                ?>
                    <p class="text-muted">No achieved events found.</p>
                <?php
                else:
                    while ($row = $events->fetch_assoc()):
                        $event_id = $row['event_id'];
                        $title = $row['event_title'];
                        $location = $row['event_location'];

                        $startDate = date("F d, Y h:i A", strtotime($row['event_start_datetime']));
                        $endDate   = date("F d, Y h:i A", strtotime($row['event_end_datetime']));

                        $caption = $row['caption'];
                        $postDate = date("F d, Y", strtotime($row['post_date']));

                        // Collect images
                        $images = [];
                        for ($i = 1; $i <= 5; $i++) {
                            if (!empty($row["img$i"])) $images[] = $row["img$i"];
                        }
                ?>

                <!-- Event Card -->
                <div class="card shadow-sm mb-4 position-relative">
                    <div class="card-body">

                        <!-- Title + Location + Date -->
                        <h6 class="mb-0 fw-bold text-success"><?= htmlspecialchars($title) ?></h6>
                        <small class="text-muted">
                            <?= $startDate ?> – <?= $endDate ?> • <?= htmlspecialchars($location) ?>
                        </small>

                        <!-- Caption -->
                        <p class="mt-2 mb-3"><?= nl2br(htmlspecialchars($caption)) ?></p>

                        <!-- Images -->
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($images as $img): ?>
                                <img src="<?= htmlspecialchars($img) ?>" class="rounded" height="250" width="275">
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>

                <?php
                    endwhile;
                endif;
                ?>
            </div>
        </section>

        <!--ALL USER POST-->
        <section class="posts-section py-3">
            <div class="container">
                <h4 class="text-success fw-bold">PUBLIC POST</h4>

                <?php
                // Fetch posts with user info and post details, excluding the current user
                $sql = "SELECT 
                            ccp.completion_id,
                            ccp.daily_id,
                            ccp.post_likes,
                            ccp.post_date,
                            pd.caption,
                            pd.img1, pd.img2, pd.img3, pd.img4, pd.img5,
                            u.user_fname, u.user_lname, u.user_profpic
                        FROM challenge_completion_post ccp
                        JOIN post_details pd ON ccp.postDetails_id = pd.postDetails_id
                        JOIN user u ON ccp.user_id = u.user_id
                        WHERE ccp.user_id != ?
                        ORDER BY ccp.post_date DESC";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $posts = $stmt->get_result();

                if ($posts && $posts->num_rows > 0):
                    while ($post = $posts->fetch_assoc()):
                        $profilepic = htmlspecialchars($post['user_profpic']);
                        $fullname = htmlspecialchars($post['user_fname'] . ' ' . $post['user_lname']);
                        $timeAgo = date('M d, Y H:i', strtotime($post['post_date']));
                        $caption = nl2br(htmlspecialchars($post['caption']));
                        $likes = intval($post['post_likes']);

                        // Get challenge title from daily_challenge -> challenges
                        $stmt1 = $conn->prepare("SELECT challenge_id FROM daily_challenge WHERE daily_id = ?");
                        $stmt1->bind_param("i", $post['daily_id']);
                        $stmt1->execute();
                        $res1 = $stmt1->get_result()->fetch_assoc();
                        $stmt1->close();

                        $title = '';
                        if ($res1) {
                            $stmt2 = $conn->prepare("SELECT challenge_title FROM challenges WHERE challenge_id = ?");
                            $stmt2->bind_param("i", $res1['challenge_id']);
                            $stmt2->execute();
                            $res2 = $stmt2->get_result()->fetch_assoc();
                            $stmt2->close();
                            if ($res2) {
                                $title = htmlspecialchars($res2['challenge_title']);
                            }
                        }
                ?>
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $profilepic ?>" width="50" height="50" class="rounded-circle me-3">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-success"><?= $fullname ?></h6>
                                            <small class="text-muted"><?= $timeAgo ?></small>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item text-danger" href="#">Report Post</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <h5 class="mt-3"><?= $title ?></h5>
                                <p><?= $caption ?></p>

                                <div class="d-flex flex-wrap gap-3">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        $imgField = 'img' . $i;
                                        if (!empty($post[$imgField])) {
                                            echo '<img src="' . htmlspecialchars($post[$imgField]) . '" class="rounded" height="200" width="230">';
                                        }
                                    }
                                    ?>
                                </div>

                                <button class="btn btn-outline-success btn-sm mt-3 fs-3 text-center px-5"><?= $likes ?> 💗</button>
                            </div>
                        </div>
                <?php
                    endwhile;
                else:
                ?>
                    <div class="alert alert-secondary text-center">
                        No posts found.
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow </p>
        </footer>

        <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold" id="reportModalLabel">Report Post</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2 fw-semibold">Select reason(s) for reporting:</p>

                        <form id="reportForm">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="spam" name="reason" value="Spam or misleading">
                            <label class="form-check-label" for="spam">Spam or misleading</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="harassment" name="reason" value="Harassment or hate speech">
                            <label class="form-check-label" for="harassment">Harassment or hate speech</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="falseInfo" name="reason" value="False Information">
                            <label class="form-check-label" for="falseInfo">False information</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inappropriate" name="reason" value="Inappropriate or offensive content">
                            <label class="form-check-label" for="inappropriate">Inappropriate or offensive content</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="other" name="reason" value="Other">
                            <label class="form-check-label" for="other">Other</label>
                        </div>

                        <div class="mb-3">
                            <label for="reportDescription" class="form-label">Additional details (optional):</label>
                            <textarea id="reportDescription" class="form-control" rows="3" placeholder="Describe the issue..."></textarea>
                        </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button id="submitReportBtn" type="button" class="btn btn-danger">Report</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Signup Modal -->
        <div class="modal fade" id="signupSuccessModal" tabindex="-1" aria-labelledby="signupSuccessLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="signupSuccessLabel">Signup Successful!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <h4 class="text-success fw-bold mb-2">🎉 You're in!</h4>
                        <p class="mb-0">You have successfully signed up for this event.</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" data-bs-dismiss="modal">OK</button>
                    </div>

                </div>
            </div>
        </div>
        <?php if (isset($_GET['signup']) && $_GET['signup'] == 1): ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var signupModal = new bootstrap.Modal(document.getElementById('signupSuccessModal'));
                    signupModal.show();
                });
            </script>
        <?php endif; ?>

        <script>
            // Open modal when "Report Post" clicked
            document.querySelectorAll('.dropdown-item.text-danger').forEach(btn => {
                btn.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = new bootstrap.Modal(document.getElementById('reportModal'));
                    modal.show();
                });
            });

            // Handle report submission
            document.getElementById('submitReportBtn').addEventListener('click', () => {
                const checkedReasons = Array.from(document.querySelectorAll('input[name="reason"]:checked'))
                .map(cb => cb.value);
                const description = document.getElementById('reportDescription').value.trim();

                if (checkedReasons.length === 0) {
                alert('⚠️ Please select at least one reason to report.');
                return;
                }

                // Replace modal content with success message
                const modalBody = document.querySelector('#reportModal .modal-body');
                modalBody.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-success fw-bold">Post Reported</h5>
                    <p class="text-muted mb-0">Thank you for helping keep the community safe and respectful.</p>
                </div>
                `;

                // Replace footer with OK button
                const modalFooter = document.querySelector('#reportModal .modal-footer');
                modalFooter.innerHTML = `
                <button id="okBtn" type="button" class="btn btn-success">OK</button>
                `;

                // When OK clicked → close modal & reset content
                document.getElementById('okBtn').addEventListener('click', () => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
                    modal.hide();
                    resetReportModal();
                });
            });

            // Reset modal to its original content
            function resetReportModal() {
                const originalContent = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold" id="reportModalLabel">Report Post</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2 fw-semibold">Select reason(s) for reporting:</p>
                        <form id="reportForm">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="spam" name="reason" value="Spam or misleading">
                            <label class="form-check-label" for="spam">Spam or misleading</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="harassment" name="reason" value="Harassment or hate speech">
                            <label class="form-check-label" for="harassment">Harassment or hate speech</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="falseInfo" name="reason" value="False Information">
                            <label class="form-check-label" for="falseInfo">False information</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inappropriate" name="reason" value="Inappropriate or offensive content">
                            <label class="form-check-label" for="inappropriate">Inappropriate or offensive content</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="other" name="reason" value="Other">
                            <label class="form-check-label" for="other">Other</label>
                        </div>
                        <div class="mb-3">
                            <label for="reportDescription" class="form-label">Additional details (optional):</label>
                            <textarea id="reportDescription" class="form-control" rows="3" placeholder="Describe the issue..."></textarea>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button id="submitReportBtn" type="button" class="btn btn-danger">Report</button>
                    </div>
                    </div>
                </div>
                `;
                document.getElementById('reportModal').innerHTML = originalContent;

                // Reattach event listeners to the new modal structure
                const newSubmitBtn = document.querySelector('#reportModal #submitReportBtn');
                if (newSubmitBtn) {
                    newSubmitBtn.addEventListener('click', document.getElementById('submitReportBtn').onclick);
                }
            }
            
            // Fix for dynamic button handler reset issue in this specific implementation:
            document.addEventListener('click', (e) => {
                if (e.target.matches('[data-bs-dismiss="modal"]')) {
                const modalElement = document.getElementById('reportModal');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                    resetReportModal();
                }
                }
            });

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
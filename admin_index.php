<?php
    include 'admin_controllerEvent.php';

    $result_event = $conn->query("SELECT * FROM `event` WHERE event_status = 'upcoming' ORDER BY event_start_datetime ASC");

    $grossFunds = 0;
    $result =  $conn->query("SELECT gross_funds FROM `admin` WHERE admin_id = 1");
    if ($result && $row = $result->fetch_assoc()) {
        $grossFunds = (float)$row['gross_funds'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Admin</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet"> 

        <style>
            body {
                background-color: #F9FAF8;
                font-family: 'Poppins', sans-serif;
                padding-top: 75px;
                color: #2E7D32; /* Global font color */
                margin: 0;
            }
            /* NAVBAR */
            .navbar {
                background-color: #2E7D32;
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
                    background-color: #A8E063;
                    transition: width 0.3s;
                }
                .navbar-nav .nav-item .nav-link:hover::after {
                    width: 100%;
                }
                .navbar-nav .nav-item .nav-link:hover {
                    color: #A8E063 !important;
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
                transition: font-size 0.4s ease, transform 0.4s ease;
            }


            /* Shrinking effect */
            .cover-photo.shrink {
                height: 300px !important;
            }
            .shrink h1 {
                font-size: 2.5rem !important;
                transform: translateY(-10px); /* smooth lift when shrinking */
            }
            .group-info {
                background-color: #fff;
                padding: 15px;
                border-top: 1px solid #e0e0e0;
                border-bottom: 1px solid #e0e0e0;
                text-align: center;
            }
            .group-info h1 {
                font-weight: 700;
                color: #2E7D32;
            }
            .group-info p {
                color: #2E7D32;
            }

            /* EVENT SECTION */
            .events-section h4,
            .achieved-events h4,
            .posts-section h4 {
                color: #2E7D32;
            }
            .events-section .card {
                border-left: 5px solid #2E7D32;
            }

            /* Buttons */
            .btn-success {
                background-color: #2E7D32 !important;
                border-color: #2E7D32 !important;
            }
            .btn-success:hover {
                background-color: #256B2B !important;
                border-color: #256B2B !important;
            }
            .btn-outline-success {
                color: #2E7D32 !important;
                border-color: #2E7D32 !important;
            }
            .btn-outline-success:hover {
                background-color: #2E7D32 !important;
                color: white !important;
            }

            /* Text Highlights */
            h4, h5, h6, strong {
                color: #2E7D32;
            }

            /* Posts */
            .posts-section h5 {
                color: #2E7D32;
            }
            .posts-section .fw-bold {
                color: #2E7D32;
            }

            /* Heart button (like) */
            .btn-outline-success.btn-sm {
                border-color: #2E7D32 !important;
                color: #2E7D32 !important;
            }
            .btn-outline-success.btn-sm:hover {
                background-color: #2E7D32 !important;
                color: white !important;
            }

            /* Dropdown icons */
            .bi-three-dots-vertical.text-success {
                color: #2E7D32 !important;
            }

            /* Scrollbar Style */
            .scroll-area::-webkit-scrollbar {
                width: 8px;
            }
            .scroll-area::-webkit-scrollbar-thumb {
                background-color: #A5D6A7;
                border-radius: 10px;
            }

            /* Modal header */
            .modal-header.bg-success {
                background-color: #2E7D32 !important;
            }

            /* Hover effects for cards */
            .card:hover {
                transform: scale(1.01);
                transition: all 0.3s ease-in-out;
                box-shadow: 0 6px 18px rgba(46, 125, 50, 0.2);
            }

            /* Footer */
            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                margin-top: 3rem;
            }
        </style>
    </head>
    <body>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
            <img
                src="img/GHLOGO.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle"/>
            Green Horizon | ADMIN
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="admin_index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_challenge&quiz.php">Challenges & Quiz</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_reports.php">Reports</a></li>
                </ul>

                <a href="landing.html" class="btn btn-outline-light mx-4">Log Out</a>
            </div>
        </nav>

        <!-- COVER + GROUP INFO -->
        <div class="cover-photo" id="coverPhoto">
            <h1 id="coverTitle">Green Horizon Community</h1>

        </div>

        <!-- MESSAGE -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show container mt-3" role="alert">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
        <?php endif; ?>


        <!-- EVENTS SECTION -->
        <section class="events-section py-4">
            <div class="container">
                <h4 class="text-success fw-bold mb-3">EVENT ANNOUNCEMENT</h4>

                <?php if ($result_event && $result_event->num_rows > 0): ?>
                    <?php while ($event = $result_event->fetch_assoc()): ?>
                        <div class="card shadow-sm mb-4 position-relative">
                            <div class="card-body">

                                <!-- Dropdown Menu -->
                                <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                                    <button class="btn btn-light btn-sm border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical fs-5 text-success"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a 
                                                class="dropdown-item view-participants-btn"
                                                data-event-id="<?= $event['event_id'] ?>">
                                                View Participants
                                            </a>
                                            <a 
                                                class="dropdown-item edit-btn"
                                                data-id="<?= $event['event_id'] ?>"
                                                data-title="<?= htmlspecialchars($event['event_title']); ?>"
                                                data-caption="<?= htmlspecialchars($event['event_caption']); ?>"
                                                data-location="<?= htmlspecialchars($event['event_location']); ?>"
                                                data-start="<?= htmlspecialchars($event['event_start_datetime']); ?>"
                                                data-end="<?= htmlspecialchars($event['event_end_datetime']); ?>"
                                                data-fund="<?= htmlspecialchars($event['event_fund']); ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" 
                                            href="admin_controllerEvent.php?delete_event=<?= $event['event_id'] ?>"
                                            onclick="return confirm('Are you sure you want to delete this event?');">
                                            Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>

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
                                        onclick="window.location.href='admin_postEvent.php?event_id=<?= $event['event_id'] ?>'">
                                    DONE
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-secondary text-center">
                        No upcoming events found.
                    </div>
                <?php endif; ?>

                <!-- ADD EVENT BUTTON -->
                 <div class="text-center mt-4">
                    <button class="btn btn-outline-success btn-lg px-5 py-2" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-circle me-2"></i> Add Event
                    </button>
                </div>
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

                        <!-- Dropdown (Edit/Delete) -->
                        <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                            <button class="btn btn-light btn-sm border-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical fs-5 text-success"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="admin_editPost.php?eventPost_id=<?= $row['eventPost_id'] ?>">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="admin_deletePost.php?eventPost_id=<?= $row['eventPost_id'] ?>">Delete</a></li>
                            </ul>
                        </div>

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
                // Fetch posts with user info and post details
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
                        ORDER BY ccp.post_date DESC";

                $posts = $conn->query($sql);

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
                                            <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
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
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <!-- ADD EVENT MODAL -->
        <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-white fw-bold" id="addEventModalLabel">Create New Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="admin_controllerEvent.php" method="POST">
                        <label class="form-label fw-semibold">Event Title</label>
                        <input type="text" name="event_title" class="form-control mb-3" placeholder="Enter event title" required>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Caption / Description</label>
                        <textarea name="event_caption" class="form-control" rows="4" placeholder="Write event description..." required></textarea>
                    </div>

                    <label class="form-label fw-semibold">Location</label>
                    <input type="text" name="event_location" class="form-control mb-3" placeholder="Enter location" required>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Start Date & Time</label>
                        <input type="datetime-local" name="start_datetime" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">End Date & Time</label>
                        <input type="datetime-local" name="end_datetime" class="form-control" required>
                        </div>
                    </div>

                    <!-- ADMIN GROSS FUNDS -->
                    <div class="alert alert-info">
                        <strong>Gross Funds:</strong> ₱<?= number_format($grossFunds, 2) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Fund (optional)</label>
                        <input type="number" name="event_fund" class="form-control" placeholder="Enter fund amount (₱)">
                    </div>

                    <div class="text-center">
                        <button type="submit" name="add_event" class="btn btn-success px-5 py-2 fs-5">Create Event</button>
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 ms-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <!-- EDIT EVENT MODAL -->
        <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold" id="editEventModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="admin_controllerEvent.php" method="POST">
                            <input type="hidden" name="event_id" id="edit_event_id">

                            <label class="form-label fw-semibold">Event Title</label>
                            <input type="text" name="event_title" id="edit_event_title" class="form-control mb-3" required>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Caption / Description</label>
                                <textarea name="event_caption" id="edit_event_caption" class="form-control" rows="4" required></textarea>
                            </div>

                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" name="event_location" id="edit_event_location" class="form-control mb-3" required>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Start Date & Time</label>
                                    <input type="datetime-local" name="start_datetime" id="edit_start_datetime" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">End Date & Time</label>
                                    <input type="datetime-local" name="end_datetime" id="edit_end_datetime" class="form-control" required>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <strong>Gross Funds:</strong> ₱<?= number_format($grossFunds, 2) ?>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Fund (optional)</label>
                                <input type="number" name="event_fund" id="edit_event_fund" class="form-control" placeholder="Enter fund amount (₱)">
                            </div>

                            <div class="text-center">
                                <button type="submit" name="edit_event" class="btn btn-success px-5 py-2 fs-5">Save Changes</button>
                                <button type="button" class="btn btn-outline-secondary px-4 py-2 ms-2" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- PARTICIPANTS MODAL -->
        <div class="modal fade" id="participantsModal" tabindex="-1" aria-labelledby="participantsLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="participantsLabel">Event Participants</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div id="participantsContent" class="table-responsive text-center">
                            <p>Loading...</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.querySelectorAll(".edit-btn").forEach(btn => {
                    btn.addEventListener("click", () => {
                        // Fill modal fields
                        document.getElementById("edit_event_id").value = btn.dataset.id;
                        document.getElementById("edit_event_title").value = btn.dataset.title;
                        document.getElementById("edit_event_caption").value = btn.dataset.caption;
                        document.getElementById("edit_event_location").value = btn.dataset.location;

                        // Convert datetime to input format
                        const toDateTimeLocal = (str) => str ? new Date(str).toISOString().slice(0,16) : "";
                        document.getElementById("edit_start_datetime").value = toDateTimeLocal(btn.dataset.start);
                        document.getElementById("edit_end_datetime").value = toDateTimeLocal(btn.dataset.end);
                        document.getElementById("edit_event_fund").value = btn.dataset.fund;

                        // Show modal
                        new bootstrap.Modal(document.getElementById("editEventModal")).show();
                    });
                });
            });

            //EVENT PARTICIPANTS 
            document.addEventListener('DOMContentLoaded', function () {

                // EDIT BUTTONS
                document.querySelectorAll(".edit-btn").forEach(btn => {
                    btn.addEventListener("click", () => {
                        document.getElementById("edit_event_id").value = btn.dataset.id;
                        document.getElementById("edit_event_title").value = btn.dataset.title;
                        document.getElementById("edit_event_caption").value = btn.dataset.caption;
                        document.getElementById("edit_event_location").value = btn.dataset.location;

                        const toDateTimeLocal = str => str ? new Date(str).toISOString().slice(0,16) : "";
                        document.getElementById("edit_start_datetime").value = toDateTimeLocal(btn.dataset.start);
                        document.getElementById("edit_end_datetime").value = toDateTimeLocal(btn.dataset.end);
                        document.getElementById("edit_event_fund").value = btn.dataset.fund;

                        new bootstrap.Modal(document.getElementById("editEventModal")).show();
                    });
                });

                // VIEW PARTICIPANTS
                document.querySelectorAll('.view-participants-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const eventId = this.getAttribute('data-event-id');

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('participantsModal'));
                        modal.show();

                        // Load participants via AJAX
                        fetch("admin_fetchParticipants.php?event_id=" + eventId)
                            .then(response => response.text())
                            .then(data => {
                                document.getElementById("participantsContent").innerHTML = data;
                            })
                            .catch(error => {
                                document.getElementById("participantsContent").innerHTML =
                                    "<p class='text-danger'>Error loading participants.</p>";
                            });
                    });
                });

            });
        </script>
    </body>
</html>
<?php
    include 'admin_controllerEvent.php';

    $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

    // Fetch event details
    $stmt = $conn->prepare("SELECT * FROM `event` WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();

    if (!$event) {
        die("Event not found.");
    }

    //preload the name and email of the user
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
    $userData = null;

    if ($user_id > 0) {
        $u = $conn->prepare("SELECT CONCAT(user_fname, ' ', user_lname) AS fullname, user_email 
                            FROM user WHERE user_id = ?");
        $u->bind_param("i", $user_id);
        $u->execute();
        $userData = $u->get_result()->fetch_assoc();
    }

    // format event date/time
    function format_event_datetime_range($start_dt, $end_dt) {
        $s = new DateTime($start_dt);
        $e = new DateTime($end_dt);

        // same date (different times)
        if ($s->format('Y-m-d') === $e->format('Y-m-d')) {
            return $s->format('F d, Y') . " • " . $s->format('g:i A') . " — " . $e->format('g:i A');
        }

        // different dates
        return $s->format('F d, Y g:i A') . " — " . $e->format('F d, Y g:i A');
    }

    $event_datetime_display = format_event_datetime_range(
        $event['event_start_datetime'],
        $event['event_end_datetime']
    );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Event Sign-Up</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <style>
        body {
            background-color: #F9FAF8;
            /* FONT APPLIED: Poppins */
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #2E7D32;
        }
        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }
        footer {
            background-color: #2E7D32;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 3rem;
        }
        .event-card {
            max-width: 700px;
            margin: 3rem auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 200px;
            margin-bottom: 300px;
        }
        .btn-success {
            background-color: #2E7D32;
            border: none;
        }
        .btn-success:hover {
            background-color: #256629;
        }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/GHLOGO.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle" />
                Green Horizon
            </a>
        </nav>

        <!--SIGN UP FORM-->
        <div class="event-card">
            <h4 class="fw-bold text-success mb-1"><?= htmlspecialchars($event['event_title']) ?></h4>
            <small class="text-muted d-block mb-3">
                <?= $event_datetime_display ?> • <?= htmlspecialchars($event['event_location']) ?>
            </small>

            <p><?= nl2br(htmlspecialchars($event['event_caption'])) ?></p>

            <hr>

            <h5 class="fw-semibold mb-3">Sign Up for This Event</h5>

            <form method="POST" action="admin_controllerEvent.php">
                <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                <input type="hidden" name="eventSignup" value="1">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" class="form-control" name="fullName"
                        value="<?= htmlspecialchars($userData['fullname']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email Address</label>
                    <input type="email" class="form-control" name="email"
                        value="<?= htmlspecialchars($userData['user_email']) ?>" required>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="comfeed.php?user_id=<?= htmlspecialchars($user_id) ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">Sign Up</button>
                </div>
            </form>
        </div>

        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow </p>
        </footer>
    </body>
</html>
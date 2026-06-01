<?php 
    include 'admin_controllerEvent.php';
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    $donors = $conn->query("SELECT `name`, donation_amount FROM donations ORDER BY donate_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Horizon | Donate</title>
    <link rel="icon" type="image/png" href="img/GHLOGO.png">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            /* Using standard colors based on your design for consistency */
            --color-bg: #f9fff9;
            --color-text: #2E7D32; 
        }

        body {
            background-color: var(--color-bg);
            /* FONT APPLIED: Poppins */
            font-family: 'Poppins', sans-serif;
            color: var(--color-text);
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: 56px; /* Space for fixed navbar */
        }

        /* Floating leaves animation */
        .leaf {
            position: fixed;
            top: -50px;
            width: 30px;
            height: 30px;
            background-image: url('https://cdn-icons-png.flaticon.com/512/616/616408.png');
            background-size: cover;
            opacity: 0.8;
            animation: fall linear infinite;
            z-index: 1;
        }

        @keyframes fall {
            0% {
                transform: translateY(-50px) rotate(0deg);
                opacity: 0.8;
            }
            100% {
                transform: translateY(110vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Navbar */
        .navbar {
            background-color: #2E7D32;
            z-index: 999;
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
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

        /* Donation Section */
        .donation-section {
            background-color: #E8F5E9;
            display: flex;
            justify-content: center;
            padding: 200px 20px 100px;
            position: relative;
            z-index: 2;
        }
        .donation-container {
            background-color: #ffffff;
            border: 1px solid #C8E6C9;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 40px 30px;
            width: 500px;
            text-align: center;
        }
        .donation-container h2 {
            color: #2E7D32;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .donation-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            text-align: left;
            color: #1B5E20;
        }
        .donation-container input {
            padding: 10px;
            border: 1.5px solid #C8E6C9;
            border-radius: 8px;
            background-color: #F9FAF8;
            margin-bottom: 18px;
            font-size: 1rem;
        }
        .donation-container input:focus {
            border-color: #66BB6A;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102,187,106,0.25);
        }
        .donation-container button {
            width: 100%;
            padding: 12px;
            background-color: #2E7D32;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .donation-container button:hover {
            background-color: #256428;
        }
        .donation-footer {
            color: #555;
            font-size: 0.9rem;
            margin-top: 15px;
        }

        /* Donor List Section */
        .donor-section {
            background-color: #F1F8E9;
            padding: 60px 20px;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .donor-box {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 30px 20px;
            margin: 0 auto;
            max-width: 900px;
        }
        .donor-box h3 {
            color: #2E7D32;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .donor-box ul {
            list-style-type: none;
            padding: 0;
        }
        .donor-box li {
            background: #E8F5E9;
            border-radius: 8px;
            margin: 8px 0;
            padding: 10px;
            font-weight: 500;
            color: #1B5E20;
        }

        /* Programs Section */
        .program-section {
            background-color: #E8F5E9;
            padding: 60px 20px;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .program-box {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 30px 20px;
            margin: 0 auto;
            max-width: 900px;
        }
        .program-box h3 {
            color: #2E7D32;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .program-box ul {
            list-style-type: none;
            padding: 0;
        }
        .program-box li {
            background: #C8E6C9;
            border-radius: 8px;
            margin: 8px 0;
            padding: 10px;
            color: #1B5E20;
            font-weight: 500;
        }

        /* Footer */
        footer {
            background-color: #2E7D32;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            font-size: 0.95rem;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
    <!--NAV TAB-->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top px-4">
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
                <li class="nav-item"><a class="nav-link" href="leaderboard.php?user_id=<?= htmlspecialchars($user_id) ?>">Leaderboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="donate.php?user_id=<?= htmlspecialchars($user_id) ?>">Donate!</a></li>
            </ul>

            <div class="dropdown me-3">
                <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    🔔
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item fw-bold" href="#">You completed a challenge!</a></li>
                    <li><a class="dropdown-item" href="#">You are top 1 in the monthly leaderboard!</a></li>
                    <li><a class="dropdown-item" href="#">Someone liked your post</a></li>
                </ul>
            </div>

            <a href="landing.html" class="btn btn-outline-light">Log Out</a>
        </div>
    </nav>

    <section class="donation-section" id="donate">
        <div class="donation-container">
            <h2>Support Our Mission</h2>
            <form id="donationForm" method="POST" action="admin_controllerEvent.php">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                <label for="name">Full Name</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Jane Doe" required>
                <label for="email">Email Address</label>
                <input class="form-control" type="email" id="email" name="email" placeholder="you@example.com" required>
                <label for="amount">Donation Amount (PHP)</label>
                <input class="form-control" type="number" id="amount" name="amount" placeholder="00.00" min="1" required>
                <div class="form-check mb-4">
                    <input class="form-check-input me-3" type="checkbox" id="privacy" required>
                    <label class="form-check-label" for="privacy">
                        I agree to the <a href="#" class="text-success text-decoration-none">Privacy Policy</a>.
                    </label>
                </div>
                <button type="submit" name="makeDonation">Donate Now</button>
            </form>
            <div class="donation-footer">Every contribution helps us grow a greener future.</div>
        </div>
    </section>

    <!--DONATION LIST-->
    <section class="donor-section">
        <div class="donor-box">
            <h3>We Are Grateful for Our Donors 💚</h3>
            <p>Thank you for your generosity and for helping us sustain our environmental projects!</p>
            <ul>
                <?php
                if ($donors->num_rows > 0) {
                    while ($row = $donors->fetch_assoc()) {
                        $name = htmlspecialchars($row['name']);
                        $amount = number_format($row['donation_amount'], 2);
                        echo "<li><strong>{$name}</strong> — Donated ₱{$amount}</li>";
                    }
                } else {
                    echo "<li>No donors yet. Be the first to donate!</li>";
                }
                ?>
            </ul>
        </div>
    </section>

    <section class="program-section">
        <div class="program-box">
            <h3>Where Your Donations Go </h3>
            <p>Your support funds our community programs that make a real difference in protecting our planet.</p>
            <ul>
                <li>Tree Planting Drives — restoring local ecosystems and combating climate change.</li>
                <li>Coastal Clean-Up Projects — protecting marine life from pollution.</li>
                <li>Environmental Education — teaching students sustainable living and waste management.</li>
                <li>Renewable Energy Campaigns — promoting solar and eco-friendly technologies.</li>
            </ul>
        </div>
    </section>

    <footer>
        <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
    </footer>

    <!-- DONATION SUCCESS MODAL -->
    <div class="modal fade" id="donationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h3 class="modal-title">Thank You!</h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p id="donationMessage" class="fs-5 mb-3"></p>
                    <h4 id="donationAmount" class="fw-bold"></h4>
                </div>
            </div>
        </div>
    </div>


    <script>
        const leafCount = 12;
        for (let i = 0; i < leafCount; i++) {
            const leaf = document.createElement('div');
            leaf.classList.add('leaf');
            leaf.style.left = Math.random() * 100 + 'vw';
            leaf.style.animationDuration = 5 + Math.random() * 5 + 's';
            leaf.style.animationDelay = Math.random() * 5 + 's';
            leaf.style.opacity = Math.random();
            leaf.style.transform = `rotate(${Math.random() * 360}deg)`;
            document.body.appendChild(leaf);
        }

        const form = document.getElementById("donationForm");

        form.addEventListener("submit", function(event) {
            const email = document.getElementById("email").value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                event.preventDefault(); // stop only if invalid
                alert("Please enter a valid email address.");
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('donated') === '1') {
                const name = urlParams.get('name') || 'Donor';
                const amount = parseFloat(urlParams.get('amount')).toFixed(2);

                document.getElementById('donationMessage').textContent = `Thank you, ${name}, for your generous donation!`;
                document.getElementById('donationAmount').textContent = `₱${amount}`;

                const donationModal = new bootstrap.Modal(document.getElementById('donationModal'));
                donationModal.show();
            }
        });
    </script>
</body>
</html>
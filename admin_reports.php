    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Green Horizon | Reports</title>
            <link rel="icon" type="image/png" href="img/GHLOGO.png">
            
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
            
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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

            h2.text-success i.bi-flag-fill, h2.text-success {
            color: #d32f2f !important;}

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
                .group-info {
                    background-color: #fff;
                    padding: 15px;
                    border-top: 1px solid #e0e0e0;
                    border-bottom: 1px solid #e0e0e0;
                    text-align: center;
                }
                .group-info h1 {
                    font-weight: 700;
                    color: #2e7d32;
                }
                .report-card {
                    border-left: 5px solid #d32f2f;
                }
                .report-info {
                    background-color: #fff3f3;
                    padding: 15px;
                    border-radius: 10px;
                    margin-bottom: 1rem;
                }
                footer {
                    background-color: #2E7D32;
                    color: white;
                    text-align: center;
                    padding: 1rem 0;
                    margin-top: 3rem;
                }
                .post-images img {
                    border-radius: 10px;
                }
                .btn-discard {
                    background-color: #ccc;
                    color: #333;
                }
                .btn-discard:hover {
                    background-color: #bdbdbd;
                }
                .btn-delete {
                    background-color: #d32f2f;
                    color: white;
                }
                .btn-delete:hover {
                    background-color: #b71c1c;
                }
            </style>
        </head>
        <body>
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
                            <li class="nav-item"><a class="nav-link" href="admin_index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_challenge&quiz.php">Challenges & Quiz</a></li>
                            <li class="nav-item"><a class="nav-link active" href="admin_reports.php">Reports</a></li>
                        </ul>

                        <a href="landing.html" class="btn btn-outline-light mx-4">Log Out</a>
                    </div>
                </nav>

                <div class="cover-photo" id="coverPhoto">
                    <h1 id="coverTitle">Dashboard Reports</h1>

                </div>

                <div class="container my-5">
                    <h2 class="fw-bold text-success mb-4"><i class="bi bi-flag-fill me-2"></i>Reported Posts</h2>

                    <div class="card shadow-sm mb-5 report-card">
                        <div class="card-body p-4">
                            <div class="report-info">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Report Details</h6>
                                <p><strong>Category:</strong> Inappropriate Content</p>
                                <p><strong>Reporter’s Caption:</strong> Contains offensive language in the comments.</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <img src="https://randomuser.me/api/portraits/women/45.jpg" width="50" height="50" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-success">Jane Doe</h6>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3">🌻 Clean-up Drive Success!</h5>
                            <p>Our team successfully cleaned the riverside area today! Thank you to all volunteers who joined us.</p>

                            <div class="d-flex flex-wrap gap-3 post-images">
                                <img src="img/cleanUp.webp" height="340" width="390">
                                <img src="img/cleanUp.webp" height="340" width="390">
                                <img src="img/cleanUp.webp" height="340" width="390">
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-3">
                                <button class="btn btn-discard px-4">Discard Report</button>
                                <button class="btn btn-delete px-4">Delete Post</button>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-5 report-card">
                        <div class="card-body p-4">
                            <div class="report-info">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Report Details</h6>
                                <p><strong>Category:</strong> Misleading Information</p>
                                <p><strong>Reporter’s Caption:</strong> The content contains incorrect environmental facts.</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" width="50" height="50" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-success">Mark Santos</h6>
                                        <small class="text-muted">5 hours ago</small>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3">🌾 Urban Gardening Workshop</h5>
                            <p>Learned so much today about composting and sustainable home gardening. Excited to apply these techniques!</p>

                            <div class="d-flex flex-wrap gap-3 post-images">
                                <img src="img/gardening.webp" height="435" width="500">
                                <img src="img/gardening.webp" height="435" width="500">
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-3">
                                <button class="btn btn-discard px-4">Discard Report</button>
                                <button class="btn btn-delete px-4">Delete Post</button>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-5 report-card">
                        <div class="card-body p-4">
                            <div class="report-info">
                                <h6 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Report Details</h6>
                                <p><strong>Category:</strong> Spam or Irrelevant</p>
                                <p><strong>Reporter’s Caption:</strong> This post is repeated multiple times in the feed.</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <img src="https://randomuser.me/api/portraits/women/60.jpg" width="50" height="50" class="rounded-circle me-3">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-success">Liza Ramos</h6>
                                        <small class="text-muted">1 day ago</small>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-3">🌍 Community Recycling Program</h5>
                            <p>We collected over 500 plastic bottles this week! Keep it up, everyone 💚</p>

                            <div class="d-flex flex-wrap gap-3 post-images">
                                <img src="img/recycle.webp" height="200" width="230">
                                <img src="img/recycle.webp" height="200" width="230">
                                <img src="img/recycle.webp" height="200" width="230">
                                <img src="img/recycle.webp" height="200" width="230">
                                <img src="img/recycle.webp" height="200" width="230">
                            </div>

                            <div class="mt-4 d-flex justify-content-end gap-3">
                                <button class="btn btn-discard px-4">Discard Report</button>
                                <button class="btn btn-delete px-4">Delete Post</button>
                            </div>
                        </div>
                    </div>
                </div>

                <footer>
                    <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
                </footer>

                <script>
                    // Confirmation dialogs
                    document.querySelectorAll(".btn-discard").forEach(btn => {
                        btn.addEventListener("click", () => {
                            if (confirm("Discard this report?")) {
                                alert("✅ Report discarded successfully.");
                            }
                        });
                    });

                    document.querySelectorAll(".btn-delete").forEach(btn => {
                        btn.addEventListener("click", () => {
                            if (confirm("Are you sure you want to delete this post?")) {
                                alert("🗑️ Post deleted successfully.");
                            }
                        });
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
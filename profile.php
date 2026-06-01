<?php 
    include 'controllerProfile.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Green Horizon | Profile</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <style>
            :root {
                --color-primary: hsl(140, 50%, 35%);
                --color-primary-dark: hsl(125, 55%, 20%);
                --color-accent: hsl(45, 100%, 60%);
                --color-accent-hover: hsl(45, 95%, 55%);
                --color-bg: hsl(40, 20%, 98%);
                --color-card: hsl(35, 30%, 99%);
                --color-text: hsl(140, 20%, 15%);
                --color-text-secondary: hsl(140, 10%, 45%);
            }
            body { /* Corrected from .body to body */
                background-color: var(--color-bg);
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: var(--color-text);
                line-height: 1.6;
            }
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .container h2{
                margin-top: 200px;
                font-size: 45px;
                color: #2e7d32;
                font-weight: 800;
                text-align:justify;
                margin-bottom: 1.5rem;
                
            }
            /* NAVBAR */
            .navbar {
                background-color: #2E7D32;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            .navbar-brand, .nav-link {
                color: white !important;
                font-weight: bold;
            }
            .nav-link:hover {
                color: #111111 !important;
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
            /* PROFILE HEADER */
            .profile-header {
                text-align: center;
                margin-top: 40px;
                background: #79bb82;
                border-radius: 20px;
                padding: 4rem 3rem;
                box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
                border: 1px solid rgba(129, 212, 250, 0.5);
            }

            .image-upload-box {
                width: 150px;
                height: 150px;
                border: 2px dashed #2E7D32;
                border-radius: 12px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                background-color: #E8F5E9;
                transition: all 0.3s ease;
            }
            .image-upload-box:hover {
                background-color: #C8E6C9;
            }
            .plus-icon {
                font-size: 48px;
                color: #2E7D32;
                font-weight: bold;
            }
            .image-preview img {
                border: 2px solid #2E7D32;
                object-fit: cover;
            }
            
            .profile-pic {
                width: 180px;
                height: 180px;
                border-radius: 50%;
                object-fit: cover;
                border: 6px solid #f5f4f1;
                box-shadow: 0 0 0 5px #2E7D32;
                margin-bottom: 2rem;
                transition: transform 0.3s ease;
            }
            .profile-pic:hover { transform: scale(1.05); }
            .profile-name { font-size: 2.5rem !important; font-weight: 900; color: #163616; }
            .profile-bio { font-size: 1.15rem; color: #010801; font-weight: 600; }

            .dashboard-section, .posts-section {
                margin-top: 5rem;
            }

            .dashboard-card {
                border-radius: 18px;
                padding: 3rem;
                min-height: 170px;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0,0,0,0.07);
                background-color: white;
                width: 100%;
                text-align: center;

            }
            .dashboard-card:hover {
                transform: translateY(-5px);
                background-color: #66BB6A;
                color: white;
                border-color: #FFD54F;
            }
            .card-icon { font-size: 2.5rem; margin-bottom: 1rem; color: #FFD54F; }
            .post-card {
                background-color: white;
                border-radius: 20px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                padding: 2rem;
                margin-bottom: 2.5rem;
                transition: 0.3s ease;
            }
            .post-card:hover {
                border-left: 6px solid #2E7D32;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            }
            .post-content p {
                font-size: 23px;
            }
            /* Edit Modal Styles */
            .photo-upload {
                width: 130px;
                height: 130px;
                cursor: pointer;
                font-size: 60px;
                color: #2E7D32;
                background-color: #F9FAF8;
            }
            .preview-images img {
                width: 130px;
                height: 130px;
                object-fit: cover;
                border-radius: 10px;
                margin-right: 10px;
                margin-bottom: 10px;
                position: relative;
            }
            .preview-images .remove-btn {
                position: absolute;
                top: 5px;
                right: 5px;
                background: red;
                color: white;
                border: none;
                border-radius: 50%;
                font-size: 16px;
                width: 24px;
                height: 24px;
                cursor: pointer;
            }
            @media (max-width: 768px) {
                .dashboard-card {
                    padding: 2rem 1rem;
                }
                .dashboard-card h5 {
                    font-size: 1.1rem;
                }
                .dashboard-card p {
                    font-size: 1.2rem;
                }
                .card-icon {
                    font-size: 2rem;
                }
            }
            
            /* FOOTER */
            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                margin-top: 5rem;
                box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <!--NAV TAB-->
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
                    <li class="nav-item"><a class="nav-link active" href="profile.php?user_id=<?= htmlspecialchars($user_id) ?>">Profile</a></li>
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

        <!--PROFILE INFO-->
        <div class="container">
            <h2 class="text-center">Your Profile</h2>
            <div class="profile-header">
                <img src="<?= htmlspecialchars($userData['user_profpic']) ?>" alt="Profile Picture" class="profile-pic">
                <h3 class="profile-name"><?= htmlspecialchars($userData['user_username']) ?></h3>

                <p class="profile-bio">
                    <?= htmlspecialchars($userData['user_bio']) ?>
                </p>

                <h1 class="text-muted small">
                    <i class="fas fa-seedling me-1"></i>
                    <?= htmlspecialchars($user_rank) ?> | 
                    Joined <?= date("F Y", strtotime($userData['user_joindate'])) ?>
                </h1>

                <button class="btn btn-sm btn-success mt-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-cog"></i> Edit Profile
                </button>
            </div>

            <!--USER DASHBOARD-->
            <div class="dashboard-section">
                <h2 class="text-center mb-5 border-bottom pb-2">Your Eco-Stats</h2>
                <div class="row g-4">
                    <div class="col-sm-6 col-md-3">
                        <div class="dashboard-card">
                            <i class="fas fa-list-check card-icon"></i>
                            <h5 class="card-title">Challenges Done</h5>
                            <p class="fs-3 fw-bold m-0"><?= $challengesDone ?></p>
                            <br>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="dashboard-card">
                            <i class="fas fa-graduation-cap card-icon"></i>
                            <h5 class="card-title">Quiz Level</h5>
                            <p class="fs-3 fw-bold m-0"><?= $quizLevel['quiz_level'] ?></p>
                            <br>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="dashboard-card">
                            <i class="fas fa-fire-alt card-icon"></i>
                            <h5 class="card-title">Streaks</h5>
                            <p class="fs-3 fw-bold m-0"><?= $currentStreak ?> Days</p>
                            <small class="text-muted">Longest: <?= $longestStreak ?> Days</small>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="dashboard-card">
                            <i class="fas fa-star card-icon"></i>
                            <h5 class="card-title">Points</h5>
                            <p class="fs-3 fw-bold m-0"><?= $monthlyPoints ?></p>
                            <small class="text-muted">Overall: <?= $overallPoints ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--USER POST-->
        <div class="container py-5">
            <h2 class="text-center mb-5 border-bottom pb-2">Your Public Posts</h2>

            <?php
            $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

            // Fetch posts for this user
            $postsQuery = $conn->prepare("SELECT pcp.completion_id, pcp.daily_id, pcp.post_date, pcp.post_likes, pd.postDetails_id, pd.caption, pd.img1, pd.img2, pd.img3, pd.img4, pd.img5,
                    u.user_fname, u.user_lname
                FROM challenge_completion_post pcp
                JOIN post_details pd ON pcp.postDetails_id = pd.postDetails_id
                JOIN user u ON pcp.user_id = u.user_id
                WHERE pcp.user_id = ?
                ORDER BY pcp.post_date DESC
            ");
            $postsQuery->bind_param("i", $user_id);
            $postsQuery->execute();
            $resultPosts = $postsQuery->get_result();

            while ($post = $resultPosts->fetch_assoc()):
                $postDetails_id = $post['postDetails_id'];
                // Collect images
                $images = [];
                for ($i = 1; $i <= 5; $i++) {
                    if (!empty($post["img$i"])) $images[] = $post["img$i"];
                }

                $userFullName = $post['user_fname'] . ' ' . $post['user_lname'];
                $timeAgo = date("M d, Y H:i", strtotime($post['post_date']));

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
            <div class="post-card position-relative border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <img src="<?= htmlspecialchars($userData['user_profpic']) ?>" width="50" height="50" class="rounded-circle me-3">
                            <div>
                                <h6 class="mb-0 fw-bold text-success"><?= htmlspecialchars($userFullName) ?></h6>
                                <small class="text-muted"><?= $timeAgo ?></small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item text-success edit-btn" href="edit_post.php?user_id=<?= htmlspecialchars($user_id) ?>&postDetails_id=<?= htmlspecialchars($postDetails_id) ?>">Edit</a></li>
                                <li><a class="dropdown-item text-danger delete-btn" data-bs-toggle="modal" data-bs-target="#deletePostModal">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="mt-3"><?= htmlspecialchars($title) ?></h5>
                    <p><?= htmlspecialchars($post['caption']) ?></p>
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach ($images as $img): ?>
                            <img src="<?= htmlspecialchars($img) ?>" class="rounded" height="250" width="275">
                        <?php endforeach; ?>
                    </div>
                    <button class="btn btn-outline-success btn-sm mt-3 fs-3 text-center px-5"><?= $post['post_likes'] ?> 💗</button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-3">

                    <div class="modal-header border-0">
                        <h5 class="modal-title text-success fw-bold">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="controllerProfile.php" name="editProfile" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        <input type="hidden" name="user_sex" value="<?= $userData['user_sex'] ?>">
                        <input type="hidden" name="user_profpic" value="<?= $userData['user_profpic'] ?>">
                        <div class="modal-body">
                            <label for="profilePicInput" class="fw-bold text-success">Profile Picture:</label>
                            <div class="text-center">
                                <div class="profile-preview position-relative d-inline-block" id="imagePreviewContainer">
                                    <img id="profilePreview" src="<?= htmlspecialchars($userData['user_profpic'] ?: 'default.png') ?>" 
                                        alt="Profile Picture" 
                                        class="img-fluid rounded" 
                                        style="border-radius:12px; max-height:150px;">
                                    <button type="button" id="removeProfileBtn" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle" 
                                        style="width:25px; height:25px; padding:0; display:block;">×</button>
                                </div>

                                <!-- Upload new profile picture -->
                                <div id="uploadProfileBox" class="image-upload-box mt-2" style="display:none; cursor:pointer; margin:0 auto;">
                                    <span class="plus-icon fs-3">+</span>
                                    <input type="file" id="profilePicInput" name="userNew_profpic" accept="image/*" style="display:none;">
                                </div>

                                <div class="form-text mt-2">Upload a JPG, PNG, or GIF (max 5MB).</div>
                            </div>


                            <label class="fw-bold text-success">Username</label>
                            <input type="text" name="username"
                                class="form-control mb-3"
                                value="<?= htmlspecialchars($userData['user_username']) ?>" required>

                            <label class="fw-bold text-success">Bio</label>
                            <textarea name="bio" class="form-control mb-3" rows="3" required><?= htmlspecialchars($userData['user_bio']) ?></textarea>
                        </div>

                        <div class="modal-footer border-0 justify-content-between">
                            <div class="text-start">
                                <a href="forgotpass.html" class="text-success text-decoration-none mb-2">Change Password</a>
                                <button type="button" class="btn btn-outline-danger d-block mt-2" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="editProfile" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!--DELETE ACCOUNT MODAL-->
        <div class="modal fade" id="deleteAccountModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-danger">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold">Confirm Account Deletion</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="deleteAccountForm" method="POST" action="controllerLogIn&Signup.php">
                        <div class="modal-body">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <p class="text-danger fw-semibold">⚠️ This action is permanent and cannot be undone.</p>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Enter your password to confirm</label>
                                <input type="password" class="form-control" name="confirmPassword" placeholder="Enter password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Post Confirmation Modal -->
        <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deletePostLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <h4 class="text-danger fw-bold mb-2">⚠️ Are you sure?</h4>
                        <p class="mb-0">Deleting this post will remove its likes and challenge points from your monthly and gross totals.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="controllerQuiz&Challenge.php">
                            <input type="hidden" name="delete_post" value="1">
                            <input type="hidden" name="daily_id" value="<?= $daily_id ?>">
                            <input type="hidden" name="postDetails_id" value="<?= $postDetails_id ?>">
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <button type="submit" class="btn btn-danger">Delete Post</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            //edit profile
            document.addEventListener('DOMContentLoaded', function() {
                const profilePreview = document.getElementById('profilePreview');
                const removeBtn = document.getElementById('removeProfileBtn');
                const uploadBox = document.getElementById('uploadProfileBox');
                const fileInput = document.getElementById('profilePicInput');

                // Click "+" to open file picker
                uploadBox.addEventListener('click', () => fileInput.click());

                // File input change -> preview new image
                fileInput.addEventListener('change', () => {
                    const file = fileInput.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = e => {
                        profilePreview.src = e.target.result;
                        profilePreview.style.display = 'inline';
                        removeBtn.style.display = 'block';
                        uploadBox.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                });

                // Remove profile picture
                removeBtn.addEventListener('click', () => {
                    profilePreview.style.display = 'none';
                    removeBtn.style.display = 'none';
                    uploadBox.style.display = 'flex';
                    fileInput.value = "";

                    // Hidden input to mark removal in backend
                    let removeInput = document.querySelector('input[name="remove_profile_pic"]');
                    if (!removeInput) {
                        removeInput = document.createElement("input");
                        removeInput.type = "hidden";
                        removeInput.name = "remove_profile_pic";
                        removeInput.value = "1";
                        document.querySelector("#editProfileModal form").appendChild(removeInput);
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('profileEditSuccess') === '1') {
                    alert("✅ Profile updated successfully!");
                }
            });

        </script>
    </body>
</html>
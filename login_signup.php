    <?php
        include 'controllerLogin&Signup.php';

        $result = $conn->query("SELECT user_username, user_email FROM `user`");
        $result_admin = $conn->query("SELECT admin_username FROM `admin`");
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Green Horizon</title>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        
        <style>
            body {
                background-color: #f7fdf7;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: #333;
                scroll-behavior: smooth;
            }
            .h2 {
                color: #1b5e20;
                font-weight: 600;
                text-align:justify;
                margin-bottom: 1.5rem;
            }
            .auth-card {
                max-width: 800px;
                margin: 5% auto;
                margin-top: 140px;
                margin-bottom: 100px;
                background: #fffafb;
                border-radius: 16px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                padding: 6rem;
            }
            .logo {
                width: 150px;
                height: auto;
                margin-bottom: 1rem;
            }
            .nav-link {
                color: #333333;
            }
            .btn-green {
                background-color: #2E7D32;
                color: #fff;
                transition: 0.3s ease;
            }
            .btn-green:hover {
                background-color: #256428;
            }
            .btn-green:disabled {
                background-color: #9E9E9E;
                cursor: not-allowed;
                border: none;
            }
            .link-accent {
                color: #0f0f0e;
                font-weight: 600;
                text-decoration: none;
            }
            .link-accent:hover {
                text-decoration: underline;
            }
            .form-control, .form-select {
                background: #FFFBF9;
                color: #333333;
            }
            .form-control:focus, .form-select:focus {
                border-color: #66BB6A;
                box-shadow: 0 0 0 0.25rem rgba(102,187,106,0.3);
            }
            .profile-upload {
                height: 70px;
                padding: 20px 10px;
                border-radius: 12px;
                background-color: #F9FAF8;
                transition: 0.3s ease;
                cursor: pointer;
            }
            .fade-step {
                display: none;
                opacity: 0;
                transition: opacity 0.5s ease;
            }
            .fade-step.active {
                display: block;
                opacity: 1;
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
            /* FOOTER */
            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                margin-top: 12rem;
            }
        </style>

    </head>
    <body>
        <div class="auth-card text-center">
            <img src="img/GHLOGO.png" alt="Green Horizon Logo" class="logo">

            <h1 class="mb-0" style="color:#2E7D32;">Welcome to Green Horizon</h1>
            <p class="mb-5">“Welcome! Be part of a greener tomorrow — sign in or create your account today.”</p>
            
            <!-- NAVTABS -->
            <ul class="nav nav-tabs mb-4 justify-content-center">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login" type="button"><h5>Log In</h5></button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#signup" type="button"><h5>Sign Up</h5></button>
                </li>
            </ul>

            <!--TAB CONTENT-->
            <div class="tab-content">
                <!-- MESSAGE -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['msg_type']; ?> alert-dismissible fade show container mt-3" role="alert">
                        <?php echo $_SESSION['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
                <?php endif; ?>
                
                <!--LOGIN TAB-->
                <div class="tab-pane fade show active" id="login">
                    <form method="POST" id="loginForm" action="controllerLogin&Signup.php">
                        <div class="mb-4">
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="mb-4">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-green w-100" name="login"><h5>Log In</h5></button>
                    </form>
                    <p class="mt-4"><a href="forgotpass.html" class="link-accent">Forgot Password?</a></p>
                </div>

                <!--SIGNUP TAB-->
                <div class="tab-pane fade" id="signup">
                    <form id="signupForm" method="POST" action="controllerLogin&Signup.php" enctype="multipart/form-data">
                        <!--SIGNUP 1ST TAB-->
                        <div id="step1" class="fade-step active text-start">
                            <div class="row mb-3">
                                <div class="col-4 text-center">
                                    <label for="profilePicInput" class="form-label fw-semibold">Profile Picture (Optional):</label>
                                    <div class="image-upload-box mx-auto" id="uploadBox">
                                        <span class="plus-icon">+</span>
                                        <input type="file" id="profilePicInput" name="user_profpic" accept="image/*" style="display: none;">
                                    </div>

                                    <div class="image-preview position-relative mx-auto" id="imagePreview" style="display: none;">
                                        <img id="previewImg" src="#" alt="Preview" class="img-fluid rounded" style="border-radius: 12px; max-height: 150px;">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" id="removeBtn" style="width: 25px; height: 25px; padding: 0;">×</button>
                                    </div>

                                    <div class="form-text mt-2">Upload a JPG, PNG, or GIF (max 5MB).</div>
                                </div>
                                <div class="col-8">
                                    <br>
                                    <input type="text" name="user_fname" id="firstName" class="form-control mb-3" placeholder="First Name" required>
                                    <input type="text" name="user_mname" class="form-control mb-3" placeholder="Middle Name (Optional)">
                                    <input type="text" name="user_lname" id="lastName" class="form-control" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <label for="birthdate" class="form-label">Birthdate:</label>
                                    <input type="date" name="birthdate" id="birthdate" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label for="sex" class="form-label">Sex:</label>
                                    <select id="sex" name="user_sex" class="form-select" required>
                                        <option value="" selected disabled>Choose...</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="prefer-not">Prefer not to say</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input me-3" type="checkbox" id="privacy" required>
                                <label class="form-check-label" for="privacy">
                                    I agree to the <a href="#" class="text-success text-decoration-none">Privacy Policy</a>.
                                </label>
                            </div>
                            <button type="button" id="nextBtn" class="btn btn-green w-100" disabled><h5>Next</h5></button>
                        </div>

                        <!--SIGNUP 2ND TAB-->
                        <div id="step2" class="fade-step text-start">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="user_email" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="user_username" placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="user_password" placeholder="Password" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="user_conpass" placeholder="Confirm Password" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" id="backBtn" class="btn btn-outline-secondary"><h5>Back</h5></button>
                                <button type="submit" name="signup" class="btn btn-green"><h5>Create Account</h5></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer>
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            const nextBtn = document.getElementById('nextBtn');
            const backBtn = document.getElementById('backBtn');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const requiredFields = document.querySelectorAll('#step1 [required]');

            // Logic to enable Next button only when all required fields are filled
            function checkStep1Validity() {
                let allFilled = true;
                requiredFields.forEach(input => {
                    if (!input.value.trim() || (input.type === 'checkbox' && !input.checked)) {
                        allFilled = false;
                    }
                });
                nextBtn.disabled = !allFilled;
            }

            requiredFields.forEach(field => {
                field.addEventListener('input', checkStep1Validity);
                field.addEventListener('change', checkStep1Validity); // For checkbox/select
            });

            // Navigation functions
            nextBtn.addEventListener('click', () => {
                // Manually trigger form validation for step 1 fields before proceeding
                let isValid = true;
                requiredFields.forEach(field => {
                    if (!field.reportValidity()) {
                        isValid = false;
                    }
                });

                if (isValid) {
                    step1.classList.remove('active');
                    step2.classList.add('active');
                    // Ensure only step 2 is visible
                    step1.style.display = 'none';
                    step2.style.display = 'block';
                }
            });

            backBtn.addEventListener('click', () => {
                step2.classList.remove('active');
                step1.classList.add('active');
                // Ensure only step 1 is visible
                step2.style.display = 'none';
                step1.style.display = 'block';
            });
            
            // Image Upload Logic (simplified for demonstration)
            const uploadBox = document.getElementById('uploadBox');
            const fileInput = document.getElementById('profilePicInput');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const removeBtn = document.getElementById('removeBtn');

            uploadBox.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                        uploadBox.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBtn.addEventListener('click', () => {
                fileInput.value = '';
                imagePreview.style.display = 'none';
                uploadBox.style.display = 'flex';
            });
            
            // Initial check to disable button
            checkStep1Validity();

            // --- EXISTING USERNAMES AND EMAILS ---
            const existingUsernames = [
                <?php
                    $usernames = [];
                    $emails = [];

                    $user_usernames = [];
                    while ($row = $result->fetch_assoc()) {
                        $user_usernames[] = "'" . addslashes($row['user_username']) . "'";
                        $emails[] = "'" . addslashes($row['user_email']) . "'";
                    }

                    // collect admin usernames
                    $admin_usernames = [];
                    while ($row_admin = $result_admin->fetch_assoc()) {
                        $admin_usernames[] = "'" . addslashes($row_admin['admin_username']) . "'";
                    }

                    // merge both
                    $usernames = array_merge($user_usernames, $admin_usernames);
                    echo implode(",", $usernames);
                ?>
            ];
            const existingEmails = [
                <?php echo implode(",", $emails); ?>
            ];

            //pass and confirm pass checker
            const signupForm = document.getElementById('signupForm');
            signupForm.addEventListener('submit', (e) => {
                const pass = signupForm.querySelector('input[name="user_password"]').value;
                const confirm = signupForm.querySelector('input[name="user_conpass"]').value;
                const username = signupForm.querySelector('input[name="user_username"]').value.trim();
                const email = signupForm.querySelector('input[name="user_email"]').value.trim();

                // Password confirmation check
                if (pass !== confirm) {
                    e.preventDefault();
                    alert("Passwords do not match. Please try again.");
                    return;
                }

                // Username uniqueness check
                if (existingUsernames.includes(username)) {
                    e.preventDefault();
                    alert("That username is already taken. Please choose another one.");
                    return;
                }

                // Email uniqueness check
                if (existingEmails.includes(email)) {
                    e.preventDefault();
                    alert("This email is already registered. Please use another email.");
                    return;
                }
            });

            // --- LOGIN FORM CHECK ---
            const loginForm = document.querySelector('loginForm');

            loginForm.addEventListener('submit', (e) => {
                const usernameInput = loginForm.querySelector('input[name="username"]').value.trim();

                // Check if username exists
                if (!existingUsernames.includes(usernameInput)) {
                    e.preventDefault(); // Stop the form from submitting
                    alert("Username not found! Please check your input or sign up first.");
                } 
            });
        </script>
    </body>
</html>
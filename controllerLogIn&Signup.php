<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'green_horizon');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // LOGIN
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // First, check if username exists in ADMIN table
        $stmt_admin = $conn->prepare("SELECT admin_id, admin_username, admin_password FROM admin WHERE admin_username = ?");
        $stmt_admin->bind_param("s", $username);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        if ($admin = $result_admin->fetch_assoc()) {
            if (password_verify($password, $admin['admin_password'])) {
                header("Location: admin_index.php");
                exit();
            } else {
                $_SESSION['message'] = "❌ Incorrect password!";
                $_SESSION['msg_type'] = "danger";
                header("Location: login_signup.php");
                exit();
            }
        }

        // If not an admin, check USER table next
        $stmt_user = $conn->prepare("SELECT user_id, user_username, user_password FROM user WHERE user_username = ?");
        $stmt_user->bind_param("s", $username);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($user = $result_user->fetch_assoc()) {
            // ✅ Verify user password
            if (password_verify($password, $user['user_password'])) {
                $_SESSION['user_id'] = $user['user_id'];

                header("Location: index.php?user_id=" . urlencode($user['user_id']));
                exit();
            } else {
                $_SESSION['message'] = "❌ Incorrect password!";
                $_SESSION['msg_type'] = "danger";
                header("Location: login_signup.php");
                exit();
            }
        } else {
            $_SESSION['message'] = "⚠️ Username not found!";
            $_SESSION['msg_type'] = "warning";
            header("Location: login_signup.php");
            exit();
        }
    }

    //SIGN UP
    if (isset($_POST['signup'])) {
        $fname = trim($_POST['user_fname']);
        $lname = trim($_POST['user_lname']);
        $mname = trim($_POST['user_mname']);
        $birthdate = $_POST['birthdate'];
        $sex = $_POST['user_sex'];
        $email = trim($_POST['user_email']);
        $username = trim($_POST['user_username']);
        $password = password_hash($_POST['user_password'], PASSWORD_DEFAULT); // secure hash

        // --- HANDLE PROFILE PICTURE UPLOAD ---
        $profpic = null;
        $uploadDir = 'uploads/user_profile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['user_profpic']) && $_FILES['user_profpic']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['user_profpic']['tmp_name'];
            $fileName = basename($_FILES['user_profpic']['name']);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid('IMG_', true) . '.' . $fileExt;
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $filePath)) {
                $profpic = $filePath;
            }
        } else {
            if ($sex === 'female') {
                $profpic = $uploadDir . 'noProfileG.webp';
            } else {
                $profpic = $uploadDir . 'noProfileB.webp'; 
            }
        }

        // --- INSERT USER ---
        $stmt = $conn->prepare("INSERT INTO user (
            user_fname, user_lname, user_mname, birthdate, user_sex, 
            user_email, user_username, user_password, user_profpic
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssssss", 
            $fname, $lname, $mname, $birthdate, $sex,
            $email, $username, $password, $profpic
        );

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Signed up successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to sign up.";
            $_SESSION['msg_type'] = "danger";
        }

        $stmt->close();
        header("Location: login_signup.php");
        exit();
    }

    // DELETE ACCOUNT
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_account'])) {

        $user_id = intval($_POST['user_id']);
        $password = $_POST['confirmPassword'];

        // Fetch user data
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            // Verify password
            if (!password_verify($password, $user['password'])) {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
                exit;
            }
        }

        $conn->begin_transaction();

        try {
            // 1️⃣ Delete user posts
            $conn->query("DELETE FROM post_details WHERE user_id = $user_id");
            $conn->query("DELETE FROM challenge_completion_post WHERE user_id = $user_id");

            //Delete daily challenges & quiz
            $conn->query("DELETE FROM daily_challenge WHERE user_id = $user_id");
            $conn->query("DELETE FROM user_quiz WHERE user_id = $user_id");
            $conn->query("DELETE FROM user_streaks WHERE user_id = $user_id");

            // 3️⃣ Delete user from user_points table
            $conn->query("DELETE FROM user_points WHERE user_id = $user_id");

            // 4️⃣ Delete user account
            $conn->query("DELETE FROM users WHERE user_id = $user_id");

            $conn->commit();

            // Destroy session
            session_unset();
            session_destroy();

            header("Location: landing.html?deleted=1");
                exit;

            } catch (Exception $e) {
                $conn->rollback();

                // Redirect to landing page with error query
                header("Location: landing.html?deleted=0");
                exit;
            }
        }
?>
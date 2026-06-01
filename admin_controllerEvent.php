<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'green_horizon');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $now = date("Y-m-d H:i:s");

    // Update events whose start time has begun
    $updateOngoing = $conn->prepare("UPDATE `event` SET event_status = 'ongoing' WHERE event_start_datetime <= ? AND event_status = 'upcoming'");
    $updateOngoing->bind_param("s", $now);
    $updateOngoing->execute();

    // ADD EVENT
    if (isset($_POST['add_event'])) {
        $title = $_POST['event_title'];
        $caption = $_POST['event_caption'];
        $start = $_POST['start_datetime'];
        $end = $_POST['end_datetime'];
        $location = $_POST['event_location'];
        $fund = !empty($_POST['event_fund']) ? floatval($_POST['event_fund']) : 0;

        // Get current gross funds
        $result = $conn->query("SELECT gross_funds FROM admin WHERE admin_id = 1");
        $grossFunds = $result->fetch_assoc()['gross_funds'];

        // Check if enough funds
        if ($fund > $grossFunds) {
            $_SESSION['message'] = "⚠️ Not enough gross funds. Available: ₱" . number_format($grossFunds, 2);
            $_SESSION['msg_type'] = "warning";
            header("Location: admin_index.php");
            exit();
        }

        // Insert new event
        $stmt = $conn->prepare("INSERT INTO event (event_title, event_caption, event_start_datetime, event_end_datetime, event_location, event_fund)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssd", $title, $caption, $start, $end, $location, $fund);

        if ($stmt->execute()) {
            // Deduct from gross funds
            if ($fund > 0) {
                $update = $conn->prepare("UPDATE admin SET gross_funds = gross_funds - ? WHERE admin_id = 1");
                $update->bind_param("d", $fund);
                $update->execute();
                $update->close();
            }

            $_SESSION['message'] = "✅ Event created successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to create event.";
            $_SESSION['msg_type'] = "danger";
        }

        $stmt->close();
        header("Location: admin_index.php");
        exit();
    }

    // EDIT EVENT
    if (isset($_POST['edit_event'])) {
        $event_id = $_POST['event_id'];
        $title = $_POST['event_title'];
        $caption = $_POST['event_caption'];
        $start = $_POST['start_datetime'];
        $end = $_POST['end_datetime'];
        $location = $_POST['event_location'];
        $newFund = !empty($_POST['event_fund']) ? floatval($_POST['event_fund']) : 0;

        // Get old fund
        $oldResult = $conn->prepare("SELECT event_fund FROM event WHERE event_id = ?");
        $oldResult->bind_param("i", $event_id);
        $oldResult->execute();
        $oldRow = $oldResult->get_result()->fetch_assoc();
        $oldFund = $oldRow ? floatval($oldRow['event_fund']) : 0;
        $oldResult->close();

        // Calculate difference
        $difference = $newFund - $oldFund;

        // Get current gross funds
        $res = $conn->query("SELECT gross_funds FROM admin WHERE admin_id = 1");
        $grossFunds = $res->fetch_assoc()['gross_funds'];

        // Check if enough funds when increasing
        if ($difference > 0 && $difference > $grossFunds) {
            $_SESSION['message'] = "⚠️ Not enough gross funds to increase event budget. Available: ₱" . number_format($grossFunds, 2);
            $_SESSION['msg_type'] = "warning";
            header("Location: admin_index.php");
            exit();
        }

        // Update event details
        $stmt = $conn->prepare("UPDATE event 
                                SET event_title = ?, event_caption = ?, event_start_datetime = ?, event_end_datetime = ?, event_location = ?, event_fund = ?
                                WHERE event_id = ?");
        $stmt->bind_param("sssssdi", $title, $caption, $start, $end, $location, $newFund, $event_id);

        if ($stmt->execute()) {
            // Adjust gross funds (if changed)
            if ($difference != 0) {
                $updateFunds = $conn->prepare("UPDATE admin SET gross_funds = gross_funds - ? WHERE admin_id = 1");
                $updateFunds->bind_param("d", $difference);
                $updateFunds->execute();
                $updateFunds->close();
            }

            $_SESSION['message'] = "✅ Event updated successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to update event.";
            $_SESSION['msg_type'] = "danger";
        }

        $stmt->close();
        header("Location: admin_index.php");
        exit();
    }

    // DELETE EVENT
    if (isset($_GET['delete_event'])) {
        $event_id = intval($_GET['delete_event']);

        // Get the event fund before deleting
        $stmt = $conn->prepare("SELECT event_fund FROM event WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $refund = $event ? floatval($event['event_fund']) : 0;
        $stmt->close();

        // Delete the event
        $delete = $conn->prepare("DELETE FROM event WHERE event_id = ?");
        $delete->bind_param("i", $event_id);

        if ($delete->execute()) {
            // Refund the fund if any
            if ($refund > 0) {
                $updateFunds = $conn->prepare("UPDATE admin SET gross_funds = gross_funds + ? WHERE admin_id = 1");
                $updateFunds->bind_param("d", $refund);
                $updateFunds->execute();
                $updateFunds->close();
            }

            $_SESSION['message'] = "🗑️ Event deleted successfully! ₱" . number_format($refund, 2) . " refunded to Gross Funds.";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to delete event.";
            $_SESSION['msg_type'] = "danger";
        }

        $delete->close();
        header("Location: admin_index.php");
        exit();
    }

    // DONATIONS
    if (isset($_POST['makeDonation'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $amount = !empty($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

        //update gross funds
        $update = $conn->prepare("UPDATE `admin` SET gross_funds = gross_funds + ? WHERE admin_id = 1");
        $update->bind_param("d", $amount);
        $update->execute();
        $update->close();

        // Insert new donations
        $stmt = $conn->prepare("INSERT INTO donations (`name`, email, donation_amount)
                                VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $email, $amount);
        $stmt->execute();
        $stmt->close();
        
        header("Location: donate.php?user_id=$user_id&amount=$amount&name=" . urlencode($name) . "&donated=1");
        exit();
    }

    //EVENT POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eventPost'])) {
        $event_id = intval($_POST['event_id']);
        $postDetails_id = null; 
        $caption = $conn->real_escape_string($_POST['caption']);

        // 1️⃣ Handle image uploads
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Initialize image paths
        $images = ['img1' => '', 'img2' => '', 'img3' => '', 'img4' => '', 'img5' => ''];

        for ($i = 1; $i <= 5; $i++) {
            if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK) {
                $fileTmp = $_FILES["image$i"]['tmp_name'];
                $fileName = time() . "_{$i}_" . basename($_FILES["image$i"]['name']);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($fileTmp, $filePath)) {
                    $images["img$i"] = $filePath;
                } else {
                    $images["img$i"] = ''; // fallback
                }
            } else {
                $images["img$i"] = ''; // if not uploaded
            }
        }

        // 2️⃣ Insert into post_details
        $stmtDetails = $conn->prepare("INSERT INTO post_details (caption, img1, img2, img3, img4, img5) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtDetails->bind_param("ssssss", $caption, $images['img1'], $images['img2'], $images['img3'], $images['img4'], $images['img5']);
        
        if ($stmtDetails->execute()) {
            $postDetails_id = $stmtDetails->insert_id;
            $stmtDetails->close();

            // 3️⃣ Insert into eventPost
            $stmtPost = $conn->prepare("INSERT INTO event_post (event_id, postDetails_id) VALUES (?, ?)");
            $stmtPost->bind_param("ii", $event_id, $postDetails_id);

            if ($stmtPost->execute()) {
                $stmtPost->close();

                $update = $conn->prepare("UPDATE `event` SET event_status = 'completed' WHERE event_id = ?");
                $update->bind_param("i", $event_id);
                $update->execute();

                // Redirect after successful post
                header("Location: admin_index.php?success=1");
                exit;
            } else {
                echo "Error saving post: " . $conn->error;
            }
        } else {
            echo "Error saving post details: " . $conn->error;
        }
    }

    // SIGNUP EVENT
    if (isset($_POST['eventSignup'])) {
        $fullname = $_POST['fullName'];
        $email = $_POST['email'];
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

        $stmt = $conn->prepare("INSERT INTO event_participants (event_id, user_id, participant_fullname, participant_email)
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $event_id, $user_id, $fullname, $email);
        $stmt->execute();
        $stmt->close();
        
        header("Location: comfeed.php?user_id=$user_id&signup=1");
        exit();
    }

?>
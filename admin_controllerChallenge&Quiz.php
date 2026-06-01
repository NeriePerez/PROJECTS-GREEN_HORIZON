<?php
    session_start();

    $conn = new mysqli('localhost', 'root', '', 'green_horizon');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // ADD CHALLENGE
    if (isset($_POST['add_challenge'])) {
        $title = $_POST['challenge_title'];
        $points = $_POST['challenge_points'];

        $stmt = $conn->prepare("INSERT INTO challenges (challenge_title, challenge_points) VALUES (?, ?)");
        $stmt->bind_param("si", $title, $points);

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Challenge added successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to add challenge.";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: admin_challenge&quiz.php");
        exit();
    }

    // UPDATE CHALLENGE
    if (isset($_POST['update_challenge'])) {
        $id = $_POST['challenge_id'];
        $title = $_POST['challenge_title'];
        $points = $_POST['challenge_points'];

        $stmt = $conn->prepare("UPDATE challenges SET challenge_title=?, challenge_points=? WHERE challenge_id=?");
        $stmt->bind_param("sii", $title, $points, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "✅ Challenge updated successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to update challenge.";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: admin_challenge&quiz.php");
        exit();
    }

    // DELETE CHALLENGE
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $stmt = $conn->prepare("DELETE FROM challenges WHERE challenge_id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "🗑️ Challenge deleted successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to delete challenge.";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: admin_challenge&quiz.php");
        exit();
    }
    
    //ADD QUIZ WITH QUESTIONS
    if (isset($_POST['add_quiz'])) {
        $level = $_POST['quiz_level'];
        $title = $_POST['quiz_title'];

        //check if level exist
        $checkQ = $conn->prepare("SELECT quiz_id FROM quiz WHERE quiz_level = ?");
        $checkQ->bind_param("i", $level);
        $checkQ->execute();
        $resultQ = $checkQ->get_result();

        if ($resultQ->num_rows === 0) {
            // Step 1: Insert quiz first
            $stmt = $conn->prepare("INSERT INTO quiz (quiz_level, quiz_title) VALUES (?, ?)");
            $stmt->bind_param("is", $level, $title);

            if ($stmt->execute()) {
                $quiz_id = $stmt->insert_id; // get new quiz id
                $stmt->close();

                // Step 2: Loop through the questions submitted from the form
                if (!empty($_POST['questions'])) {
                    $stmtQ = $conn->prepare("
                        INSERT INTO quiz_question 
                        (quiz_id, question_text, option1, option2, option3, option4, correct_answer, question_points)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    foreach ($_POST['questions'] as $q) {
                        $question_text = $q['question_text'];
                        $c1 = $q['option1'];
                        $c2 = $q['option2'];
                        $c3 = $q['option3'];
                        $c4 = $q['option4'];
                        $answer = intval($q['correct_answer']);
                        $correct;
                        if($answer == 1) {
                            $correct = "A";
                        } elseif($answer == 2) {
                            $correct = "B";
                        } elseif($answer == 3) {
                            $correct = "C";
                        } elseif($answer == 4) {
                            $correct = "D";
                        }
                        $points = $q['question_points'];

                        $stmtQ->bind_param("issssssi", $quiz_id, $question_text, $c1, $c2, $c3, $c4, $correct, $points);
                        $stmtQ->execute();
                    }

                    $stmtQ->close();
                }

                $_SESSION['message'] = "✅ Quiz and questions added successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "❌ Failed to add quiz.";
                $_SESSION['msg_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "❌ Failed to add quiz. ";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: admin_challenge&quiz.php");
        exit();
    }

    // EDIT QUIZ
    if (isset($_POST['edit_quiz'])) {
        $quiz_id = $_POST['quiz_id'];
        $level = $_POST['quiz_level'];
        $title = $_POST['quiz_title'];

        // Step 1: Insert quiz first
        $stmt = $conn->prepare("UPDATE quiz SET quiz_level = ?, quiz_title = ? WHERE quiz_id = ?");
        $stmt->bind_param("isi", $level, $title, $quiz_id);

        if ($stmt->execute()) {
            $stmt->close();

            // Step 2: Delete all existing questions for this quiz (clean slate)
            $conn->query("DELETE FROM quiz_question WHERE quiz_id = $quiz_id");

            // Step 3: Reinsert updated questions
            if (!empty($_POST['questions'])) {
                $stmtQ = $conn->prepare("
                    INSERT INTO quiz_question 
                    (quiz_id, question_text, option1, option2, option3, option4, correct_answer, question_points)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");

                foreach ($_POST['questions'] as $q) {
                    $question_text = $q['question_text'];
                    $c1 = $q['option1'];
                    $c2 = $q['option2'];
                    $c3 = $q['option3'];
                    $c4 = $q['option4'];

                    $answer = intval($q['correct_answer']);
                    if ($answer == 1) {
                        $correct = "A";
                    } elseif ($answer == 2) {
                        $correct = "B";
                    } elseif ($answer == 3) {
                        $correct = "C";
                    } else {
                        $correct = "D";
                    }

                    $points = $q['question_points'];
                    $stmtQ->bind_param("issssssi", $quiz_id, $question_text, $c1, $c2, $c3, $c4, $correct, $points);
                    $stmtQ->execute();
                }

                $stmtQ->close();
            }

            $_SESSION['message'] = "✅ Quiz and questions updated successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to update quiz.";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: admin_challenge&quiz.php");
        exit();
    }


    // DELETE QUIZ WITH QUESTIONS
    if (isset($_GET['delete_quiz'])) {
        $id = intval($_GET['delete_quiz']);

        $stmt_q = $conn->prepare("DELETE FROM quiz_question WHERE quiz_id = ?");
        $stmt_q->bind_param("i", $id);
        $stmt_q->execute();

        $stmt = $conn->prepare("DELETE FROM quiz WHERE quiz_id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "🗑️ Quiz and its questions deleted successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "❌ Failed to delete quiz.";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: admin_challenge&quiz.php");
        exit();
    }


?>

<?php
    include 'controllerQuiz&Challenge.php';

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

    // Fetch all questions for this quiz
    $result = $conn->query("SELECT * FROM quiz_question WHERE quiz_id = $quiz_id");
    $questions = $result->fetch_all(MYSQLI_ASSOC);

    // Randomize question order
    shuffle($questions);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Quiz - Easy</title>
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

            /* Navbar */
            .navbar {
                background-color: #2E7D32;
            }

            .navbar-brand {
                color: white !important;
                font-weight: bold;
            }

            /* Quiz Card */
            .quiz-card {
                margin: 60px auto;
                border: none;
                border-radius: 15px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                background: white;
                margin-top: 210px;
                margin-bottom: 300px;
            }

            h1 {
                color: #2E7D32;
                font-weight: bold;
            }

            h3 {
                color: #2E7D32;
                font-weight: 600;
            }

            p {
                color: #1B5E20;
                font-size: 1rem;
                margin-bottom: 20px;
            }

            /* Responsive paragraph text */
            @media (max-width: 768px) {
                p {
                    font-size: 0.9rem;
                }
                h3 {
                    font-size: 1.2rem;
                }
            }

            /* Option Buttons */
            .option-btn {
                border-radius: 10px;
                transition: all 0.2s ease;
                border-color: #2E7D32;
                color: #2E7D32;
            }

            .option-btn.selected {
                background-color: #2E7D32;
                color: white;
                border-color: #2E7D32;
            }

            .option-btn:hover {
                background-color: #A5D6A7;
                color: #1B5E20;
            }

            /* Nav Buttons */
            #nextBtn, #prevBtn {
                font-weight: 600;
                border: none;
                transition: 0.3s ease;
            }

            #nextBtn {
                background-color: #2E7D32;
                color: white;
            }

            #nextBtn:hover {
                background-color: #256628;
            }

            #prevBtn {
                border: 2px solid #2E7D32;
                color: #2E7D32;
                background-color: transparent;
            }

            #prevBtn:hover {
                background-color: #E8F5E9;
            }

            /* Footer */
            footer {
                background-color: #2E7D32;
                color: white !important;
                text-align: center;
                padding: 1rem 0;
                margin-top: 300px;
            }
            footer,
            footer p {
                background-color: #2E7D32 !important;
                color: #ffffff !important;
                text-align: center !important;
            }


            /* Question step visibility */
            .question-step {
                display: none;
            }

            .question-step.active {
                display: block;
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

        <div class="container card quiz-card p-4">
            <div class="card-body text-center">
                <h1 class="fw-bold mb-3">Easy Quiz</h1>
                <p>Answer the questions below. Use the buttons to navigate.</p>

                <form id="quizForm" method="POST" action="controllerQuiz.php">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                    <input type="hidden" name="quiz_id" value="<?= htmlspecialchars($quiz_id) ?>">
                    <?php
                        $qnum = 1;
                        foreach ($questions as $q) {
                            // Randomize options
                            $options = [
                                'A' => $q['option1'],
                                'B' => $q['option2'],
                                'C' => $q['option3'],
                                'D' => $q['option4']
                            ];
                            $keys = array_keys($options);
                            shuffle($keys);
                    ?>
                    <div class="question-step <?php echo $qnum === 1 ? 'active' : ''; ?>">
                        <h3 class="mb-5"><?php echo $qnum . ". " . htmlspecialchars($q['question_text']); ?></h3>
                        <input type="hidden" name="question_id[<?= $qnum - 1 ?>]" value="<?= $q['question_id'] ?>">

                        <div class="row answers align-items-center">
                            <?php
                            $half = ceil(count($keys) / 2);
                            $chunks = array_chunk($keys, $half);
                            foreach ($chunks as $col) {
                                echo "<div class='col'>";
                                foreach ($col as $k) {
                                    $val = htmlspecialchars($options[$k]);
                                    echo "<button type='button' class='btn option-btn w-100 mb-3' data-value='{$k}'>
                                            <h5>{$val}</h5>
                                        </button>";
                                }
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                    <?php $qnum++; } ?>
                
                    <div class="d-flex justify-content-between mt-5">
                        <button type="button" id="prevBtn" class="btn py-2 px-4 fs-5" disabled>⬅ Previous</button>
                        <button type="button" id="nextBtn" class="btn py-2 px-4 fs-5">Next ➡</button>
                    </div>
            </form>
            </div>
        </div>

        <footer style="background:#2E7D32; color:white;">
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            const steps = document.querySelectorAll('.question-step');
            const nextBtn = document.getElementById('nextBtn');
            const prevBtn = document.getElementById('prevBtn');
            const optionBtns = document.querySelectorAll('.option-btn');
            let currentStep = 0;

            optionBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.closest('.answers').querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                });
            });

            function showStep(index) {
                steps.forEach((step, i) => step.classList.toggle('active', i === index));
                prevBtn.disabled = index === 0;
                nextBtn.textContent = index === steps.length - 1 ? 'Submit' : 'Next ➡';
            }

            nextBtn.addEventListener('click', () => {
                if (currentStep === steps.length - 1) {
                    let allAnswered = true;

                    // Remove previous hidden inputs
                    document.querySelectorAll('#quizForm input[name^="answer"]').forEach(input => input.remove());

                    steps.forEach((step, index) => {
                        const selected = step.querySelector('.option-btn.selected');
                        if (!selected) {
                            allAnswered = false;
                        } else {
                            // Create hidden input to submit the answer
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'answer[' + index + ']'; // e.g. answer[0], answer[1]
                            input.value = selected.getAttribute('data-value');
                            document.getElementById('quizForm').appendChild(input);
                        }
                    });

                    if (!allAnswered) {
                        alert("Please answer all questions before submitting.");
                        return; // prevent submission
                    }

                    // Submit the form via POST
                    document.getElementById('quizForm').submit();
                } else {
                    currentStep++;
                    showStep(currentStep);
                }
            });


            prevBtn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            showStep(currentStep);
        </script>
    </body>
</html>
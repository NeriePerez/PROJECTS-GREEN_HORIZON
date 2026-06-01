<?php
    include 'admin_controllerChallenge&Quiz.php';

    $quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
    $quiz = $conn->query("SELECT * FROM quiz WHERE quiz_id = $quiz_id")->fetch_assoc();
    $result = $conn->query("SELECT quiz_level FROM `quiz`");

    $questions_result = $conn->query("SELECT * FROM quiz_question WHERE quiz_id = $quiz_id ORDER BY question_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Green Horizon | Edit Quiz</title>
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            body {
                background-color: #F0F4F1;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                padding-top: 80px;
                padding-bottom: 80px;
            }

            .navbar {
                background-color: #2E7D32 !important;
                box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            }

            .navbar-brand {
                color: white !important;
                font-weight: 700;
                font-size: 1.25rem;
                letter-spacing: 0.5px;
            }

            .card {
                max-width: 1000px;
                margin: 2.5rem auto;
                border-radius: 14px;
                border: none;
                background: #ffffff;
                box-shadow: 0 6px 16px rgba(0,0,0,0.10);
                padding: 2rem 2.5rem;
            }

            .btn-success {
                background-color: #2E7D32;
                border: none;
                transition: 0.3s;
            }
            .btn-success:hover{
                background-color: #256629;
                transform: translateY(-2px);
            }

            .btn-outline-success {
                border-color: #2E7D32;
                color: #2E7D32;
                font-weight: 600;
                transition: 0.3s;
            }
            .btn-outline-success:hover {
                background-color: #2E7D32;
                color: white;
                transform: translateY(-2px);
            }

            #questionsList .card {
                border-left: 5px solid #2E7D32 !important;
                border-radius: 10px;
                padding: 1rem 1.2rem;
                background: #ffffff;
            }

            .question-card .dropdown {
                position: absolute;
                top: 12px;
                right: 12px;
            }

            .dropdown-menu {
                border-radius: 10px;
                padding: 0.5rem 0;
                border: none;
                box-shadow: 0 6px 14px rgba(0,0,0,0.15);
            }

            .dropdown-menu .dropdown-item {
                border-radius: 6px;
                padding: 8px 18px;
                font-weight: 500;
            }
            .dropdown-menu .dropdown-item:hover {
                background-color: #ECF7ED;
                color: #2E7D32;
            }

            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                font-size: 0.95rem;
                letter-spacing: 0.4px;
                box-shadow: 0 -3px 6px rgba(0,0,0,0.15);
            }

            /* Smooth highlight for correct answer */
            .list-group-item-success {
                background-color: #DFF5E1 !important;
                border: 1px solid #C0E7C5 !important;
                font-weight: 600;
            }
        </style>
    </head>
    <body>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark px-4 fixed-top">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/GHLOGO.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle" />
                Green Horizon | ADMIN
            </a>
        </nav>

        <!-- EDIT QUIZ CARD -->
        <div class="card p-4">
            <h4 class="fw-bold text-success mb-3">✏️ Edit Quiz</h4>

            <form id="quizForm" method="POST" action="admin_controllerChallenge&Quiz.php">
                <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

                <!-- LEVEL -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Level</label>
                    <input type="text" class="form-control" id="quizLevel" name="quiz_level"
                        value="<?= htmlspecialchars($quiz['quiz_level'] ?? '') ?>" required>
                </div>

                <!-- TITLE -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" id="quizTitle" name="quiz_title"
                        value="<?= htmlspecialchars($quiz['quiz_title'] ?? '') ?>" required>
                </div>

                <!-- ADD QUESTION BUTTON -->
                <div class="mb-3 text-center">
                    <button type="button" class="btn btn-outline-success fs-5 py-3 px-5"
                        data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                        ➕ Add Question
                    </button>
                </div>

                <!-- EXISTING QUESTIONS LIST -->
                <div id="questionsList">
                    <?php 
                        $i = 1;
                        while ($q = $questions_result->fetch_assoc()) {
                            // Determine correct answer index (1–4)
                            $ans = 0;
                            if ($q['correct_answer'] == $q['option1']) $ans = 1;
                            elseif ($q['correct_answer'] == $q['option2']) $ans = 2;
                            elseif ($q['correct_answer'] == $q['option3']) $ans = 3;
                            elseif ($q['correct_answer'] == $q['option4']) $ans = 4;

                            $choices = [$q['option1'], $q['option2'], $q['option3'], $q['option4']];
                    ?>
                    <div class="card mb-3 p-3 border border-success-subtle question-card"
                        data-id="<?= $q['question_id'] ?>"
                        data-text="<?= htmlspecialchars($q['question_text']) ?>"
                        data-choices='<?= json_encode($choices, JSON_HEX_APOS|JSON_HEX_QUOT) ?>'
                        data-correct="<?= $ans ?>"
                        data-points="<?= htmlspecialchars($q['question_points']) ?>">

                        <!-- Dropdown Menu -->
                        <div class="dropdown text-end">
                            <button class="btn btn-light btn-sm border-0" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical text-success fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="openEditModal('<?= $q['question_id'] ?>'); return false;">Edit</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteQuestionById('<?= $q['question_id'] ?>'); return false;">Delete</a>
                                </li>
                            </ul>
                        </div>

                        <h6 class="fw-bold text-success mb-2">Question <?= $i++ ?>:</h6>
                        <p class="mb-1"><?= htmlspecialchars($q['question_text']) ?></p>
                        <ul class="list-group mb-2">
                            <?php foreach ($choices as $index => $c): ?>
                                <li class="list-group-item <?= ($ans == $index + 1) ? 'list-group-item-success' : '' ?>">
                                    <?= chr(65 + $index) ?>. <?= htmlspecialchars($c) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="text-end fw-semibold text-secondary mb-0">Points: <?= htmlspecialchars($q['question_points']) ?></p>
                    </div>
                    <?php } ?>
                </div>

                <hr>

                <!-- ACTION BUTTONS -->
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4 fs-4"
                        onclick="window.location.href='admin_quiz.php'">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4 fs-4" name="action" value="edit_quiz">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- ADD QUESTION MODAL -->
        <div class="modal fade" id="addQuestionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded-3">
                    <div class="modal-header">
                        <h5 class="modal-title text-success fw-bold">Add Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question</label>
                            <input type="text" id="questionText" class="form-control" placeholder="Enter question" required>
                        </div>
                        <label class="form-label fw-semibold">Choices</label>
                        <div class="mb-2"><input type="text" id="choice1" class="form-control" placeholder="Choice 1"></div>
                        <div class="mb-2"><input type="text" id="choice2" class="form-control" placeholder="Choice 2"></div>
                        <div class="mb-2"><input type="text" id="choice3" class="form-control" placeholder="Choice 3"></div>
                        <div class="mb-2"><input type="text" id="choice4" class="form-control" placeholder="Choice 4"></div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correct Answer</label>
                            <select id="correctAnswer" class="form-select">
                                <option value="1">Choice 1</option>
                                <option value="2">Choice 2</option>
                                <option value="3">Choice 3</option>
                                <option value="4">Choice 4</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Points</label>
                            <input type="number" id="questionPoints" class="form-control" placeholder="Enter points (e.g. 5)" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="saveQuestion">Save Question</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDIT QUESTION MODAL -->
        <div class="modal fade" id="editQuestionModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content rounded-3">
                    <div class="modal-header">
                        <h5 class="modal-title text-success fw-bold">Edit Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Question</label>
                            <input type="text" id="editQuestionText" class="form-control" required>
                        </div>

                        <label class="form-label fw-semibold">Choices</label>
                        <div class="mb-2"><input type="text" id="editChoice1" class="form-control" placeholder="Choice 1"></div>
                        <div class="mb-2"><input type="text" id="editChoice2" class="form-control" placeholder="Choice 2"></div>
                        <div class="mb-2"><input type="text" id="editChoice3" class="form-control" placeholder="Choice 3"></div>
                        <div class="mb-2"><input type="text" id="editChoice4" class="form-control" placeholder="Choice 4"></div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correct Answer</label>
                            <select id="editCorrectAnswer" class="form-select">
                                <option value="1">Choice 1</option>
                                <option value="2">Choice 2</option>
                                <option value="3">Choice 3</option>
                                <option value="4">Choice 4</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Points</label>
                            <input type="number" id="editQuestionPoints" class="form-control" min="1" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="updateQuestion">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <footer class="fixed-bottom">
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const questionsList = document.getElementById('questionsList');
                const saveQuestionBtn = document.getElementById('saveQuestion');

                let nextQuestionId = 1;
                let currentEditId = null;
                let editModalInstance = null;

                // 🧠 Escape HTML to prevent injection
                function escapeHtml(str) {
                    return str.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
                }

                // 🧩 Build question card HTML
                function buildQuestionCardHtml(id, index, text, choices, correct) {
                    return `
                    <div class="question-card position-relative">
                        <div class="dropdown">
                        <button class="btn btn-light btn-sm border-0" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical text-success fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="openEditModal('${id}'); return false;">Edit</a></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteQuestionById('${id}'); return false;">Delete</a></li>
                        </ul>
                        </div>
                        <h6 class="fw-bold text-success mb-2">Question ${index}:</h6>
                        <p class="mb-1">${escapeHtml(text)}</p>
                        <ul class="list-group mb-2">
                        ${choices.map((c, i) =>
                            `<li class="list-group-item ${parseInt(correct) === i + 1 ? 'list-group-item-success' : ''}">
                            ${String.fromCharCode(65 + i)}. ${escapeHtml(c)}
                            </li>`).join('')}
                        </ul>
                    </div>
                    `;
                }

                // ➕ Add New Question
                saveQuestionBtn.addEventListener('click', () => {
                    const qText = document.getElementById('questionText').value.trim();
                    const choices = [
                        document.getElementById('choice1').value.trim(),
                        document.getElementById('choice2').value.trim(),
                        document.getElementById('choice3').value.trim(),
                        document.getElementById('choice4').value.trim()
                    ];
                    const correct = document.getElementById('correctAnswer').value;
                    const points = document.getElementById('questionPoints').value.trim();

                    if (!qText || choices.some(c => c === '') || !points) {
                        alert("⚠️ Please fill in all fields.");
                        return;
                    }

                    const id = `q${nextQuestionId++}`;
                    const wrapper = document.createElement('div');
                    wrapper.className = 'card mb-3 p-3 border-success question-card';
                    wrapper.dataset.id = id;
                    wrapper.dataset.correct = correct;
                    wrapper.dataset.choices = JSON.stringify(choices);
                    wrapper.dataset.text = qText;
                    wrapper.dataset.points = points;

                    wrapper.innerHTML = `
                        ${buildQuestionCardHtml(id, questionsList.children.length + 1, qText, choices, correct)}
                        <p class="text-end fw-semibold text-secondary mb-0">Points: ${escapeHtml(points)}</p>
                    `;

                    questionsList.appendChild(wrapper);

                    // Reset modal fields
                    document.querySelectorAll('#addQuestionModal input').forEach(i => i.value = '');
                    document.getElementById('correctAnswer').value = '1';

                    // Hide modal
                    bootstrap.Modal.getInstance(document.getElementById('addQuestionModal')).hide();
                });


                // ✏️ Open Edit Modal
                window.openEditModal = function(id) {
                    const item = document.querySelector(`[data-id="${id}"]`);
                    if (!item) return;

                    currentEditId = id;
                    const text = item.dataset.text;
                    const choices = JSON.parse(item.dataset.choices);
                    const correct = item.dataset.correct;
                    const points = item.dataset.points || "1";

                    // Fill modal fields
                    document.getElementById('editQuestionText').value = text;
                    document.getElementById('editChoice1').value = choices[0];
                    document.getElementById('editChoice2').value = choices[1];
                    document.getElementById('editChoice3').value = choices[2];
                    document.getElementById('editChoice4').value = choices[3];
                    document.getElementById('editCorrectAnswer').value = correct;
                    document.getElementById('editQuestionPoints').value = points;

                    const el = document.getElementById('editQuestionModal');
                    editModalInstance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                    editModalInstance.show();
                };

                // 💾 Update Question
                document.getElementById('updateQuestion').addEventListener('click', () => {
                    if (!currentEditId) return;

                    const qText = document.getElementById('editQuestionText').value.trim();
                    const choices = [
                        document.getElementById('editChoice1').value.trim(),
                        document.getElementById('editChoice2').value.trim(),
                        document.getElementById('editChoice3').value.trim(),
                        document.getElementById('editChoice4').value.trim()
                    ];
                    const correct = document.getElementById('editCorrectAnswer').value;
                    const points = document.getElementById('editQuestionPoints').value.trim();

                    if (!qText || choices.some(c => c === '') || !points) {
                        alert("⚠️ Please fill in all fields, including points.");
                        return;
                    }

                    const item = document.querySelector(`[data-id="${currentEditId}"]`);
                    if (!item) return;

                    // Update data attributes
                    item.dataset.text = qText;
                    item.dataset.choices = JSON.stringify(choices);
                    item.dataset.correct = correct;
                    item.dataset.points = points;

                    // Rebuild question card content
                    const index = Array.from(questionsList.children).indexOf(item) + 1;
                    item.innerHTML = `
                        ${buildQuestionCardHtml(currentEditId, index, qText, choices, correct)}
                        <p class="text-end fw-semibold text-secondary mb-0">Points: ${escapeHtml(points)}</p>
                    `;

                    // Hide modal
                    if (editModalInstance) editModalInstance.hide();
                    currentEditId = null;
                });


                // ❌ Delete Question
                window.deleteQuestionById = function(id) {
                    if (!confirm("Are you sure you want to delete this question?")) return;

                    const item = document.querySelector(`[data-id="${id}"]`);
                    if (item) item.remove();

                    // Renumber remaining questions and rebuild their HTML (including points)
                    Array.from(questionsList.children).forEach((child, idx) => {
                        const qid = child.dataset.id;
                        const text = child.dataset.text;
                        const choices = JSON.parse(child.dataset.choices);
                        const correct = child.dataset.correct;
                        const points = child.dataset.points || "1";

                        child.innerHTML = `
                            ${buildQuestionCardHtml(qid, idx + 1, text, choices, correct)}
                            <p class="text-end fw-semibold text-secondary mb-0">Points: ${escapeHtml(points)}</p>
                        `;
                    });
                };

                //level exists checker
                const existingLevel = [
                    <?php
                        $level = [];

                        while ($row = $result->fetch_assoc()) {
                            $level[] = "'" . addslashes($row['quiz_level']) . "'";
                        }

                        echo implode(",", $level);
                    ?>
                ];

                // ✅ Submit Quiz
                document.getElementById('quizForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (questionsList.children.length === 0) {
                        alert("⚠️ Please add at least one question.");
                        return;
                    }

                    // Remove old hidden inputs if resubmitting
                    document.querySelectorAll('.hidden-question').forEach(el => el.remove());

                    // Loop through each question in the list
                    Array.from(questionsList.children).forEach((child, index) => {
                        const choices = JSON.parse(child.dataset.choices);

                        // Create hidden inputs for each question property
                        const fields = {
                            [`questions[${index}][question_text]`]: child.dataset.text,
                            [`questions[${index}][option1]`]: choices[0],
                            [`questions[${index}][option2]`]: choices[1],
                            [`questions[${index}][option3]`]: choices[2],
                            [`questions[${index}][option4]`]: choices[3],
                            [`questions[${index}][correct_answer]`]: child.dataset.correct,
                            [`questions[${index}][question_points]`]: child.dataset.points || 1
                        };

                        for (const name in fields) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = name;
                            input.value = fields[name];
                            input.classList.add('hidden-question');
                            this.appendChild(input);
                        }
                    });

                    // Add the action field
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'edit_quiz';
                    actionInput.value = '1';
                    this.appendChild(actionInput);

                    this.submit();
                });
            });
        </script>
    </body>
</html>
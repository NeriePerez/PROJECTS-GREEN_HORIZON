<?php
    include 'admin_controllerChallenge&Quiz.php';

    $result = $conn->query("SELECT quiz_level FROM `quiz`");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />
        <title>Green Horizon | Add Quiz</title>

        <!-- BOOTSTRAP -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- ICONS -->
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
                <img src="img/554299150_1825072304882509_7390063311808815074_n.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle">
                <span class="fw-bold fs-4">Green Horizon | ADMIN</span>
            </a>
        </nav>

        <!-- ADD QUIZ CARD -->
        <div class="card p-4">
            <h4 class="fw-bold text-success mb-3">📝 Add New Quiz</h4>

            <form action="admin_controllerChallenge&Quiz.php" id="add_quiz" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Level</label>
                    <input type="number" class="form-control" id="quiz_level" name="quiz_level" placeholder="e.g. 1, 2" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" id="quiz_title" name="quiz_title" placeholder="Enter quiz title" required>
                </div>

                <div class="mb-3 text-center">
                    <button type="button" class="btn btn-outline-success fs-5 py-3 px-5" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                    ➕ Add Item
                    </button>
                </div>

                <!-- Display Added Questions -->
                <div id="questionsList"></div>

                <hr>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-4 fs-4" onclick="window.location.href='admin_challenge&quiz.php'">Cancel</button>
                    <button type="submit" name="add_quiz" class="btn btn-success px-4 fs-4">Add Quiz</button>
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
                            <input type="text" id="question_text" name="question_text" class="form-control" placeholder="Enter question" required>
                        </div>

                        <label class="form-label fw-semibold">Choices</label>
                        <div class="mb-2"><input type="text" id="option1" name="option1" class="form-control" placeholder="Choice 1" required></div>
                        <div class="mb-2"><input type="text" id="option2" name="option2" class="form-control" placeholder="Choice 2" required></div>
                        <div class="mb-2"><input type="text" id="option3" name="option3" class="form-control" placeholder="Choice 3" required></div>
                        <div class="mb-2"><input type="text" id="option4" name="option4" class="form-control" placeholder="Choice 4" required></div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correct Answer</label>
                            <select id="correct_answer" name="correct_answer" class="form-select">
                                <option value="1">Choice 1</option>
                                <option value="2">Choice 2</option>
                                <option value="3">Choice 3</option>
                                <option value="4">Choice 4</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Points</label>
                            <input type="number" id="question_points" name="question_points" class="form-control" placeholder="Enter points (e.g. 5)" min="1" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="saveQuestion">Add</button>
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
                        <div class="mb-2"><input type="text" id="editChoice1" class="form-control" required></div>
                        <div class="mb-2"><input type="text" id="editChoice2" class="form-control" required></div>
                        <div class="mb-2"><input type="text" id="editChoice3" class="form-control" required></div>
                        <div class="mb-2"><input type="text" id="editChoice4" class="form-control" required></div>

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

        <!-- FOOTER -->
        <footer class="fixed-bottom">
            <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            const questionsList = document.getElementById('questionsList');
            const successMessage = document.getElementById('successMessage');
            const saveQuestionBtn = document.getElementById('saveQuestion');

            let nextQuestionId = 1;          // stable id generator
            let currentEditId = null;        // id of question being edited
            let editModalInstance = null;

            // helper: build question card HTML from data
            function buildQuestionCardHtml(id, index, text, choices, correct, points) {
                return `
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm border-0" data-bs-toggle="dropdown" aria-expanded="false">
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
                        ${choices.map((c, i) => `<li class="list-group-item ${parseInt(correct) === i + 1 ? 'list-group-item-success' : ''}">
                            ${String.fromCharCode(65 + i)}. ${escapeHtml(c)}
                        </li>`).join('')}
                    </ul>
                    <p class="text-end fw-semibold text-secondary mb-0">Points: ${points}</p>
                `;
            }


            // escape HTML to avoid injection in examples
            function escapeHtml(str) {
                return str.replace(/[&<>"']/g, function(m) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]);
                });
            }

            function addQuestionToList(qText, choices, correct, points) {
                const index = questionsList.children.length;
                const id = `q${index + 1}`;

                const wrapper = document.createElement('div');
                wrapper.className = 'card mb-3 p-3 border-success position-relative question-card';
                wrapper.dataset.id = id;
                wrapper.dataset.correct = correct;
                wrapper.dataset.choices = JSON.stringify(choices);
                wrapper.dataset.points = points;
                wrapper.dataset.text = qText;

                wrapper.innerHTML = `
                    ${buildQuestionCardHtml(id, index + 1, qText, choices, correct, points)}

                    <!-- Hidden inputs (for form submission) -->
                    <input type="hidden" name="questions[${index}][question_text]" value="${escapeHtml(qText)}">
                    <input type="hidden" name="questions[${index}][option1]" value="${escapeHtml(choices[0])}">
                    <input type="hidden" name="questions[${index}][option2]" value="${escapeHtml(choices[1])}">
                    <input type="hidden" name="questions[${index}][option3]" value="${escapeHtml(choices[2])}">
                    <input type="hidden" name="questions[${index}][option4]" value="${escapeHtml(choices[3])}">
                    <input type="hidden" name="questions[${index}][correct_answer]" value="${escapeHtml(correct)}">
                    <input type="hidden" name="questions[${index}][question_points]" value="${escapeHtml(points)}">
                `;

                questionsList.appendChild(wrapper);
            }


            // ADD QUESTION
            saveQuestionBtn.addEventListener('click', () => {
                const qText = document.getElementById('question_text').value.trim();
                const choices = [
                    document.getElementById('option1').value.trim(),
                    document.getElementById('option2').value.trim(),
                    document.getElementById('option3').value.trim(),
                    document.getElementById('option4').value.trim()
                ];
                const correct = document.getElementById('correct_answer').value;
                const points = document.getElementById('question_points').value.trim();

                if (!qText || choices.some(c => c === '') || !points) {
                    alert("⚠️ Please fill in all fields.");
                    return;
                }

                const id = `q${nextQuestionId++}`;
                const index = questionsList.children.length + 1;

                addQuestionToList(qText, choices, correct, points);

                // Reset modal fields
                document.querySelectorAll('#addQuestionModal input').forEach(input => input.value = '');
                document.getElementById('correct_answer').value = '1';
                document.getElementById('question_points').value = '';

                // Close modal
                const addModalEl = document.getElementById('addQuestionModal');
                const addModal = bootstrap.Modal.getInstance(addModalEl) || new bootstrap.Modal(addModalEl);
                addModal.hide();
                rebuildHiddenInputs();
            });

            // OPEN EDIT MODAL: populate fields from stored data and show modal
            window.openEditModal = function(id) {
                const item = document.querySelector(`[data-id="${id}"]`);
                if (!item) return;
                currentEditId = id;

                const text = item.dataset.text || '';
                const choices = JSON.parse(item.dataset.choices || '["","","",""]');
                const correct = item.dataset.correct || '1';
                const points = item.dataset.points || '';

                document.getElementById('editQuestionText').value = text;
                document.getElementById('editChoice1').value = choices[0] || '';
                document.getElementById('editChoice2').value = choices[1] || '';
                document.getElementById('editChoice3').value = choices[2] || '';
                document.getElementById('editChoice4').value = choices[3] || '';
                document.getElementById('editCorrectAnswer').value = correct;
                document.getElementById('editQuestionPoints').value = points;

                // ensure modal instance stored so we can hide it later
                const el = document.getElementById('editQuestionModal');
                editModalInstance = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                editModalInstance.show();
            };

            // UPDATE QUESTION AFTER EDIT
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
                    alert("⚠️ Please fill in all fields.");
                    return;
                }

                const item = document.querySelector(`[data-id="${currentEditId}"]`);
                if (!item) {
                    alert("Item not found (it may have been removed).");
                    if (editModalInstance) editModalInstance.hide();
                    return;
                }

                // update dataset and DOM
                item.dataset.text = qText;
                item.dataset.choices = JSON.stringify(choices);
                item.dataset.correct = correct;
                item.dataset.points = points;

                // keep original question numbering (compute index from position)
                const index = Array.from(questionsList.children).indexOf(item) + 1;
                item.innerHTML = buildQuestionCardHtml(currentEditId, index, qText, choices, correct, points);

                // hide modal
                if (editModalInstance) editModalInstance.hide();
                currentEditId = null;
                rebuildHiddenInputs();
            });

            function rebuildHiddenInputs() {
                Array.from(questionsList.children).forEach((child, idx) => {
                    const text = child.dataset.text || '';
                    const choices = JSON.parse(child.dataset.choices || '["","","",""]');
                    const correct = child.dataset.correct || '1';
                    const points = child.dataset.points || '0';

                    // remove old hidden inputs
                    child.querySelectorAll('input[type="hidden"]').forEach(i => i.remove());

                    // append fresh hidden inputs
                    const hidden = `
                        <input type="hidden" name="questions[${idx}][question_text]" value="${escapeHtml(text)}">
                        <input type="hidden" name="questions[${idx}][option1]" value="${escapeHtml(choices[0])}">
                        <input type="hidden" name="questions[${idx}][option2]" value="${escapeHtml(choices[1])}">
                        <input type="hidden" name="questions[${idx}][option3]" value="${escapeHtml(choices[2])}">
                        <input type="hidden" name="questions[${idx}][option4]" value="${escapeHtml(choices[3])}">
                        <input type="hidden" name="questions[${idx}][correct_answer]" value="${escapeHtml(correct)}">
                        <input type="hidden" name="questions[${idx}][question_points]" value="${escapeHtml(points)}">
                    `;
                    child.insertAdjacentHTML('beforeend', hidden);
                });
            }


            // DELETE QUESTION BY ID
            window.deleteQuestionById = function(id) {
                if (!confirm("Are you sure you want to delete this question?")) return;
                const item = document.querySelector(`[data-id="${id}"]`);
                if (item) item.remove();

                // re-number remaining questions
                Array.from(questionsList.children).forEach((child, idx) => {
                    const id = child.dataset.id;
                    const text = child.dataset.text || '';
                    const choices = JSON.parse(child.dataset.choices || '["","","",""]');
                    const correct = child.dataset.correct || '1';
                    const points = child.dataset.points || '0';
                    child.innerHTML = buildQuestionCardHtml(id, idx + 1, text, choices, correct, points);
                });
                rebuildHiddenInputs();
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
            const addQuiz = document.getElementById('add_quiz');
            addQuiz.addEventListener('submit', (e) => {
                const level = addQuiz.querySelector('input[name="quiz_level"]').value;

                if (existingLevel.includes(level)) {
                    e.preventDefault();
                    alert("That quiz level already exists. Please choose another one.");
                    return;
                }
            });
        </script>
    </body>
</html>

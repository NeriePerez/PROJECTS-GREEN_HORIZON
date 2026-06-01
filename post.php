<?php 
    include 'controllerQuiz&Challenge.php';

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

    // Fetch today’s challenge to display
    $query = $conn->query("SELECT daily_id, challenge_id FROM daily_challenge WHERE user_id = $user_id AND assigned_date = CURDATE()")->fetch_assoc();
    $result = null;
    $challenge_id = $query['challenge_id'];
    if ($query && isset($query['challenge_id'])) {
        $result = $conn->query("SELECT challenge_title, challenge_points FROM challenges WHERE challenge_id = $challenge_id")->fetch_assoc();
    } 
    $points = $result['challenge_points'];
    $daily_id = $query['daily_id'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Green Horizon | Home</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            /* GENERAL */
            
            body {
                background-color: #F9FAF8;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
                color: #2E7D32;
            }

            /* Navbar */
            .navbar {
                background-color: #2E7D32;
            }
            .navbar-brand {
                color: white !important;
                font-weight: bold;
            }

            /* Container */
            .post-container {
                max-width: 1080px;
                margin: 3rem auto;
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                padding: 2rem;
                margin-top: 200px;
                margin-bottom: 150px;
            }

            /* Upload Box */
            .photo-upload {
                width: 150px;
                height: 150px;
                cursor: pointer;
                font-size: 60px;
                color: #2E7D32;
                background-color: #F9FAF8;
                border: 2px dashed #2E7D32;
                border-radius: 10px;
                transition: background-color 0.3s, color 0.3s;
            }
            .photo-upload:hover {
                background-color: #2E7D32;
                color: white;
            }

            /* Preview Images */
            .preview-images img {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border-radius: 10px;
                margin-right: 10px;
                margin-bottom: 10px;
                border: 2px solid #2E7D32;
            }

            /* Buttons */
            .btn-success {
                background-color: #2E7D32 !important;
                border: none;
            }
            .btn-success:hover {
                background-color: #256628 !important;
            }

            .btn-outline-success {
                border-color: #2E7D32;
                color: #2E7D32;
            }
            .btn-outline-success:hover {
                background-color: #2E7D32;
                color: white;
            }

            /* Footer */
            footer {
                background-color: #2E7D32;
                color: white;
                text-align: center;
                padding: 1rem 0;
                margin-top: 3rem;
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

        <!--POST CONTAINER-->
        <div class="post-container">
            <h1 class="text-center fw-bold">Share Your Accomplishment!</h1>
            <p class="text-center text-muted mb-5">
                Tell everyone about the challenge you just completed and inspire others 🌱
            </p>

            <form id="postForm" method="POST" action="controllerQuiz&Challenge.php" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                <input type="hidden" name="daily_id" value="<?= htmlspecialchars($daily_id) ?>">
                <input type="hidden" name="challenge_id" value="<?= htmlspecialchars($challenge_id) ?>">
                <input type="hidden" name="points" value="<?= htmlspecialchars($points) ?>">
                <input type="hidden" name="challenge_post" value="1">
                <?php if ($result): ?>
                    <h5 class="fw-semibold mb-3 text-success"><?= htmlspecialchars($result['challenge_title']) ?></h5>
                <?php else: ?>
                    <p>Error occured. The service will be back soon!</p>
                <?php endif; ?>

                <div class="mb-3">
                <label for="caption" class="form-label fw-semibold">Caption</label>
                <textarea
                    class="form-control border-success"
                    id="caption"
                    name="caption"
                    rows="3"
                    placeholder="Write something about your challenge..."></textarea>
                </div>

                <div class="mb-3">
                <label class="form-label fw-semibold">Upload up to 5 pictures</label>
                <div class="d-flex align-items-start gap-4 flex-wrap">
                    <div id="uploadBox" class="photo-upload d-flex justify-content-center align-items-center">
                    +
                    </div>
                    
                    <div class="preview-images d-flex flex-wrap" id="previewContainer"></div>
                </div>
                <small class="text-muted" id="counterText">0 / 5 uploaded</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-success py-2 px-4 fs-5" onclick="window.location.href='index.php?user_id=<?= htmlspecialchars($user_id) ?>'">
                        Discard
                    </button>
                    <button id="nextBtn" type="submit" class="btn btn-success py-2 px-4 fs-5">
                        Post
                    </button>
                </div>
            </form>
        </div>

        <footer>
        <p>© 2025 Green Horizon | Building a Greener Tomorrow</p>
        </footer>

        <script>
            const uploadBox = document.getElementById('uploadBox');
            const previewContainer = document.getElementById('previewContainer');
            const counterText = document.getElementById('counterText');
            const nextBtn = document.getElementById('nextBtn');

            const form = document.getElementById('postForm');
            const fileInputs = [];
            const uploadedSlots = [null, null, null, null, null]; // track files

            // Create hidden file inputs
            for (let i = 0; i < 5; i++) {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = `image${i+1}`;
                input.accept = 'image/*';
                input.style.display = 'none';
                form.appendChild(input);
                fileInputs.push(input);

                input.addEventListener('change', () => {
                    const file = input.files[0];
                    if (!file) return;

                    uploadedSlots[i] = file;

                    const imgWrapper = document.createElement('div');
                    imgWrapper.classList.add('position-relative', 'me-2', 'mb-2');

                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.width = '150px';
                    img.style.height = '150px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '10px';

                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '×';
                    removeBtn.classList.add('btn', 'btn-sm', 'btn-danger', 'position-absolute');
                    removeBtn.style.top = '5px';
                    removeBtn.style.right = '5px';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.style.padding = '2px 8px';

                    removeBtn.addEventListener('click', () => {
                        imgWrapper.remove();
                        input.value = '';
                        uploadedSlots[i] = null;
                        updateCounter();
                        updateUploadState();
                    });

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(removeBtn);
                    previewContainer.appendChild(imgWrapper);

                    updateCounter();
                    updateUploadState();
                });
            }

            // Click "+" box
            uploadBox.addEventListener('click', () => {
                const nextEmpty = uploadedSlots.findIndex(f => f === null);
                if (nextEmpty === -1) return alert('You can only upload up to 5 pictures.');
                fileInputs[nextEmpty].click();
            });

            // Counter
            function updateCounter() {
                const count = uploadedSlots.filter(f => f !== null).length;
                counterText.textContent = `${count} / 5 uploaded`;
            }

            // Update upload box state
            function updateUploadState() {
                const count = uploadedSlots.filter(f => f !== null).length;
                if (count >= 5) {
                    uploadBox.style.opacity = '0.5';
                    uploadBox.style.pointerEvents = 'none';
                } else {
                    uploadBox.style.opacity = '1';
                    uploadBox.style.pointerEvents = 'auto';
                }
            }

            // Minimum 3 images check
            nextBtn.addEventListener('click', (e) => {
                const count = uploadedSlots.filter(f => f !== null).length;
                if (count < 3) {
                    e.preventDefault();
                    alert("Please upload at least 3 pictures before posting.");
                }
            });
        </script>

    </body>
</html>
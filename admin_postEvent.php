<?php 
    include 'admin_controllerEvent.php';

    $event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

    // Fetch event info from DB
    $stmt = $conn->prepare("SELECT * FROM `event` WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();

    if (!$event) {
        die("Event not found.");
    }

    $startDate = date("F d, Y h:i A", strtotime($event['event_start_datetime']));
    $endDate = date("F d, Y h:i A", strtotime($event['event_end_datetime']));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Green Horizon | Post Completed Event</title>
        <link rel="icon" type="image/png" href="img/GHLOGO.png">
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body {
                background-color: #f9faf8;
                /* FONT APPLIED: Poppins */
                font-family: 'Poppins', sans-serif;
            }

            /* NAVBAR */
            .navbar {
            background-color: #2e7d32;
            }
            .navbar-brand {
            color: white !important;
            font-weight: bold;
            }

            /* POST CONTAINER */
            .post-container {
            max-width: 1080px;
            margin: 3rem auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 150px;
            margin-bottom: 130px;
            }

            h1,
            h4,
            label,
            strong {
            color: #2e7d32;
            }

            p,
            small {
            color: #1b5e20;
            }

            hr {
            border-color: #a5d6a7;
            }

            /* PHOTO UPLOAD BOX */
            .photo-upload {
            width: 150px;
            height: 150px;
            cursor: pointer;
            font-size: 60px;
            color: #2e7d32;
            background-color: #f9faf8;
            border: 2px dashed #2e7d32;
            transition: all 0.3s ease;
            }

            .photo-upload:hover {
            background-color: #e8f5e9;
            color: #1b5e20;
            }

            .preview-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 2px solid #2e7d32;
            }

            /* BUTTONS */
            .btn-success {
            background-color: #2e7d32;
            border-color: #2e7d32;
            }
            .btn-success:hover {
            background-color: #256628;
            border-color: #256628;
            }

            .btn-outline-success {
            border-color: #2e7d32;
            color: #2e7d32;
            }
            .btn-outline-success:hover {
            background-color: #2e7d32;
            color: white;
            }

            /* FOOTER */
            footer {
            background-color: #2e7d32;
            text-align: center;
            padding: 1rem 0;
            margin-top: 3rem;
            }

            footer, footer p{
            color: white !important;
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
        </nav>

        <div class="post-container">
            <h1 class="text-center fw-bold">Post Completed Event!</h1>
            <div class="mb-4">
                <h4 class="fw-bold"><?= htmlspecialchars($event['event_title']) ?></h4>
                <small class="text-muted d-block mb-2">
                    <?= $startDate ?> – <?= $endDate ?> • <?= htmlspecialchars($event['event_location']) ?>
                </small>
                <p>
                    <?= nl2br(htmlspecialchars($event['event_caption'])) ?>
                </p>
                <p>
                    <strong>Funds: ₱<?= number_format($event['event_fund'], 2) ?></strong>
                </p>
            </div>
            <hr />

            <form id="postForm" method="POST" action="admin_controllerEvent.php" enctype="multipart/form-data">
                <input type="hidden" name="eventPost" value="1">
                <input type="hidden" name="event_id" value="<?= htmlspecialchars($event_id) ?>">

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
                    <button type="button" class="btn btn-outline-success py-2 px-4 fs-5" onclick="window.location.href='admin_index.php'">
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
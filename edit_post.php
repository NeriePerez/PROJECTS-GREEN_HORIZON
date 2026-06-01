<?php 
    include 'controllerQuiz&Challenge.php';

    $postDetails_id = isset($_GET['postDetails_id']) ? intval($_GET['postDetails_id']) : 0;
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

    // Fetch old post data
    $stmt = $conn->prepare("SELECT * FROM post_details WHERE postDetails_id = ?");
    $stmt->bind_param("i", $postDetails_id);
    $stmt->execute();
    $post = $stmt->get_result()->fetch_assoc();

    // Collect existing images
    $oldImages = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($post["img$i"])) {
            $oldImages[] = $post["img$i"];
        }
    }
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
            <a class="navbar-brand d-flex align-items-center">
                <img src="img/GHLOGO.png" alt="Logo" width="50" height="50" class="me-2 rounded-circle" />
                Green Horizon
            </a>
        </nav>

        <!--POST CONTAINER-->
        <div class="post-container">
            <h1 class="text-center fw-bold">Edit Post</h1>

            <form id="editForm" method="POST" action="controllerQuiz&Challenge.php" enctype="multipart/form-data">
                <input type="hidden" name="edit_post" value="1">
                <input type="hidden" name="postDetails_id" value="<?= $postDetails_id ?>">    
                <input type="hidden" name="user_id" value="<?= $user_id ?>">    

                <?php if ($result): ?>
                    <h5 class="fw-semibold mb-3 text-success"><?= htmlspecialchars($result['challenge_title']) ?></h5>
                <?php else: ?>
                    <p>Error occured. The service will be back soon!</p>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="fw-semibold">Caption</label>
                    <textarea name="caption" rows="3" class="form-control"><?= htmlspecialchars($post['caption']) ?></textarea>
                </div>

                <div class="mb-3">
                <label class="form-label fw-semibold">Upload up to 5 pictures</label>
                <div class="d-flex align-items-start gap-4 flex-wrap">
                    <div id="uploadBox" class="photo-upload d-flex justify-content-center align-items-center">
                    +
                    </div>
                    
                    <div id="previewContainer" class="d-flex flex-wrap gap-2">
                        <?php 
                        $imgIndex = 1;
                        foreach ($oldImages as $img): ?>
                            <div class="position-relative old-img-wrapper" data-slot="<?= $imgIndex-1 ?>">

                                <!-- show preview -->
                                <img src="<?= $img ?>" class="rounded border" width="150" height="150">

                                <!-- remove button -->
                                <button type="button" style="border-radius: 50%;"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 removeOldBtn"
                                        data-target="img<?= $imgIndex ?>">
                                    ×
                                </button>

                                <!-- hidden input the backend expects -->
                                <input type="hidden" 
                                    name="existing_images[]" 
                                    value="<?= $img ?>">

                            </div>
                        <?php 
                        $imgIndex++;
                        endforeach; ?>
                    </div>
                </div>
                <small class="text-muted" id="counterText">0 / 5 uploaded</small>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-success py-2 px-4 fs-5" onclick="window.location.href='profile.php?user_id=<?= htmlspecialchars($user_id) ?>'">
                    Discard
                </button>
                <button id="nextBtn" type="submit" class="btn btn-success py-2 px-4 fs-5">
                    Save Post Changes
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
            const form = document.getElementById('editForm');

            let maxImages = 5;

            // Track slots: null = empty, "old" = existing image, File object = new image
            const uploadedSlots = [null, null, null, null, null];

            // Track file inputs for new images
            const fileInputs = [];

            // Mark old images
            document.querySelectorAll('.old-img-wrapper').forEach((wrapper, index) => {
                uploadedSlots[index] = "old";
            });

            // Create hidden inputs for new images
            for (let i = 0; i < maxImages; i++) {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = `image${i + 1}`;
                input.accept = 'image/*';
                input.style.display = 'none';
                form.appendChild(input);
                fileInputs.push(input);

                input.addEventListener('change', () => {
                    const file = input.files[0];
                    if (!file) return;

                    // Find first free slot
                    const slot = uploadedSlots.findIndex(s => s === null);
                    if (slot === -1) {
                        alert("Maximum of 5 pictures allowed.");
                        input.value = '';
                        return;
                    }

                    uploadedSlots[slot] = file;

                    // Create preview
                    const imgWrapper = document.createElement('div');
                    imgWrapper.classList.add('position-relative', 'me-2', 'mb-2');
                    imgWrapper.dataset.slot = slot;

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
                        uploadedSlots[slot] = null;
                        input.value = '';
                        updateCounter();
                        updateUploadState();
                    });

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(removeBtn);
                    previewContainer.appendChild(imgWrapper);

                    updateCounter();
                    updateUploadState();
                });
            };

            // Remove old images
            document.querySelectorAll('.removeOldBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const wrapper = btn.parentElement;
                    const slot = parseInt(wrapper.dataset.slot);

                    // Mark slot as empty
                    uploadedSlots[slot] = null;

                    // Remove hidden input
                    const hiddenInput = wrapper.querySelector('input[name="existing_images[]"]');
                    if (hiddenInput) hiddenInput.remove();

                    wrapper.remove();
                    updateCounter();
                    updateUploadState();
                });
            });

            // Click to upload
            uploadBox.addEventListener('click', () => {
                const nextEmpty = uploadedSlots.findIndex(s => s === null);
                if (nextEmpty === -1) return alert("Maximum of 5 pictures allowed.");
                fileInputs[nextEmpty].click();
            });

            function updateCounter() {
                const count = uploadedSlots.filter(x => x !== null).length;
                counterText.textContent = `${count} / 5 uploaded`;
            }

            function updateUploadState() {
                const count = uploadedSlots.filter(x => x !== null).length;
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
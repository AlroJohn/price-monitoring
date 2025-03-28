<?php
require('../includes/connection.php'); // Adjust path if needed

// Fetch store categories for the dropdown
$store_query = $db->query("SELECT STORE_ID, CATEGORY FROM store_type");
$stores = $store_query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="../js/city.js"></script> <!-- Add the correct path to city.js -->
    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #3b9fc4;
        }

        .container {
            max-width: 600px;
            padding: 20px;
            background-color: rgb(15, 79, 104);
            box-shadow: 15px 15px 10px rgb(26, 25, 25);
        }

        .form-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-box h3 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .btn-primary {
            width: 100%;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
        }

        .form-navigation .btn {
            width: 48%;
        }

        .active {
            background-color: whitesmoke;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container" style="border-radius: 20px;">
            <div class="row mb-3">
                <div class="col-md-6">
                    <a href="login-admin.php" class="btn active w-100" style=" background-color:Whitesmoke;">
                        <i class="fas fa-flip-horizontal fa-fw fa-user"></i> LOGIN
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="create_account.php" class="btn btn-primary active w-100">
                        <i class="fas fa-flip-horizontal fa-fw fa-user"></i> REGISTER
                    </a>
                </div>
            </div>

            <div class="form-box mt-0">
                <h3>Register Account</h3>
                <form method="POST" action="create_process.php" id="accountForm" enctype="multipart/form-data">

                    <!-- Step 1: Personal Information -->
                    <div class="form-step active" id="step1">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="middle_name" class="form-label">Middle Name (Optional)</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Suffix (Optional)</label>
                                    <input type="text" name="suffix" id="suffix" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>

                    <!-- Step 2: Store Information -->
                    <div class="form-step" id="step2">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="shop_name" class="form-label">Store/Shop Name</label>
                                    <input type="text" name="shop_name" id="shop_name" class="form-control" required>
                                    <div id="store-status"></div> <!-- This will show the status message -->
                                </div>
                            </div>
                        </div>

                        <!-- Store Type Dropdown -->
                        <div class="mb-3">
                            <label for="store_id" class="form-label">Store Type</label>
                            <select name="store_id" id="store_id" class="form-select" required>
                                <option value="">Type of Store</option>
                                <?php foreach ($stores as $store): ?>
                                    <option value="<?= $store['STORE_ID'] ?>"><?= htmlspecialchars($store['CATEGORY']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Location Fields -->
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="purok" class="form-label">Purok</label>
                                    <input type="text" name="purok" id="purok" class="form-control" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="barangay" class="form-label">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Credentials -->
                    <div class="form-step" id="step3">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="document_1" class="form-label">Upload Document</label>
                                    <input type="file" name="document_1" id="document_1" class="form-control" required>
                                    <!-- Mini details text with smaller font size and less opacity -->
                                    <small style="font-size: 0.8rem; opacity: 0.7;">(ex. Barangay Permit, Business
                                        Permit..)</small>
                                    <!-- Link to open modal for Document 1 preview (Initially hidden) -->
                                    <a href="#" id="previewDoc1" data-bs-toggle="modal" data-bs-target="#previewModal"
                                        style="display:none;">Preview</a>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="document_2" class="form-label">Upload Valid ID</label>
                                    <input type="file" name="document_2" id="document_2" class="form-control" required>
                                    <!-- Mini details text with smaller font size and less opacity -->
                                    <small style="font-size: 0.8rem; opacity: 0.7;">(ex. National ID, Drivers
                                        ID..)</small><br>
                                    <!-- Link to open modal for Document 2 preview (Initially hidden) -->
                                    <a href="#" id="previewDoc2" data-bs-toggle="modal" data-bs-target="#previewModal"
                                        style="display:none;">Preview</a>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for preview -->
                        <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="previewModalLabel">File Preview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe id="filePreview" width="100%" height="400px"
                                            style="display:none;"></iframe>
                                        <img id="imagePreview" width="100%" height="400px" style="display:none;" />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                    <div id="username-status"></div> <!-- This will show the status message -->
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control"
                                            required>
                                        <span class="input-group-text" id="eye-icon">
                                            <i class="fa-solid fa-eye" onclick="togglePasswordVisibility()"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Review Information -->
                    <div class="form-step" id="step4">
                        <div id="reviewInfo">
                            <h4>Review Your Information</h4>
                            <p><strong>First Name:</strong> <span id="reviewFirstName"></span></p>
                            <p><strong>Middle Name:</strong> <span id="reviewMiddleName"></span></p>
                            <p><strong>Last Name:</strong> <span id="reviewLastName"></span></p>
                            <p><strong>Suffix:</strong> <span id="reviewSuffix"></span></p>
                            <p><strong>Gender:</strong> <span id="reviewGender"></span></p>
                            <p><strong>Phone Number:</strong> <span id="reviewPhoneNumber"></span></p>
                            <p><strong>Purok:</strong> <span id="reviewPurok"></span></p>
                            <p><strong>Barangay:</strong> <span id="reviewBarangay"></span></p>
                            <p><strong>Email:</strong> <span id="reviewEmail"></span></p>
                            <p><strong>Username:</strong> <span id="reviewUsername"></span></p>
                            <p><strong>Store Type:</strong> <span id="reviewStoreType"></span></p>
                            <p><strong>Store/Shop Name:</strong> <span id="reviewShopName"></span></p>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="form-navigation">
                        <button type="button" class="btn btn-secondary" id="prevBtn"
                            onclick="changeStep(-1)">Previous</button>
                        <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn"
                            style="display: none;">Submit</button>
                    </div>
                </form>
            </div>
            <!-- Back Button -->
            <div class="text-center mt-3">
                <a href="/price-monitoring/user/pages/price_monitoring.php" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Home Page
                </a>
            </div>
        </div>
    </div>

    <script>
        var currentStep = 0;
        var steps = document.querySelectorAll('.form-step');

        function changeStep(step) {
            // Hide current step
            steps[currentStep].classList.remove('active');
            // Change step index
            currentStep += step;

            // If we're at the last step, show the submit button
            if (currentStep == steps.length - 1) {
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('submitBtn').style.display = 'block';
            } else {
                document.getElementById('nextBtn').style.display = 'block';
                document.getElementById('submitBtn').style.display = 'none';
            }

            // If we're at the first step, hide the "previous" button
            if (currentStep == 0) {
                document.getElementById('prevBtn').style.display = 'none';
            } else {
                document.getElementById('prevBtn').style.display = 'block';
            }

            // Show the next step
            steps[currentStep].classList.add('active');

            // For the review step, populate the values 
            if (currentStep === 3) {
                console.log('Review Step Triggered');
                // Populate the review fields with the form values
                document.getElementById('reviewFirstName').textContent = document.getElementById('first_name').value;
                document.getElementById('reviewMiddleName').textContent = document.getElementById('middle_name').value;
                document.getElementById('reviewLastName').textContent = document.getElementById('last_name').value;
                document.getElementById('reviewSuffix').textContent = document.getElementById('suffix').value;
                document.getElementById('reviewGender').textContent = document.getElementById('gender').value;
                document.getElementById('reviewPhoneNumber').textContent = document.getElementById('phone_number').value;
                document.getElementById('reviewPurok').textContent = document.getElementById('purok').value;
                document.getElementById('reviewBarangay').textContent = document.getElementById('barangay').value;
                document.getElementById('reviewEmail').textContent = document.getElementById('email').value;
                document.getElementById('reviewUsername').textContent = document.getElementById('username').value;
                document.getElementById('reviewStoreType').textContent = document.getElementById('store_id').options[document.getElementById('store_id').selectedIndex].text;
                document.getElementById('reviewShopName').textContent = document.getElementById('shop_name').value;
            }
        }

        // Initially hide the "Previous" button
        document.getElementById('prevBtn').style.display = 'none';

        // Toggle password visibility function
        function togglePasswordVisibility() {
            var passwordField = document.getElementById('password');
            var eyeIcon = document.getElementById('eye-icon').getElementsByTagName('i')[0];

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }

        // Initialize first step visibility
        changeStep(0);

        window.onload = function () {
            var $ = new City();
            $.showProvinces("#province");
            $.showCities("#municipality");
        };
    </script>

    <script>
        // Username availability checker
        document.getElementById('username').addEventListener('input', function () {
            var username = this.value;
            var statusDiv = document.getElementById('username-status');

            if (username.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'check_availability.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        statusDiv.innerHTML = xhr.responseText;
                        if (xhr.responseText.includes('unavailable')) {
                            statusDiv.style.color = 'red';
                        } else {
                            statusDiv.style.color = 'green';
                        }
                    }
                };
                xhr.send('username=' + encodeURIComponent(username));
            } else {
                statusDiv.innerHTML = '';
            }
        });

        // Store name availability checker
        document.getElementById('shop_name').addEventListener('input', function () {
            var shopName = this.value;
            var statusDiv = document.getElementById('store-status');

            if (shopName.length > 0) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'check_availability.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        statusDiv.innerHTML = xhr.responseText;
                        if (xhr.responseText.includes('unavailable')) {
                            statusDiv.style.color = 'red';
                        } else {
                            statusDiv.style.color = 'green';
                        }
                    }
                };
                xhr.send('shop_name=' + encodeURIComponent(shopName));
            } else {
                statusDiv.innerHTML = '';
            }
        });
    </script>

    <script>
        // Function to preview document 1
        document.getElementById('document_1').addEventListener('change', function () {
            var file = this.files[0];
            var previewLink = document.getElementById('previewDoc1');
            var filePreview = document.getElementById('filePreview');
            var imagePreview = document.getElementById('imagePreview');

            // If a file is selected, show the preview link
            if (file) {
                previewLink.style.display = 'inline';  // Show the preview link
            } else {
                previewLink.style.display = 'none';  // Hide the preview link
            }

            // File preview functionality when preview link is clicked
            previewLink.addEventListener('click', function (e) {
                if (!file) {
                    e.preventDefault(); // Prevent modal from opening if no file is selected
                } else {
                    if (file.type.includes('image')) {
                        imagePreview.style.display = 'block';
                        filePreview.style.display = 'none';
                        imagePreview.src = URL.createObjectURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                        filePreview.style.display = 'block';
                        filePreview.src = URL.createObjectURL(file);
                    }
                }
            });
        });

        // Function to preview document 2
        document.getElementById('document_2').addEventListener('change', function () {
            var file = this.files[0];
            var previewLink = document.getElementById('previewDoc2');
            var filePreview = document.getElementById('filePreview');
            var imagePreview = document.getElementById('imagePreview');

            // If a file is selected, show the preview link
            if (file) {
                previewLink.style.display = 'inline';  // Show the preview link
            } else {
                previewLink.style.display = 'none';  // Hide the preview link
            }

            // File preview functionality when preview link is clicked
            previewLink.addEventListener('click', function (e) {
                if (!file) {
                    e.preventDefault(); // Prevent modal from opening if no file is selected
                } else {
                    if (file.type.includes('image')) {
                        imagePreview.style.display = 'block';
                        filePreview.style.display = 'none';
                        imagePreview.src = URL.createObjectURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                        filePreview.style.display = 'block';
                        filePreview.src = URL.createObjectURL(file);
                    }
                }
            });
        });
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Business</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/YOUR_FA_KIT.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Top Navigation -->
    <div class="bg-blue-600 shadow-md py-3 px-6 flex justify-between items-center">
        <div>
            <img src="../img/logo.png" alt="Logo" class="h-16">
        </div>
        <div>
            <a href="login-admin.php" class="text-white text-lg font-semibold hover:underline">
                Back to Login
            </a>
        </div>
    </div>

    <!-- Centered Registration Form -->
    <div class="flex justify-center items-center flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Register Your Business</h2>

            <!-- Success Message -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <p class="text-green-500 text-sm text-center mb-4">
                    <?= $_SESSION['success_message'];
                    unset($_SESSION['success_message']); ?>
                </p>
            <?php endif; ?>

            <form action="process_create_account.php" method="POST" enctype="multipart/form-data" id="registrationForm">
                <!-- Step 1: Personal Information -->
                <div class="form-step block" id="step1">
                    <h3 class="text-lg font-semibold mb-2">Step 1: Personal Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">First Name</label>
                            <input type="text" name="first_name" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Last Name</label>
                            <input type="text" name="last_name" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium">Phone Number</label>
                        <input type="text" name="phone_number" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg" required>
                    </div>
                </div>

                <!-- Step 2: Business Details -->
                <div class="form-step hidden" id="step2">
                    <h3 class="text-lg font-semibold mb-2">Step 2: Business Details</h3>
                    <label class="block text-sm font-medium">Shop Name</label>
                    <input type="text" name="shop_name" class="w-full px-4 py-2 border rounded-lg" required>

                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium">Purok</label>
                            <input type="text" name="purok" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Barangay</label>
                            <input type="text" name="barangay" class="w-full px-4 py-2 border rounded-lg" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium">Store Type</label>
                        <select name="store_id" class="w-full px-4 py-2 border rounded-lg" required>
                            <option value="" disabled selected>Select Store Type</option>
                            <option value="1">Meat Shop</option>
                            <option value="2">Poultry</option>
                            <option value="3">Hardware</option>
                        </select>
                    </div>
                </div>

                <!-- Step 3: Credentials & Documents -->
                <div class="form-step hidden" id="step3">
                    <h3 class="text-lg font-semibold mb-2">Step 3: Credentials & Documents</h3>
                    <label class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg" required>

                    <label class="block text-sm font-medium mt-4">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg" required>

                    <label class="block text-sm font-medium mt-4">Upload Document 1 (Required)</label>
                    <input type="file" name="document_1" class="w-full px-4 py-2 border rounded-lg" required>

                    <label class="block text-sm font-medium mt-4">Upload Document 2 (Optional)</label>
                    <input type="file" name="document_2" class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Step 4: Review & Submit -->
                <div class="form-step hidden" id="step4">
                    <h3 class="text-lg font-semibold mb-2">Step 4: Review & Submit</h3>
                    <p>Check your details and click **Register**.</p>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg" id="prevBtn"
                        onclick="changeStep(-1)">Previous</button>
                    <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-lg" id="nextBtn"
                        onclick="changeStep(1)">Next</button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hidden"
                        id="submitBtn">Register Business</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll('.form-step');

        function changeStep(step) {
            steps[currentStep].classList.add('hidden');
            currentStep += step;
            steps[currentStep].classList.remove('hidden');

            document.getElementById('prevBtn').style.display = currentStep === 0 ? 'none' : 'block';
            document.getElementById('nextBtn').style.display = currentStep === steps.length - 1 ? 'none' : 'block';
            document.getElementById('submitBtn').style.display = currentStep === steps.length - 1 ? 'block' : 'none';
        }

        document.getElementById('prevBtn').style.display = 'none';
    </script>
</body>

</html>
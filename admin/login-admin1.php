<?php require('session.php'); ?>
<?php if (logged_in()) { ?>
  <script type="text/javascript">
    window.location = "index.php";
  </script>
  <?php
} ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <link rel="icon" href="..\icon\logo\logo-96.png">
  <title>B'S STORE - Admin Login</title>

  <!--CSS LINKS-->
  <link rel="stylesheet" href="..\css\disable-text-select">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="..\css\login_style.css" rel="stylesheet">


</head>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
  }

  body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #3b9fc4;
    /*393E46;*/
  }

  .container {
    width: 100%;
    max-width: 400px;
    padding: 20px;
    background-color: rgb(15, 79, 104);
    box-shadow: 15px 15px 10px rgb(26, 25, 25);
    /*, -10px -10px 15px rgb(143, 211, 211)*/
    /*box-shadow: 0px 0px 30px 5px #74d0d4;*/
  }


  .login-box {
    background-color: #fff;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 10);
  }

  .login-box h3 {
    margin-top: -10px;
    margin-bottom: 40px;
    padding: 0;
    color: #333;
    text-align: center;
    text-transform: uppercase;
  }


  .user-box {
    position: relative;
    margin-bottom: 40px;
  }

  .user-box input {
    width: 100%;
    padding: 2px 0;
    font-size: 18px;
    color: #333;
    margin-top: 0px;
    border: none;
    border-bottom: 2px solid #333;
    outline: none;
    background: transparent;
  }

  /* ADDED CODE */
  .user-box label {
    position: absolute;
    top: 25px;
    left: 0;
    padding: 10px 0;
    font-size: 16px;
    color: #020202;
    pointer-events: none;
    transition: .5s;
  }


  /* END OF ADDED CODE */

  button {
    display: inline-block;
    background-color: #333;
    color: #fff;
    padding: 10px 0px;
    font-size: 16px;
    text-transform: uppercase;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    transition: 0.5s;
    letter-spacing: 2px;
    border-radius: 5px;
  }

  button:hover {
    background-color: #fff;
    color: #333;
    border: 1px solid #333;
  }

  button span {
    position: absolute;
    display: block;
  }

  button span:nth-child(1) {
    top: 0;
    left: -100%;
    width: 100%;
    height: 2px;
    background-color: #333;
    animation: animate1 1s linear infinite;
  }

  @keyframes animate1 {
    0% {
      left: -100%;
    }

    50%,
    100% {
      left: 100%;
    }
  }

  button span:nth-child(2) {
    top: -100%;
    right: 0;
    width: 2px;
    height: 100%;
    background-color: #333;
    animation: animate2 1s linear infinite;
    animation-delay: 0.25s;
  }

  @keyframes animate2 {
    0% {
      top: -100%;
    }

    50%,
    100% {
      top: 100%;
    }
  }

  button span:nth-child(3) {
    bottom: 0;
    right: -100%;
    width: 100%;
    height: 2px;
    background-color: #333;
    animation: animate3 1s linear infinite;
    animation-delay: 0.5s;
  }

  @keyframes animate3 {
    0% {
      right: -100%;
    }

    50%,
    100% {
      right: 100%;
    }
  }

  button span:nth-child(4) {
    bottom: -100%;
    left: 0;
    width: 2px;
    height: 100%;
    background-color: #333;
    animation: animate4 1s linear infinite;
    animation-delay: 0.75s;
  }

  @keyframes animate4 {
    0% {
      bottom: -100%;
    }

    50%,
    100% {
      bottom: 100%;
    }
  }

  .password-toggle-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
  }

  .password-toggle-icon i {
    font-size: 18px;
    line-height: 1;
    color: #333;
    transition: color 0.3s ease-in-out;
    margin-bottom: 20px;
  }

  .password-toggle-icon i:hover {
    color: #000;
  }

  /*This is style for user buttons*/
  .d-flex {
    margin-bottom: 5%;
  }

  .d-flex a {
    text-decoration: none;
    color: black;
    /* Change the text color */
    padding: 8px 15px;
    /* Adjust padding for each link */
    border-radius: 5px;
    width: 49%;
  }

  .active {
    background-color: whitesmoke;
  }

  /*DIS IS FOR DA NOTIFICATION */
  .notification {
    padding: 8px 12px;
    margin: 10px auto;
    border-radius: 5px;
    text-align: center;
    font-weight: normal;
    /* Less bold */
    font-size: 16px;
    /* Reduce font size */
    display: none;
    /* Hide by default */
    max-width: 380px;
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1000;
  }

  .error {
    background-color: #ffcccc;
    color: #d8000c;
    border: 1px solid #d8000c;
  }


  .show {
    display: block !important;
    /* Show when JavaScript adds .show class */
  }
</style>

<body>
  <!-- Error Notification Message -->


  <?php
  $errorMessage = "";
  if (isset($_GET['usernotexist'])) {
    $errorMessage = "😭 User not Registered!";
  } elseif (isset($_GET['wrongcredentials'])) {
    $errorMessage = "😓 Wrong Credentials!";
  } elseif (isset($_GET['pending'])) {
    $errorMessage = "🕒 User application under review!";
  } elseif (isset($_GET['missingpassword'])) {
    $errorMessage = "😟 Password is missing!";
  }
  ?>

  <!-- Error Notification -->
  <?php if (!empty($errorMessage)): ?>
    <div id="error-notification" class="notification error">
      <?= $errorMessage ?>
    </div>
  <?php endif; ?>

  <div class="container ">
    <!-- Login and Register Buttons -->
    <div class="d-flex justify-content-between" style="width: 100%;">
      <a href="login-admin.php" class="btn btn-primary bg-gradient-primary" style="color: whitesmoke;">
        <i class="fas fa-flip-horizontal fa-fw fa-user"></i> LOGIN
      </a>
      <a href="create_account.php" class="btn active">
        <i class="fas fa-flip-horizontal fa-fw fa-user"></i> REGISTER
      </a>
    </div>

    <!-- Login Box -->
    <div class="login-box">
      <h3>STORE OWNER</h3>
      <form role="form" action="login_admin_process.php" method="post">
        <!-- Username Field -->
        <div class="user-box">
          <input id="username" name="user" type="text" />
          <label for="username">Username</label>
        </div>
        <!-- Password Field with Toggle Icon -->
        <div class="user-box">
          <input id="password" name="password" type="password" value="" />
          <label for="password">Password</label>
          <span class="password-toggle-icon"><i class="fas fa-eye"></i></span>
        </div>
        <!-- Login Button -->
        <button class="submit btn-user btn-block" type="submit" name="btnlogin">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          Login
        </button>
      </form>
    </div>

    <!-- Back Button to Home Page -->
    <div class="text-center mt-3">
      <a href="../../index.php" class="btn btn-secondary w-100">
        <i class="fas fa-arrow-left"></i> Home Page
      </a>
    </div>
  </div>

  <!-- Password Toggle Script -->
  <script type="text/javascript">
    const passwordField = document.getElementById("password");
    const togglePassword = document.querySelector(".password-toggle-icon i");

    togglePassword.addEventListener("click", function () {
      if (passwordField.type === "password") {
        passwordField.type = "text";
        togglePassword.classList.remove("fa-eye");
        togglePassword.classList.add("fa-eye-slash");
      } else {
        passwordField.type = "password";
        togglePassword.classList.remove("fa-eye-slash");
        togglePassword.classList.add("fa-eye");
      }
    });
  </script>

  <!-- Label Animation Script for Username & Password Fields -->
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
      const usernameField = document.getElementById('username');
      const passwordField = document.getElementById('password');
      const usernameLabel = document.querySelector('label[for="username"]');
      const passwordLabel = document.querySelector('label[for="password"]');

      // Function to update label style based on input value
      const updateLabelStyle = (field, label) => {
        if (field.value.trim() !== '') {
          label.style.top = '-26px';
          label.style.fontSize = '13px';
          label.style.color = '#020202';
          label.style.backgroundColor = 'transparent';
        } else {
          label.style.top = '-8px';
          label.style.fontSize = '18px';
          label.style.color = '#020202';
        }
      };

      // Apply hover effect on labels
      const applyHoverEffect = (label) => {
        label.style.top = '-26px';
        label.style.fontSize = '13px';
        label.style.color = '#020202';
        label.style.backgroundColor = 'transparent';
      };

      // Remove hover effect if field is empty
      const removeHoverEffect = (field, label) => {
        if (field.value.trim() === '') {
          label.style.top = '-8px';
          label.style.fontSize = '18px';
          label.style.color = '#020202';
        }
      };

      // Add event listeners for label animation and hover effects
      usernameField.addEventListener('input', () => updateLabelStyle(usernameField, usernameLabel));
      passwordField.addEventListener('input', () => updateLabelStyle(passwordField, passwordLabel));
      usernameField.addEventListener('mouseenter', () => applyHoverEffect(usernameLabel));
      usernameField.addEventListener('mouseleave', () => removeHoverEffect(usernameField, usernameLabel));
      usernameField.addEventListener('focus', () => applyHoverEffect(usernameLabel));
      usernameField.addEventListener('blur', () => removeHoverEffect(usernameField, usernameLabel));
      passwordField.addEventListener('mouseenter', () => applyHoverEffect(passwordLabel));
      passwordField.addEventListener('mouseleave', () => removeHoverEffect(passwordField, passwordLabel));
      passwordField.addEventListener('focus', () => applyHoverEffect(passwordLabel));
      passwordField.addEventListener('blur', () => removeHoverEffect(passwordField, passwordLabel));

      // Initialize label styles based on input values
      updateLabelStyle(usernameField, usernameLabel);
      updateLabelStyle(passwordField, passwordLabel);
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const notification = document.getElementById('error-notification');
      if (notification) {
        notification.classList.add('show');
        setTimeout(() => {
          notification.classList.remove('show');
        }, 4000);
      }
    });
  </script>
</body>


</html>
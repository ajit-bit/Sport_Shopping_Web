<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "registration"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error and success messages
$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare an SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO registrations (name, email, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $username, $hashed_password);

        // Execute and check for success
        if ($stmt->execute()) {
            $success_message = "Registration was successful! Redirecting to login page...";
            header("refresh:2; url=login.php"); // Redirect after 2 seconds
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">

    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f0f0f0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .login-box {
            width: 100%;
        }

        h2 {
            text-align: center;
            font-size: 30px;
            color: #333;
            margin-bottom: 30px;
        }

        .input-box {
            position: relative;
            margin-bottom: 30px;
        }

        .input-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #333;
            border: none;
            border-bottom: 2px solid #777;
            outline: none;
            background: transparent;
        }

        .input-box label {
            position: absolute;
            top: 0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: #777;
            pointer-events: none;
            transition: 0.5s;
        }

        .input-box input:focus ~ label,
        .input-box input:valid ~ label {
            top: -25px;
            left: 0;
            color: #ff5f6d;
            font-size: 12px;
        }

        .show-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 14px;
            color: #777;
            cursor: pointer;
        }

        .show-password:hover {
            color: #ff5f6d;
        }

        .forgot {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .forgot a {
            text-decoration: none;
            font-size: 14px;
            color: #777;
        }

        .forgot a:hover {
            color: #ff5f6d;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #ff5f6d;
            color: #fff;
            font-size: 18px;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #ff2d5f; 
        }

        .signup {
           text-align:center; 
           margin-top :20px; 
       }

       .signup p { 
           font-size :14px; 
           color :#777; 
       }

       .signup a { 
           color :#ff5f6d; 
           text-decoration :none; 
       }

       .signup a:hover { 
           text-decoration :underline; 
       }
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
    </style>
</head>
<body>
<?php include 'nav.html'; ?>
    <div class="login-container">
        <div class="login-box">
            <h2>Register</h2>
            <?php if ($error_message): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if ($success_message): ?>
                <p style="color: green;"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <form action="" method="POST" id="registrationForm">
                <div class="input-box">
                    <input type="text" name="name" required>
                    <label>Name</label>
                </div>
                <div class="input-box">
                    <input type="text" id="email" name="email" required>
                    <label>Email</label>
                    <div id="emailError" class="error-message">Please enter a valid email address.</div>
                </div>
                <div class="input-box">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input type="password" id="password" name="password" required>
                    <label>Password</label>
                    <button type="button" id="togglePassword" class="show-password">Show</button>
                </div>
                <div class="input-box">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <label>Confirm Password</label>
                    <button type="button" id="toggleConfirmPassword" class="show-password">Show</button>
                    <div id="passwordError" class="error-message">Passwords do not match.</div>
                </div>
                <button type="submit" class="login-btn">Register</button>
                <div class="signup">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>

            <script>
                // Toggle Password Visibility
                const togglePassword = document.getElementById('togglePassword');
                const password = document.getElementById('password');
                togglePassword.addEventListener('click', () => {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
                });

                const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
                const confirmPassword = document.getElementById('confirm_password');
                toggleConfirmPassword.addEventListener('click', () => {
                    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPassword.setAttribute('type', type);
                    toggleConfirmPassword.textContent = type === 'password' ? 'Show' : 'Hide';
                });

                // Form validation
                document.getElementById('registrationForm').addEventListener('submit', function(event) {
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;

                    let isValid = true;

                    // Email validation regex
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const emailError = document.getElementById('emailError');
                    if (!emailRegex.test(email)) {
                        emailError.style.display = 'block';
                        isValid = false;
                    } else {
                        emailError.style.display = 'none';
                    }

                    // Password matching check
                    const passwordError = document.getElementById('passwordError');
                    if (password !== confirmPassword) {
                        passwordError.style.display = 'block';
                        isValid = false;
                    } else {
                        passwordError.style.display = 'none';
                    }

                    if (!isValid) {
                        event.preventDefault();
                    }
                });
            </script>
        </div>
    </div>
</body>
</html>

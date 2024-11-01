<?php
    // Database connection
    $servername = "localhost";
    $dbUsername = "root";  // Default XAMPP username
    $dbPassword = "";      // Default XAMPP password
    $dbname = "registration"; // Your database name

    // Create connection
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize error message variable
    $error_message = "";

    // Process the login form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email_or_username = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        
        // Prepare a SQL statement to check if the user exists based on email or username
        $stmt = $conn->prepare("SELECT password FROM registrations WHERE (email = ? OR username = ?)");
        $stmt->bind_param("ss", $email_or_username, $email_or_username); // "ss" indicates two string types

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Bind the result
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Successful login
                header("Location: main.php");
                exit();
            } else {
                // Invalid password
                $error_message = "Invalid password. Please try again.";
            }
        } else {
            // User not found
            $error_message = "No account found with that email or username.";
        }

        // Close the statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form </title>
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
    </style>
</head>
<body>
<?php include 'nav.html'; ?>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
                <form action="" method="POST">
                    <div class="input-box">
                        <input type="text" name="email" required>
                        <label>Email or Username</label>
                    </div>
                    <div class="input-box">
                        <input type="password" id="password" name="password" required>
                        <label>Password</label>
                        <button type="button" id="togglePassword" class="show-password">Show</button>
                    </div>
                    <div class="forgot">
                        <a href="#">Forgot password?</a>
                    </div>
                    <button type="submit" class="login-btn">Login</button>
                    <div class="signup">
                        <p>Don't have an account? <a href="registration.php">Sign Up</a></p>
                    </div>
                </form>
                
                <script>
                    const togglePassword = document.getElementById('togglePassword');
                    const password = document.getElementById('password');
                    
                    togglePassword.addEventListener('click', () => {
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        
                        togglePassword.textContent = type === 'password' ? 'Sshow' : 'Hide';
                    });
                </script>
        </div>
    </div>
</body>
</html> 
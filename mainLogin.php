<?php
session_start();

// Destroys the session entirely when the logout button is clicked
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: mainLogin.php");
    exit();
}

// Direct Login - No Database Check!
if (isset($_POST['login'])) { 
    $username = $_POST['user'];
    
    // Instantly logs you in and sends you to the dashboard
    $_SESSION['admin'] = empty($username) ? "Admin" : $username;
    header("Location: bb1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank | Admin Portal</title>
    
    <!-- Importing a premium modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            /* Premium deep red diagonal gradient background */
            background: linear-gradient(135deg, #8B0000 0%, #4A0000 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 45px 40px;
            border-radius: 16px;
            /* Modern soft floating shadow */
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin: 20px;
        }

        .logo-icon {
            width: 65px;
            height: 65px;
            background: #fff0f0;
            color: #8B0000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
            box-shadow: 0 4px 10px rgba(139, 0, 0, 0.1);
        }

        .login-header h1 {
            font-size: 26px;
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 35px;
            font-weight: 400;
        }

        .input-group {
            text-align: left;
            margin-bottom: 22px;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #eef2f5;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            color: #333;
        }

        .input-group input::placeholder {
            color: #adb5bd;
        }

        /* Beautiful glowing focus effect */
        .input-group input:focus {
            border-color: #8B0000;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(139, 0, 0, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8B0000, #b30000);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(139, 0, 0, 0.2);
        }

        /* Smooth hover lift animation */
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 0, 0, 0.3);
            background: linear-gradient(135deg, #990000, #cc0000);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        /* Responsive design for smaller screens */
        @media screen and (max-width: 480px) {
            .login-container {
                padding: 35px 25px;
            }
            .login-header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Blood Drop Logo Placeholder -->
        <div class="logo-icon">🩸</div>
        
        <div class="login-header">
            <h1>Admin Portal</h1>
            <p>Secure login to Blood Bank System</p>
        </div>

        <form name="form" method="POST" action="">
            <div class="input-group">
                <label>Admin Username</label>
                <input type="text" name="user" placeholder="Enter username (Optional)" required>
            </div>
            
            <div class="input-group">
                <label>Security Password</label>
                <input type="password" name="pass" placeholder="Enter anything" required>
            </div>
            
            <button type="submit" name="login" class="btn-login">Secure Login</button>
        </form>
    </div>

</body>
</html>
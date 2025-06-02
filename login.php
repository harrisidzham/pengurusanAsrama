<?php
session_start();
include('connect.php');  // Include your database connection

// Initialize error message
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to verify credentials
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);  // bind the username and password
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Set session if user is authenticated
        $_SESSION['username'] = $username;
        header("Location: index.php");  // Redirect to a new page
    } else {
        $error = "Nama pengguna atau kata laluan tidak sah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pengurusan Asrama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000; /* Set background to black */
            font-family: 'Nunito', sans-serif;
            background: radial-gradient(circle at top left,rgb(255, 255, 255), #000); /* Subtle gradient for more depth */
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(15px);
            padding: 0;
            display: flex;
            overflow: hidden;
            width: 900px;
            height: 600px;
        }

        .login-form-container {
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            max-width: 500px;
        }

        h1.welcome-heading {
            text-align: center;
            color: #fff;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            background: -webkit-linear-gradient(45deg, #ff416c, #ff4b2b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        h2 {
            text-align: center;
            color: #fff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            font-size: 16px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            background: linear-gradient(45deg, #ff416c, #ff4b2b);
            border: none;
            border-radius: 50px;
            color: #fff;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #ff4b2b, #ff416c);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(255, 75, 43, 0.5);
        }

        .alert {
            margin-bottom: 20px;
            font-size: 14px;
            color: red;
            text-align: center;
        }

        .signup-link {
            text-align: center;
            color: #fff;
            margin-top: 20px;
        }

        .signup-link a {
            color: #ff416c;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .signup-link a:hover {
            color: #ff4b2b;
        }

        .login-image {
            background-image: url('DSC_0035.jpg'); /* Replace with the correct image path */
            background-size: cover;
            background-position: center;
            width: 50%;
        }

        /* Hanya tampil pada laptop */
        @media (max-width: 1024px) {
            body {
                display: none;
            }
        }

        @media (min-width: 1025px) {
            .container {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <div class="container">
       
        <div class="login-image"></div>

     
        <div class="login-form-container">
            
            <h1 class="welcome-heading">Selamat Datang ke Pengurusan Asrama</h1>

            <h2>Log Masuk</h2>

          
            <?php if (!empty($error)): ?>
                <div class="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">
                <input type="text" class="form-control" id="username" name="username" placeholder="Nama Pengguna" required>
                <input type="password" class="form-control" id="password" name="password" placeholder="Kata Laluan" required>
                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="signup-link">
                <p>Belum ada akaun? <a href="signup.php">Daftar Sekarang</a></p>
            </div>
        </div>
    </div>
</body>
</html>

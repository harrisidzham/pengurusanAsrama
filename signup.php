<?php
session_start();
include('connect.php');  // Include your database connection

// Initialize error message
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        $error = "Kata laluan dan pengesahan kata laluan tidak sepadan.";
    } else {
        // Check if the username already exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Nama pengguna sudah wujud.";
        } else {
            // Insert new user into the database
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql); // Prepare the statement
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $stmt->bind_param("ss", $username, $hashed_password); // Bind the parameters

            if ($stmt->execute()) {
                $success = "Pendaftaran berjaya. Anda kini boleh <a href='login.php'>log masuk</a>.";
            } else {
                $error = "Pendaftaran gagal. Sila cuba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sistem Pengurusan Asrama</title>
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
            background: #000; /* Pure black background */
            font-family: 'Nunito', sans-serif;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.08); /* Semi-transparent white */
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px); /* Adds depth */
        }

        .form-title {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #fff;
            margin-bottom: 30px;
        }

        .form-control {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: none;
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-control:focus {
            border-color: #ff4081; /* Pink accent */
            box-shadow: 0 0 10px rgba(255, 64, 129, 0.5);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            background: linear-gradient(45deg, #ff4081, #f50057); /* Pink gradient */
            border: none;
            border-radius: 50px;
            color: #fff;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #f50057, #ff4081);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .alert {
            font-size: 14px;
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .success {
            font-size: 14px;
            color: #28a745;
            text-align: center;
            margin-bottom: 10px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #ff4081;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            color: #fff;
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 25px;
            }
        }

        @media (max-width: 480px) {
            .form-title {
                font-size: 25px;
            }

            .btn-primary {
                padding: 12px;
                font-size: 16px;
            }
        }
        p {
            color: white;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="form-title">Daftar Akaun</h2>

        <!-- Show error message if registration fails -->
        <?php if (!empty($error)): ?>
            <div class="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        
        <?php if (!empty($success)): ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <input type="text" class="form-control" id="username" name="username" placeholder="Nama Pengguna" required>
            <input type="password" class="form-control" id="password" name="password" placeholder="Kata Laluan" required>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Sahkan Kata Laluan" required>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>

        <div class="login-link">
            <p>Sudah ada akaun? <a href="login.php">Log Masuk</a></p>
        </div>
    </div>
</body>

</html>

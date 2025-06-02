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
<html lang="ms">

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
            background: #f8f9fa; /* Cerah: hampir putih */
            font-family: 'Nunito', sans-serif;
        }

        .form-container {
            background: #ffffff; /* Putih penuh */
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .form-control {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #ced4da;
            background-color: #f9f9f9;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.25);
            background-color: #fff;
        }

        .btn-primary {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            background-color: #0d6efd;
            border: none;
            border-radius: 50px;
            color: #fff;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .alert {
            font-size: 14px;
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .success {
            font-size: 14px;
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            color: #0a58ca;
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
            color: #333;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="form-title">Daftar Akaun</h2>

        <!-- Papar mesej ralat atau kejayaan -->
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

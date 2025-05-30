<?php
session_start();
include('connect.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch user data
function fetchUserData($conn, $username) {
    $stmt = $conn->prepare("SELECT * FROM hostel_registration WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$user = fetchUserData($conn, $_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semak Pendaftaran Bilik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            transition: box-shadow 0.3s ease;
        }
        .profile-card:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        .card-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: #0056b3;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }
        .profile-info {
            margin-bottom: 20px;
            font-size: 1rem;
            color: #666;
            display: flex;
            align-items: center;
        }
        .profile-info strong {
            font-weight: 500;
            color: #333;
            margin-right: 10px;
        }
        .profile-info i {
            margin-right: 10px;
            color: #0056b3;
        }
        .back-button {
            margin: 20px auto;
            text-align: center;
        }
        .btn-primary {
            background-color: #0056b3;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #004299;
        }
        .profile-card a {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="profile-card card shadow-lg">
            <div class="card-body">
                <h3 class="card-title">Maklumat Pendaftaran Bilik</h3>
                <p class="profile-info"><i class="fas fa-user"></i><strong>Nama:</strong> <?php echo htmlspecialchars($user['name'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-door-closed"></i><strong>Nombor Bilik:</strong> <?php echo htmlspecialchars($user['room_number'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-book"></i><strong>Kursus:</strong> <?php echo htmlspecialchars($user['course'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-graduation-cap"></i><strong>Semester:</strong> <?php echo htmlspecialchars($user['semester'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-phone"></i><strong>Nombor Telefon:</strong> <?php echo htmlspecialchars($user['phone_number'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-user-friends"></i><strong>Nama Waris:</strong> <?php echo htmlspecialchars($user['guardian_name'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-home"></i><strong>Alamat Waris:</strong> <?php echo htmlspecialchars($user['guardian_address'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-phone-alt"></i><strong>Nombor Telefon Waris:</strong> <?php echo htmlspecialchars($user['guardian_phone'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-building"></i><strong>Asrama:</strong> <?php echo htmlspecialchars($user['asrama'] ?? ''); ?></p>
                <p class="profile-info"><i class="fas fa-level-up-alt"></i><strong>Aras:</strong> <?php echo htmlspecialchars($user['aras'] ?? ''); ?></p>
            </div>
        </div>

       
        <div class="back-button">
            <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>

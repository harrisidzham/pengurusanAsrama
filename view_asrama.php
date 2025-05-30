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

// Fetch current user data
$user = fetchUserData($conn, $_SESSION['username']);

if ($user) {
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data and sanitize inputs
        $name = trim($_POST['name']);
        $room_number = trim($_POST['room_number']);
        $course = trim($_POST['course']);
        $semester = trim($_POST['semester']);
        $phone_number = trim($_POST['phone_number']);
        $guardian_name = trim($_POST['guardian_name']);
        $guardian_address = trim($_POST['guardian_address']);
        $guardian_phone = trim($_POST['guardian_phone']);
        $asrama = trim($_POST['asrama']);
        $aras = trim($_POST['aras']);

        // Input validation
        $errors = [];
        if (empty($name) || empty($room_number) || empty($phone_number) || empty($asrama) || empty($aras)) {
            $errors[] = "Sila isi semua medan yang diperlukan.";
        }

        // Handle file upload
        $profile_picture = $user['profile_picture']; // Default to current picture
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] != UPLOAD_ERR_NO_FILE) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $mime_type = mime_content_type($_FILES['profile_picture']['tmp_name']);
            
            if (!in_array($mime_type, $allowed_types)) {
                $errors[] = "Sila muat naik fail gambar yang sah (JPG, PNG, atau GIF).";
            } else {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $file_name = uniqid() . '-' . basename($_FILES['profile_picture']['name']);
                $target_file = $upload_dir . $file_name;
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                    $profile_picture = $target_file;
                } else {
                    $errors[] = "Gagal memuat naik gambar.";
                }
            }
        }

        
        if (empty($errors)) {
            $update_sql = "UPDATE hostel_registration SET 
                name = ?, 
                room_number = ?, 
                course = ?, 
                semester = ?, 
                phone_number = ?, 
                guardian_name = ?, 
                guardian_address = ?, 
                guardian_phone = ?, 
                asrama = ?, 
                aras = ?";
            
            // Update profile picture if it was uploaded
            if ($profile_picture != $user['profile_picture']) {
                $update_sql .= ", profile_picture = ?";
            }

            $update_sql .= " WHERE email = ?";
            $stmt = $conn->prepare($update_sql);

            if ($profile_picture != $user['profile_picture']) {
                $stmt->bind_param("ssssssssssss", $name, $room_number, $course, $semester, $phone_number, $guardian_name, $guardian_address, $guardian_phone, $asrama, $aras, $profile_picture, $_SESSION['username']);
            } else {
                $stmt->bind_param("sssssssssss", $name, $room_number, $course, $semester, $phone_number, $guardian_name, $guardian_address, $guardian_phone, $asrama, $aras, $_SESSION['username']);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Maklumat berjaya dikemaskini!');</script>";
                // Fetch updated user data
                $user = fetchUserData($conn, $_SESSION['username']);
            } else {
                echo "<script>alert('Ralat semasa mengemaskini: " . $stmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('" . implode('\\n', $errors) . "');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maklumat Pendaftaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000; 
            color: #f8f9fa; 
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }

        .profile-card {
            background: linear-gradient(145deg, #1d1f21, #292b2c); Dark gradient for card
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }

        .card-title {
            font-weight: 600;
            color:white ; 
            margin-bottom: 20px;
        }

        .form-section {
            margin-top: 20px;
        }

        .img-thumbnail {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #ffcc00; 
            transition: transform 0.3s ease;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
        }

        .form-label {
            font-weight: 500;
            color:#F0F8FF; 
        }

        .btn-back {
            margin-top: 20px;
            text-align: center;
        }

        button.btn-primary {
            /* background-color: purple;  */
            border-color: black;
            border-radius: 25px;
            padding: 12px 20px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

       
        a.btn-secondary {
            /* background-color: #007bff;  */
            border-radius: 25px;
            padding: 12px 20px;
            font-weight: 500;
        }

        a.btn-secondary:hover {
            /* background-color: #0056b3;  */
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .img-thumbnail {
                width: 150px;
                height: 150px;
            }
        }
        
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="profile-card card shadow-lg">
            <div class="card-body">
                <h3 class="card-title text-center">Maklumat Pendaftaran Bilik</h3>
                <form method="POST" enctype="multipart/form-data" class="form-section">
                    <div class="mb-3 text-center">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="rounded-circle img-thumbnail">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/200" alt="Profile Picture" class="rounded-circle img-thumbnail">
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombor Bilik</label>
                                <input type="text" name="room_number" class="form-control" value="<?php echo htmlspecialchars($user['room_number']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kursus</label>
                                <input type="text" name="course" class="form-control" value="<?php echo htmlspecialchars($user['course']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Semester</label>
                                <input type="text" name="semester" class="form-control" value="<?php echo htmlspecialchars($user['semester']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombor Telefon</label>
                                <input type="text" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Waris</label>
                                <input type="text" name="guardian_name" class="form-control" value="<?php echo htmlspecialchars($user['guardian_name']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Waris</label>
                                <input type="text" name="guardian_address" class="form-control" value="<?php echo htmlspecialchars($user['guardian_address']); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nombor Telefon Waris</label>
                                <input type="text" name="guardian_phone" class="form-control" value="<?php echo htmlspecialchars($user['guardian_phone']); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asrama</label>
                        <input type="text" name="asrama" class="form-control" value="<?php echo htmlspecialchars($user['asrama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Aras</label>
                        <input type="text" name="aras" class="form-control" value="<?php echo htmlspecialchars($user['aras']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Profil (JPG, PNG, GIF)</label>
                        <input type="file" name="profile_picture" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kemaskini Maklumat</button>
                </form>
                <div class="btn-back">
                    <a href="index.php" class="btn btn-secondary w-100 mt-3">Kembali ke Laman Utama</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    echo "Maklumat pengguna tidak dijumpai.";
}
?>

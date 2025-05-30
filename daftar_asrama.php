<?php
session_start();
include('connect.php');


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $name = $_POST['name'];
    $room_number = $_POST['room_number'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    // Insert the data into the database
    $sql = "INSERT INTO hostel_registration (name, room_number, phone_number, email) 
            VALUES ('$name', '$room_number', '$phone_number', '$email')";
    
    if (mysqli_query($conn, $sql)) {
        // Save the email into the session for the next page to retrieve user data
        $_SESSION['username'] = $email;
        echo "<script>alert('Pendaftaran berjaya! Sila pilih nombor bilik.'); window.location.href='select_bilik.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Asrama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        /* Body Styling */
        body {
            background-color: #121212;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container for the form */
        .container {
            background: rgba(0, 0, 0, 0.8); /* Semi-transparent black */
            color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 500px; /* Medium size */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(12px);
        }

        h1 {
            font-size: 1.8em;
            color: #ff5a81;
            text-align: center;
            font-weight: 700;
        }

        p {
            color: #d3cfe7;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 15px;
            font-weight: 600;
            color: #e0d4f7;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1); /* Lighter black for inputs */
            color: #ffffff;
            border: 1px solid #5c4a85;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff4081;
            box-shadow: 0 0 10px rgba(255, 64, 129, 0.5);
            background-color: rgba(255, 255, 255, 0.15); /* Slightly lighter on focus */
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff4081, #ff80ab);
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            color: white;
            margin-top: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(255, 64, 129, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff80ab, #ff4081);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 64, 129, 0.5);
        }

        .form-text {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 15px;
        }

        /* Animations */
        .container, .btn-primary {
            animation: fadeIn 0.7s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .container {
                padding: 20px;
                width: 100%;
            }

            h1 {
                font-size: 24px;
            }

            .btn-primary {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Daftar Asrama</h1>
        <p>Sila isi maklumat di bawah untuk mendaftar asrama:</p>

        <form action="daftar_asrama.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Penuh</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="room_number" class="form-label">No Ndp Number</label>
                <input type="text" class="form-control" id="room_number" name="room_number" required>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Nombor Telefon</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary">Daftar</button>
            <p class="form-text">Pastikan maklumat anda tepat sebelum mendaftar.</p>
        </form>
    </div>
</body>
</html>

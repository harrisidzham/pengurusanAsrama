<?php
session_start();
include('connect.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengurusan Asrama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Roboto:wght@400;500&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #D3D3D3;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

       
        .main-title {
            font-size: 40px;
            font-weight: 700;
            color: #483D8B;
            margin-bottom: 20px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .container {
            background-color: #483D8B;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 90%;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.5);
        }

      
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding-bottom: 15px;
            margin-bottom: 30px;
            border-bottom: 2px solid #FF6347;
        }

        .welcome {
            font-size: 24px;
            color: #FF6347;
            font-weight: 600;
        }

        .logout-btn {
            background-color: #dc3545;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 50px;
            color: white;
            border: none;
            text-decoration: none;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background-color: #b22222;
            box-shadow: 0 6px 12px rgba(255, 99, 71, 0.2);
            transform: translateY(-2px);
        }

        .long-sentence {
            font-size: 16px;
            color: white;
            text-align: justify;
            margin-bottom: 20px;
        }

        .management-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .btn-primary, .btn-secondary {
            width: 80%;
            max-width: 400px;
            padding: 15px;
            font-size: 18px;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #8B008B;
            border: none;
        }

        .btn-primary:hover {
            background-color: #7B68EE;
            box-shadow: 0 8px 15px rgba(255, 99, 71, 0.3);
            transform: translateY(-3px);
        }

        .btn-secondary {
            background-color: #8B008B;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #7B68EE;
            box-shadow: 0 8px 15px rgba(40, 167, 69, 0.3);
            transform: translateY(-3px);
        }

        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #fff;
        }

        /* Logo Styling */
        .ilp-logo {
            position: absolute;
            top: -140px;
            left: -220px;
            width: 290px;
            height: auto;
        }

        .jtm-logo {
            position: absolute;
            top: -110px;
            right: -150px;
            width: 150px;
            height: auto;
        }

        
        @media (max-width: 768px) {
            .main-title {
                font-size: 28px;
            }

            .welcome {
                font-size: 18px;
            }

            .btn-primary, .btn-secondary {
                font-size: 16px;
                padding: 12px 30px;
            }

            .ilp-logo, .jtm-logo {
                width: 80px; /* Smaller logo size for mobile */
            }
        }
    </style>
</head>
<body>
    <!-- Main Title -->
    <div class="main-title">Sistem Pengurusan Asrama</div>

    <div class="container">
        <!-- ILP and JTM Logos -->
        <img src="download-removebg-preview.png" alt="ILP Logo" class="ilp-logo">
        <img src="logo-removebg-preview ak.png" alt="JTM Logo" class="jtm-logo">

        <!-- Welcome and Logout Section -->
        <div class="header-section">
            <span class="welcome">Selamat datang, <?php echo $_SESSION["username"]; ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <p class="long-sentence">
            Ini adalah Sistem Pengurusan Asrama. Anda boleh melihat atau menguruskan maklumat sistem penyelia asrama di sini. 
            Sila pastikan semua maklumat diisi dengan betul untuk memastikan kelancaran sistem.
        </p>

        <!-- Management Buttons -->
        <div class="management-buttons">
            <a href="daftar_asrama.php" class="btn btn-primary">Daftar Asrama</a>
            <a href="semak_pendaftaran_bilik.php" class="btn btn-secondary">Semak Pendaftaran Bilik</a>
            <a href="lapor_kerosakan.php" class="btn btn-primary">Laporan Kerosakan Bilik</a>
        </div>

        <!-- Footer -->
        <footer>
            &copy; 2024 Sistem Pengurusan Asrama. Semua Hak Cipta Terpelihara.<br>
            Institut Latihan Perindustrian Kuala Langat
        </footer>
    </div>
</body>
</html>


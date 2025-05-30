<?php
session_start();
include('connect.php');

$message = "";


if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = $_POST['room_number'];
    $damage_type = $_POST['damage_type'];
    $damage_description = $_POST['damage_description'];
    $reported_by = $_SESSION['username'];

    // Prepare and execute the insert statement
    $stmt = $conn->prepare("INSERT INTO damage_reports (room_number, damage_type, damage_description, reported_by) VALUES (?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ssss", $room_number, $damage_type, $damage_description, $reported_by);
        
        if ($stmt->execute()) {
            $message = "Laporan kerosakan berjaya dihantar.";
            $_POST['room_number'] = "";
            $_POST['damage_description'] = "";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerosakan Bilik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <style>
        /* Container Styles */
        body {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #ffffff;
        }
        .container {
            background-color: #333;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }
        
        /* Header Styles */
        h2 {
            font-size: 2rem;
            color: #f39c12;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        /* Form Control Styles */
        .form-control, .form-select {
            background-color: #444;
            color: #fff;
            border: 1px solid #666;
            padding: 0.8rem;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #555;
            outline: none;
            box-shadow: 0 0 5px #f39c12;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #f39c12;
            border: none;
            padding: 0.8rem 1.5rem;
            font-weight: bold;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #e67e22;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Message Alert Styles */
        .alert {
            font-size: 1rem;
            text-align: center;
            color: #333;
            background-color: #f1c40f;
            border-radius: 10px;
            padding: 0.8rem;
            margin-top: 1rem;
        }
        
        /* Label Styles */
        .form-label {
            color: #ecf0f1;
            font-weight: 600;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Laporan Kerosakan Bilik</h2>
        
        <?php if (!empty($message)): ?> 
            <p class="alert"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="room_number" class="form-label">Nombor Bilik</label>
                <input type="text" class="form-control" id="room_number" name="room_number" value="<?php echo htmlspecialchars($_POST['room_number'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="mb-3">
                <label for="damage_type" class="form-label">Jenis Kerosakan</label>
                <select class="form-select" id="damage_type" name="damage_type" required>
                    <option value="">Sila Pilih</option>
                    <option value="Kipas">Kipas</option>
                    <option value="Lampu Suis">Lampu Suis</option>
                    <option value="Lampu">Lampu</option>
                    <option value="Pintu">Pintu</option>
                    <option value="Pintu Tombak">Pintu Tombak</option>
                    <option value="Meja">Meja</option>
                    <option value="Kerusi">Kerusi</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="damage_description" class="form-label">Keterangan Kerosakan</label>
                <textarea class="form-control" id="damage_description" name="damage_description" rows="4" required><?php echo htmlspecialchars($_POST['damage_description'] ?? '', ENT_QUOTES); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Hantar Laporan</button>
        </form>
    </div>
</body>
</html>

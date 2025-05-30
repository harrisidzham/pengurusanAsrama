<?php
session_start();
include('connect.php');

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Fetch the user details from the session
$email = $_SESSION['username'];

// Fetch rooms that are already taken
$taken_rooms = [];
$room_query = "SELECT room_number FROM hostel_registration WHERE room_number IS NOT NULL";
$result = mysqli_query($conn, $room_query);

while ($row = mysqli_fetch_assoc($result)) {
    $taken_rooms[] = $row['room_number'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_room'])) {
    // Capture selected asrama, floor (aras), and room number
    $selected_asrama = $_POST['asrama'];
    $selected_aras = $_POST['aras'];
    $selected_room = $_POST['room_number'];

    // Check if the selected room is already taken
    if (in_array($selected_room, $taken_rooms)) {
        echo "<script>alert('Bilik ini sudah dipilih, sila pilih bilik lain.');</script>";
    } else {
        // Update the database with the selected room number, asrama, and aras
        $sql = "UPDATE hostel_registration 
                SET room_number = '$selected_room', asrama = '$selected_asrama', aras = '$selected_aras' 
                WHERE email = '$email'";

        if (mysqli_query($conn, $sql)) {
            // Redirect to the asrama view page after successful room selection
            echo "<script>alert('Bilik berjaya dipilih!'); window.location.href='view_asrama.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Logic for displaying the room statuses (triggered by a button)
$show_rooms = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_status'])) {
    $show_rooms = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <title>Pilih Nombor Bilik</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1c1c1c; /* Black background */
            color: #ffffff; /* White text color */
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            background-color: #2c2c2c; /* Darker card background */
        }
        .card:hover {
            transform: translateY(-5px);
        }
        h1, h2 {
            font-weight: 600;
            color: #ffd700; /* Gold color for headers */
        }
        .form-label {
            font-weight: 500;
            color: #ffffff; /* White color for labels */
        }
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        .badge-taken {
            background-color: #dc3545; /* Red for taken rooms */
        }
        .badge-available {
            background-color: #28a745; /* Green for available rooms */
        }
        .badge {
            font-size: 1rem;
            padding: 0.5rem;
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card p-4">
                    <h1 class="text-center mb-4">Pilih Nombor Bilik</h1>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="asrama" class="form-label">Pilih Asrama</label>
                            <select id="asrama" name="asrama" class="form-select" required>
                                <option value="">Pilih Asrama</option>
                                <option value="Asrama 1">Asrama 1</option>
                                <option value="Asrama 2">Asrama 2</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="aras" class="form-label">Pilih Aras</label>
                            <select id="aras" name="aras" class="form-select" required>
                                <option value="">Pilih Aras</option>
                                <option value="Aras 1">Aras 1</option>
                                <option value="Aras 2">Aras 2</option>
                                <option value="Aras 3">Aras 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="room_number" class="form-label">Nombor Bilik</label>
                            <select id="room_number" name="room_number" class="form-select" required>
                                <option value="">Pilih Bilik</option>
                                <?php for ($i = 1; $i <= 100; $i++): ?>
                                    <?php 
                                        $room = str_pad($i, 3, '0', STR_PAD_LEFT); 
                                        $disabled = in_array($room, $taken_rooms) ? 'disabled' : '';
                                    ?>
                                    <option value="<?php echo $room; ?>" <?php echo $disabled; ?>>
                                        <?php echo $room; ?> <?php echo $disabled ? '(Telah Diambil)' : ''; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" name="submit_room" class="btn btn-primary">Pilih Bilik</button>
                    </form>

                    <!-- Button to show room status -->
                    <form method="POST" action="" class="mt-3">
                        <button type="submit" name="show_status" class="btn btn-secondary">Lihat Status Bilik</button>
                    </form>
                </div>

                
                <?php if ($show_rooms): ?>
                    <div class="card p-4 mt-4">
                        <h2>Status Bilik</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombor Bilik</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 1; $i <= 100; $i++): ?>
                                    <?php 
                                        $room = str_pad($i, 3, '0', STR_PAD_LEFT);
                                        $status_class = in_array($room, $taken_rooms) ? 'badge-taken' : 'badge-available';
                                        $status_text = in_array($room, $taken_rooms) ? 'Diambil' : 'Tersedia';
                                    ?>
                                    <tr>
                                        <td><?php echo $room; ?></td>
                                        <td><span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
session_start();

require_once 'db.php';

// Redirect ke login.php jika belum login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Rental Motor</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMj0e5d8M5G5Lx5nq2h4m3p4T4D2s6f9f9j8g" crossorigin="anonymous">
    <style>
        .btn-custom {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .button-container {
            display: flex;
            justify-content: center; /* Mengatur tombol ke kiri */
            gap: 10px; /* Jarak antar tombol */
            margin-bottom: 20px; /* Jarak bawah */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Dashboard Rental Motor Hickham</h2>
        <div class="button-container">
            <a class="btn btn-primary btn-custom" href="kendaraan.php"><i class="fas fa-motorcycle"></i> Kelola Kendaraan</a>
            <a class="btn btn-primary btn-custom" href="pemilik.php"><i class="fas fa-user"></i> Kelola Pemilik</a>
            <a class="btn btn-primary btn-custom" href="sewa.php"><i class="fas fa-file-alt"></i> Kelola Sewa</a>
            <a class="btn btn-danger btn-custom" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        
        <h3>Tabel Kendaraan</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kendaraan</th>
                        <th>Jenis</th>
                        <th>Plat Nomor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM kendaraan";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['nama_kendaraan'] . "</td>";
                            echo "<td>" . $row['jenis'] . "</td>";
                            echo "<td>" . $row['plat_nomor'] . "</td>";
                            echo "<td>
                                    <a class='btn btn-warning btn-sm btn-custom' href='kendaraan.php?id=" . $row['id'] . "'><i class='fas fa-edit'></i> Edit</a>
                                    <a class='btn btn-danger btn-sm btn-custom' href='kendaraan.php?id=" . $row['id'] . "&action=delete'><i class='fas fa-trash'></i> Hapus</a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Tidak ada data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

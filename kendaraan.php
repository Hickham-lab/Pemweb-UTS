<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Proses tambah, edit, dan hapus kendaraan
if (isset($_POST['submit'])) {
    $nama_kendaraan = $_POST['nama_kendaraan'];
    $jenis = $_POST['jenis'];
    $plat_nomor = $_POST['plat_nomor'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $sql = "UPDATE kendaraan SET nama_kendaraan='$nama_kendaraan', jenis='$jenis', plat_nomor='$plat_nomor' WHERE id='$id'";
    } else {
        // Create
        $sql = "INSERT INTO kendaraan (nama_kendaraan, jenis, plat_nomor) VALUES ('$nama_kendaraan', '$jenis', '$plat_nomor')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: kendaraan.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Hapus kendaraan
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM kendaraan WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: kendaraan.php");
    exit();
}

// Ambil data kendaraan untuk edit
$kendaraan = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM kendaraan WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $kendaraan = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kendaraan</title>
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
            justify-content: flex-start; /* Mengatur tombol ke kiri */
            gap: 10px; /* Jarak antar tombol */
            margin-bottom: 20px; /* Jarak bawah */
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Kelola Kendaraan</h2>
        <!-- Tombol Kembali ke Dashboard -->
        <div class="button-container">
            <a href="dashboard.php" class="btn btn-secondary btn-custom">Kembali ke Dashboard</a>
        </div>
        <form action="kendaraan.php" method="post">
            <input type="hidden" name="id" value="<?php echo $kendaraan['id'] ?? ''; ?>">
            <div class="form-group mb-3">
                <label for="nama_kendaraan">Nama Kendaraan:</label>
                <input type="text" id="nama_kendaraan" name="nama_kendaraan" value="<?php echo $kendaraan['nama_kendaraan'] ?? ''; ?>" required class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="jenis">Jenis:</label>
                <input type="text" id="jenis" name="jenis" value="<?php echo $kendaraan['jenis'] ?? ''; ?>" required class="form-control">
            </div>
            <div class="form-group mb-3">
                <label for="plat_nomor">Plat Nomor:</label>
                <input type="text" id="plat_nomor" name="plat_nomor" value="<?php echo $kendaraan['plat_nomor'] ?? ''; ?>" required class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-custom">Simpan</button>
        </form>

        <h3>Daftar Kendaraan</h3>
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
</body>
</html>
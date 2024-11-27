<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Proses tambah, edit, dan hapus penyewaan
if (isset($_POST['submit'])) {
    $kendaraan_id = $_POST['kendaraan_id'];
    $pemilik_id = $_POST['pemilik_id'];
    $tanggal_sewa = $_POST['tanggal_sewa'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $pembayaran = (int)$_POST['pembayaran'];  // Pastikan pembayaran adalah integer

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $sql = "UPDATE penyewaan SET kendaraan_id='$kendaraan_id', pemilik_id='$pemilik_id', tanggal_sewa='$tanggal_sewa', tanggal_kembali='$tanggal_kembali', pembayaran='$pembayaran' WHERE id='$id'";
    } else {
        // Create
        $sql = "INSERT INTO penyewaan (kendaraan_id, pemilik_id, tanggal_sewa, tanggal_kembali, pembayaran) VALUES ('$kendaraan_id', '$pemilik_id', '$tanggal_sewa', '$tanggal_kembali', '$pembayaran')";
    }    
    mysqli_query($conn, $sql);
    header("Location: sewa.php");
    exit();
}

// Hapus penyewaan
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM penyewaan WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: sewa.php");
    exit();
}

// Ambil data penyewaan untuk edit
$sewa = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM penyewaan WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $sewa = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Penyewaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kelola Penyewaan</h2>
        
        <!-- Tombol Kembali ke Dashboard -->
        <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

        <!-- Form untuk tambah atau edit penyewaan -->
        <form action="sewa.php" method="post">
            <input type="hidden" name="id" value="<?php echo $sewa['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="kendaraan_id" class="form-label">Kendaraan:</label>
                <select id="kendaraan_id" name="kendaraan_id" required class="form-select">
                    <?php
                    $sql = "SELECT * FROM kendaraan";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'" . (isset($sewa) && $sewa['kendaraan_id'] == $row['id'] ? ' selected' : '') . ">" . $row['nama_kendaraan'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="pemilik_id" class="form-label">Pemilik:</label>
                <select id="pemilik_id" name="pemilik_id" required class="form-select">
                    <?php
                    $sql = "SELECT * FROM pemilik";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'" . (isset($sewa) && $sewa['pemilik_id'] == $row['id'] ? ' selected' : '') . ">" . $row['nama_pemilik'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="tanggal_sewa" class="form-label">Tanggal Sewa:</label>
                <input type="date" id="tanggal_sewa" name="tanggal_sewa" value="<?php echo $sewa['tanggal_sewa'] ?? ''; ?>" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="tanggal_kembali" class="form-label">Tanggal Kembali:</label>
                <input type="date" id="tanggal_kembali" name="tanggal_kembali" value="<?php echo $sewa['tanggal_kembali'] ?? ''; ?>" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="pembayaran" class="form-label">Pembayaran:</label>
                <input type="number" id="pembayaran" name="pembayaran" value="<?php echo $sewa['pembayaran'] ?? ''; ?>" required min="0" step="1" class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
        </form>

        <h3 class="mt-4">Daftar Penyewaan</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kendaraan</th>
                    <th>Pemilik</th>
                    <th>Tanggal Sewa</th>
                    <th>Tanggal Kembali</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT penyewaan.*, kendaraan.nama_kendaraan, pemilik.nama_pemilik FROM penyewaan 
                        JOIN kendaraan ON penyewaan.kendaraan_id = kendaraan.id 
                        JOIN pemilik ON penyewaan.pemilik_id = pemilik.id";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nama_kendaraan'] . "</td>";
                        echo "<td>" . $row['nama_pemilik'] . "</td>";
                        echo "<td>" . $row['tanggal_sewa'] . "</td>";
                        echo "<td>" . $row['tanggal_kembali'] . "</td>";
                        echo "<td class='pembayaran'>" . number_format($row['pembayaran']) . "</td>";  // Menampilkan pembayaran
                        echo "<td><a href='sewa.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a> | <a href='sewa.php?id=" . $row['id'] . "&action=delete' class='btn btn-danger btn-sm'>Hapus</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Proses tambah, edit, dan hapus pemilik
if (isset($_POST['submit'])) {
    $nama_pemilik = $_POST['nama_pemilik'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $sql = "UPDATE pemilik SET nama_pemilik='$nama_pemilik', alamat='$alamat', no_telepon='$no_telepon' WHERE id='$id'";
    } else {
        // Create
        $sql = "INSERT INTO pemilik (nama_pemilik, alamat, no_telepon) VALUES ('$nama_pemilik', '$alamat', '$no_telepon')";
    }
    
    mysqli_query($conn, $sql);
    header("Location: pemilik.php");
    exit();
}

// Hapus pemilik
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM pemilik WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: pemilik.php");
    exit();
}

// Ambil data pemilik untuk edit
$pemilik = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pemilik WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $pemilik = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemilik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kelola Pemilik</h2>
        
        <!-- Tombol Kembali ke Dashboard -->
        <a href="dashboard.php" class="btn btn-secondary mb-3">Kembali ke Dashboard</a>

        <!-- Form untuk menambah atau edit pemilik -->
        <form action="pemilik.php" method="post">
            <input type="hidden" name="id" value="<?php echo $pemilik['id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="nama_pemilik" class="form-label">Nama Pemilik:</label>
                <input type="text" id="nama_pemilik" name="nama_pemilik" value="<?php echo $pemilik['nama_pemilik'] ?? ''; ?>" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo $pemilik['alamat'] ?? ''; ?>" required class="form-control">
            </div>
            <div class="mb-3">
                <label for="no_telepon" class="form-label">No Telepon:</label>
                <input type="text" id="no_telepon" name="no_telepon" value="<?php echo $pemilik['no_telepon'] ?? ''; ?>" required class="form-control">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
        </form>

        <h3 class="mt-4">Daftar Pemilik</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pemilik</th>
                    <th>Alamat</th>
                    <th>No Telepon</th>
                    <th>Aksi</th>
                </tr>
            </ ```php
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM pemilik";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nama_pemilik'] . "</td>";
                        echo "<td>" . $row['alamat'] . "</td>";
                        echo "<td>" . $row['no_telepon'] . "</td>";
                        echo "<td><a href='pemilik.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a> | <a href='pemilik.php?id=" . $row['id'] . "&action=delete' class='btn btn-danger btn-sm'>Hapus</a></td>";
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
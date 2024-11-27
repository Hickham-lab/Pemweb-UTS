<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Proses tambah, edit, dan hapus pengguna
if (isset($_POST['submit'])) {
    $nama_pengguna = $_POST['nama_pengguna'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash password
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $id = $_POST['id'];
        $sql = "UPDATE pengguna SET nama_pengguna='$nama_pengguna', email='$email', nomor_telepon='$nomor_telepon', alamat='$alamat', password='$password', tanggal_lahir='$tanggal_lahir', jenis_kelamin='$jenis_kelamin' WHERE id='$id'";
    } else {
        // Create
        $sql = "INSERT INTO pengguna (nama_pengguna, email, nomor_telepon, alamat, password, tanggal_lahir, jenis_kelamin) 
                VALUES ('$nama_pengguna', '$email', '$nomor_telepon', '$alamat', '$password', '$tanggal_lahir', '$jenis_kelamin')";
    }    
    mysqli_query($conn, $sql);
    header("Location: pengguna.php");
    exit();
}

// Hapus pengguna
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM pengguna WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: pengguna.php");
    exit();
}

// Ambil data pengguna untuk edit
$pengguna = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM pengguna WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $pengguna = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Kelola Pengguna</h2>
        
        <!-- Tombol Kembali ke Dashboard -->
        <a href="dashboard.php" class="back-to-dashboard-button">Kembali ke Dashboard</a>

        <!-- Form untuk tambah atau edit pengguna -->
        <form action="pengguna.php" method="post">
            <input type="hidden" name="id" value="<?php echo $pengguna['id'] ?? ''; ?>">
            <div class="form-group">
                <label for="nama_pengguna">Nama Pengguna:</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna" value="<?php echo $pengguna['nama_pengguna'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $pengguna['email'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="nomor_telepon">Nomor Telepon:</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" value="<?php echo $pengguna['nomor_telepon'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" required><?php echo $pengguna['alamat'] ?? ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir:</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $pengguna['tanggal_lahir'] ?? ''; ?>" required>
        </div>
            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="Laki-laki" <?php echo (isset($pengguna) && $pengguna['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo (isset($pengguna) && $pengguna['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                    <option value="Lainnya" <?php echo (isset($pengguna) && $pengguna['jenis_kelamin'] == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                </select>
            </div>
            <button type="submit" name="submit">Simpan</button>
        </form>

        <h3>Daftar Pengguna</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM pengguna";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nama_pengguna'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['nomor_telepon'] . "</td>";
                        echo "<td>" . $row['alamat'] . "</td>";
                        echo "<td>" . $row['tanggal_lahir'] . "</td>";
                        echo "<td>" . $row['jenis_kelamin'] . "</td>";
                        echo "<td><a href='pengguna.php?id=" . $row['id'] . "'>Edit</a> | <a href='pengguna.php?id=" . $row['id'] . "&action=delete'>Hapus</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

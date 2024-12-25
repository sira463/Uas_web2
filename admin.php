<?php
session_start();
include 'db.php';

// Cek apakah pengguna login sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

/// Proses tambah menu
if (isset($_POST['submit'])) {
    $nama_menu = $_POST['nama_menu'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $url_gambar = $_POST['url_gambar'];
    $kategori = $_POST['kategori'];
    $lama_penyajian = $_POST['lama_penyajian'];
    $bahan_menu = $_POST['bahan_menu'];
    $petunjuk_penyajian = $_POST['petunjuk_penyajian'];

    $sql = "INSERT INTO menu (nama_menu, tanggal, harga, deskripsi, url_gambar, kategori, lama_penyajian, bahan_menu, petunjuk_penyajian) 
            VALUES ('$nama_menu', '$tanggal', '$harga', '$deskripsi', '$url_gambar', '$kategori', '$lama_penyajian', '$bahan_menu', '$petunjuk_penyajian')";
    if ($conn->query($sql) === TRUE) {
        $message = "Menu berhasil ditambahkan!";
    } else {
        $message = "Gagal menambahkan menu: " . $conn->error;
    }
}

// Proses hapus menu
if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    $sql = "DELETE FROM menu WHERE id = $hapus_id";
    if ($conn->query($sql) === TRUE) {
        $message = "Menu berhasil dihapus!";
    } else {
        $message = "Gagal menghapus menu: " . $conn->error;
    }
}

// Proses edit menu
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM menu WHERE id = $edit_id");
    $menu = $result->fetch_assoc();
}

/// Proses update menu
if (isset($_POST['update'])) {
    $id = $_POST['id']; 
    $nama_menu = $_POST['nama_menu'];
    $tanggal = $_POST['tanggal'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $url_gambar = $_POST['url_gambar'];
    $kategori = $_POST['kategori'];
    $lama_penyajian = $_POST['lama_penyajian'];
    $bahan_menu = $_POST['bahan_menu'];
    $petunjuk_penyajian = $_POST['petunjuk_penyajian'];

    $sql = "UPDATE menu SET nama_menu = '$nama_menu', tanggal = '$tanggal', harga = '$harga', deskripsi = '$deskripsi', url_gambar = '$url_gambar', 
            kategori = '$kategori', lama_penyajian = '$lama_penyajian', bahan_menu = '$bahan_menu', petunjuk_penyajian = '$petunjuk_penyajian' 
            WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $message = "Menu berhasil diperbarui!";
    } else {
        $message = "Gagal memperbarui menu: " . $conn->error;
    }
}

// Proses download CSV
if (isset($_GET['download']) && $_GET['download'] == 'true') {
    // Set header untuk mengunduh file CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="menu_data.csv"');

    // Membuat file CSV
    $output = fopen('php://output', 'w');
    
    // Menulis header CSV
    fputcsv($output, ['ID', 'Nama Menu', 'Tanggal', 'Harga', 'Deskripsi', 'URL Gambar']);

    // Ambil data dari database
    $sql = "SELECT * FROM menu";
    $result = $conn->query($sql);
    
    // Menulis data menu ke CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Menutup file output
    fclose($output);
    exit; // Menghentikan eksekusi setelah file CSV dikirim
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e1d2;
            margin: 0;
            padding: 0;
        }

        h2, h3 {
            color: #6F4E37;
            text-align: center;
        }

        a {
            color: #6F4E37;
            text-decoration: none;
        }

        a:hover {
            color: #4A3A29;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 1.2em;
            color: #6F4E37;
        }

        input, textarea {
            padding: 10px;
            border: 1px solid #6F4E37;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
        }

        button {
            background-color: #6F4E37;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2em;
        }

        button:hover {
            background-color: #4A3A29;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #6F4E37;
            color: white;
        }

        table td img {
            width: 100px;
        }

        .actions a {
            margin-right: 10px;
            color: #6F4E37;
        }

        .actions a:hover {
            color: #4A3A29;
        }
    </style>
</head>
<body>
    <h2>Halaman Admin</h2>
    <a href="logout.php" style="float: right;">Logout</a>

    <h3>Tambah Menu</h3>
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    <form method="POST" action="">
        <label for="nama_menu">Nama Menu:</label>
        <input type="text" id="nama_menu" name="nama_menu" value="<?= isset($menu) ? $menu['nama_menu'] : '' ?>" required><br>

        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" value="<?= isset($menu) ? $menu['tanggal'] : '' ?>" required><br>

        <label for="harga">Harga:</label>
        <input type="number" id="harga" name="harga" value="<?= isset($menu) ? $menu['harga'] : '' ?>" required><br>

        <label for="deskripsi">Deskripsi:</label>
        <textarea id="deskripsi" name="deskripsi" required><?= isset($menu) ? $menu['deskripsi'] : '' ?></textarea><br>

        <label for="url_gambar">URL Gambar:</label>
        <input type="text" id="url_gambar" name="url_gambar" value="<?= isset($menu) ? $menu['url_gambar'] : '' ?>" required><br>

        <label for="kategori">Kategori:</label>
        <input type="text" id="kategori" name="kategori" value="<?= isset($menu) ? $menu['kategori'] : '' ?>" required><br>

        <label for="lama_penyajian">Lama Penyajian (Menit):</label>
        <input type="number" id="lama_penyajian" name="lama_penyajian" value="<?= isset($menu) ? $menu['lama_penyajian'] : '' ?>" required><br>

        <label for="bahan_menu">Bahan Menu:</label>
        <textarea id="bahan_menu" name="bahan_menu" required><?= isset($menu) ? $menu['bahan_menu'] : '' ?></textarea><br>

        <label for="petunjuk_penyajian">Petunjuk Penyajian:</label>
        <textarea id="petunjuk_penyajian" name="petunjuk_penyajian" required><?= isset($menu) ? $menu['petunjuk_penyajian'] : '' ?></textarea><br>

        <?php if (isset($menu)) { ?>
            <!-- Jangan lupa menambahkan input ID untuk update -->
            <input type="hidden" name="id" value="<?= $menu['id'] ?>">
            <button type="submit" name="update">Update</button>
        <?php } else { ?>
            <button type="submit" name="submit">Tambah</button>
        <?php } ?>
    </form>

    <h3>Daftar Menu</h3>
    
    <!-- Tombol untuk download CSV -->
    <a href="admin.php?download=true">
        <button>Download CSV</button>
    </a>

    <table border="1">
    <tr>
    <th>Nama Menu</th>
    <th>Tanggal</th>
    <th>Harga</th>
    <th>Deskripsi</th>
    <th>Kategori</th>
    <th>Lama Penyajian</th>
    <th>Bahan Menu</th>
    <th>Petunjuk Penyajian</th>
    <th>Gambar</th>
    <th>Aksi</th>
    </tr>
    <?php
    $sql = "SELECT * FROM menu";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['nama_menu']}</td>
                <td>{$row['tanggal']}</td>
                <td>{$row['harga']}</td>
                <td>{$row['deskripsi']}</td>
                <td>{$row['kategori']}</td>
                <td>{$row['lama_penyajian']}</td>
                <td>{$row['bahan_menu']}</td>
                <td>{$row['petunjuk_penyajian']}</td>
                <td><img src='{$row['url_gambar']}' width='100'></td>
                <td>
                    <a href='admin.php?edit_id={$row['id']}'>Edit</a>
                    <a href='admin.php?hapus_id={$row['id']}'>Hapus</a>
                </td>
            </tr>";
    }
    ?>
    </table>
</body>
</html>

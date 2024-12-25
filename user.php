<?php
session_start();
include 'db.php';

// Cek apakah pengguna login sebagai user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}

// Proses pengiriman formulir
$pesanan = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan'])) {
    $pesanan = [];
    foreach ($_POST['pesan'] as $id_menu) {
        $jumlah = isset($_POST['jumlah'][$id_menu]) ? intval($_POST['jumlah'][$id_menu]) : 1;
        $sql = "SELECT * FROM menu WHERE id = $id_menu";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pesanan[] = [
                'nama_menu' => $row['nama_menu'],
                'harga' => $row['harga'],
                'jumlah' => $jumlah,
                'total' => $row['harga'] * $jumlah,
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <style>
        /* Styling untuk halaman user */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4e1d2;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #6F4E37;
            margin: 20px 0;
        }

        h3 {
            color: #6F4E37;
            margin-left: 20px;
        }

        /* Tabel */
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #6F4E37;
            color: #fff;
        }

        table tr:hover {
            background-color: #f8d9c7;
        }

        table img {
            border-radius: 8px;
            display: block;
            margin: 0 auto;
        }

        /* Tombol logout */
        a[href="logout.php"] {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #6F4E37;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px;
            float: right;
        }

        a[href="logout.php"]:hover {
            background-color: #5a3e30;
        }

        /* Tombol selesai */
        button.selesai {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6F4E37;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin: 20px auto;
            display: block;
        }

        button.selesai:hover {
            background-color: #5a3e30;
        }

        /* Tabel hasil pesanan */
        .hasil-pesanan {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <h2>Halaman User</h2>
    <a href="logout.php" style="float: right;">Logout</a>
    <h3>Daftar Menu</h3>
    <form method="POST" action="">
        <table border="1">
            <tr>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Jumlah</th>
                <th>Pilih</th>
            </tr>
            <?php
            $sql = "SELECT * FROM menu";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['nama_menu']}</td>
                        <td>{$row['harga']}</td>
                        <td>{$row['deskripsi']}</td>
                        <td><img src='{$row['url_gambar']}' width='100'></td>
                        <td><input type='number' name='jumlah[{$row['id']}]' value='1' min='1' /></td>
                        <td><input type='checkbox' name='pesan[{$row['id']}]' value='{$row['id']}' /></td>
                      </tr>";
            }
            ?>
        </table>
        <button type="submit" class="selesai">Selesai</button>
    </form>

    <?php if (!empty($pesanan)): ?>
        <h3>Hasil Pesanan Anda</h3>
        <table class="hasil-pesanan">
            <tr>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
            <?php
            $grand_total = 0;
            foreach ($pesanan as $item) {
                echo "<tr>
                        <td>{$item['nama_menu']}</td>
                        <td>{$item['harga']}</td>
                        <td>{$item['jumlah']}</td>
                        <td>{$item['total']}</td>
                      </tr>";
                $grand_total += $item['total'];
            }
            ?>
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td><strong><?php echo $grand_total; ?></strong></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html>

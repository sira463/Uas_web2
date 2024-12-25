<?php
session_start();
include 'db.php';

// Cek apakah pengguna login sebagai user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}

// Ambil ID menu dari URL
$id = $_GET['id'];
$sql = "SELECT * FROM menu WHERE id = $id";
$result = $conn->query($sql);
$menu = $result->fetch_assoc();

if (!$menu) {
    echo "Menu tidak ditemukan!";
    exit;
}

// Proses ketika tombol Checkout diklik
if (isset($_POST['checkout'])) {
    $jumlah = $_POST['jumlah'];
    $total = $menu['harga'] * $jumlah;

    // Menyimpan data checkout dalam session
    $_SESSION['checkout'] = [
        'nama_menu' => $menu['nama_menu'],
        'harga' => $menu['harga'],
        'jumlah' => $jumlah,
        'total' => $total
    ];

    // Set flag checkout untuk menampilkan struk
    $checkout_done = true;
} else {
    $checkout_done = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu</title>
    <style>
       body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
            background-color: #f4e1d2;
            color: #333;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Kontainer logo */
        .logo-container {
            text-align: center;
            margin-top: 20px;
        }

        /* Logo */
        .logo-container img {
            max-width: 150px;
            height: auto;
        }

        /* Header */
        h2 {
            text-align: center;
            color: #444;
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Formulir */
        form {
            background-color: #fff;
            padding: 30px;
            margin: 30px auto;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            font-size: 16px;
            border-top: 3px solid #6F4E37;
        }

        /* Gambar Menu */
        img {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Input dan button */
        input[type="number"] {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 15px;
            width: 50%;
            display: inline-block;
            box-sizing: border-box;
        }

        button {
            background-color: #6F4E37;
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #5a3d2d;
        }

        /* Tautan Kembali */
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #6F4E37;
            font-weight: bold;
            font-size: 16px;
        }

        a:hover {
            color: #5a3d2d;
        }

        /* Style untuk struk belanja */
        .receipt {
            background-color: #fff;
            padding: 30px;
            margin: 30px auto;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: left;
            font-family: 'Courier New', Courier, monospace;
            border: 1px solid #ccc;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        /* Animasi FadeIn */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .receipt h2 {
            color: #6F4E37;
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            text-transform: uppercase;
        }

        /* Pemisah antara informasi di struk */
        .receipt hr {
            border: 0;
            border-top: 1px dashed #ddd;
            margin: 10px 0;
        }

        /* Format teks dalam struk */
        .receipt p {
            font-size: 18px;
            margin: 10px 0;
            line-height: 1.5;
        }

        /* Styling untuk label dan total */
        .receipt p strong {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Tombol Kembali */
        .back-button {
            display: inline-block;
            background-color: #6F4E37;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 20px;
            width: auto;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background-color: #5a3d2d;
            transform: scale(1.05);
        } /* Styling here */
    </style>
</head>
<body>

    <div class="logo-container">
        <img src="logo.png" alt="Logo Coffee">
    </div>

    <?php if ($checkout_done): ?>
        <div class="receipt">
            <h2>Terima Kasih!</h2>
            <hr>
            <p>Pesanan Anda sedang diproses. Berikut adalah detail pesanan Anda:</p>
            <p><strong>Nama Menu:</strong> <?= isset($_SESSION['checkout']['nama_menu']) ? $_SESSION['checkout']['nama_menu'] : 'Data tidak ditemukan' ?></p>
            <p><strong>Harga:</strong> Rp. <?= isset($_SESSION['checkout']['harga']) ? number_format($_SESSION['checkout']['harga'], 0, ',', '.') : '0' ?></p>
            <p><strong>Jumlah:</strong> <?= isset($_SESSION['checkout']['jumlah']) ? $_SESSION['checkout']['jumlah'] : '0' ?></p>
            <p><strong>Total Harga:</strong> Rp. <?= isset($_SESSION['checkout']['total']) ? number_format($_SESSION['checkout']['total'], 0, ',', '.') : '0' ?></p>
            <a href="user.php" class="back-button">Kembali ke Halaman Utama</a>
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <p>Nama Menu: <?= $menu['nama_menu'] ?></p>
            <p>Harga: Rp. <?= number_format($menu['harga'], 0, ',', '.') ?></p>
            <p>Deskripsi: <?= $menu['deskripsi'] ?></p>
            <p>Gambar: <img src="<?= $menu['url_gambar'] ?>" width="100"></p>
            <label for="jumlah">Jumlah:</label>
            <input type="number" id="jumlah" name="jumlah" value="1" min="1" required><br><br>
            <button type="submit" name="checkout">Checkout</button>
        </form>
        <a href="user.php" class="back-button">Kembali</a>
    <?php endif; ?>

</body>
</html>

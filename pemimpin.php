<?php
session_start();

// Cek apakah pengguna login sebagai pemimpin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pemimpin') {
    header('Location: index.php'); // Redirect ke halaman login jika bukan pemimpin
    exit;
}

include 'db.php'; // Pastikan Anda menghubungkan ke database dengan benar

$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
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

        /* Tombol download dan print */
        .action-buttons {
            margin: 20px auto;
            text-align: center;
        }

        .action-buttons button {
            padding: 10px 20px;
            background-color: #6F4E37;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
        }

        .action-buttons button:hover {
            background-color: #5a3e30;
        }
    </style>
</head>
<body>

<h2>Menu Daftar</h2>

<!-- Tombol untuk download dan print -->
<div class="action-buttons">
    <button onclick="downloadCSV()">Download </button>
    <button onclick="window.print()">Print</button>
</div>

<table id="menuTable" border="1">
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
</tr>

    <?php
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
    </tr>";
    }
    ?>
</table>

<script>
    // Fungsi untuk mengunduh tabel sebagai file 
    function downloadCSV() {
        var table = document.getElementById("menuTable");
        var rows = table.rows;
        var csv = [];
        
        // Menyusun data baris pertama (header)
        var header = [];
        for (var i = 0; i < rows[0].cells.length; i++) {
            header.push(rows[0].cells[i].textContent);
        }
        csv.push(header.join(","));

        // Menyusun data baris berikutnya
        for (var i = 1; i < rows.length; i++) {
            var row = [];
            for (var j = 0; j < rows[i].cells.length; j++) {
                row.push(rows[i].cells[j].textContent);
            }
            csv.push(row.join(","));
        }

        var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        var link = document.createElement("a");
        link.href = URL.createObjectURL(csvFile);
        link.download = "menu.csv";
        link.click();
    }
</script>

</body>
</html>

<?php
// Pastikan Anda menutup koneksi database
$conn->close();
?>

<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password sebelum validasi
    $role = $_POST['role'];

    // Cek apakah username dan password sesuai dengan role
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = '$role'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect berdasarkan role
        if ($role === 'admin') {
            header('Location: admin.php');
        } elseif ($role === 'user') {
            header('Location: user.php');
        } elseif ($role === 'pemimpin') {
            header('Location: pemimpin.php'); // Halaman untuk pemimpin
        }
        exit;
    } else {
        $error = "Login gagal! Periksa username, password, dan role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e1d2; /* Warna coklat muda */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff; /* Warna putih untuk form box */
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-container img {
            display: block;
            margin: 0 auto 10px;
            width: 100px; /* Ukuran gambar */
            height: auto;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
            color: #555555;
        }

        .login-container input, .login-container select, .login-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container button {
            background-color: #6f4e37; /* Warna coffee brown untuk tombol */
            color: #ffffff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .login-container button:hover {
            background-color: #56392d; /* Warna coffee brown yang lebih gelap */
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Menampilkan gambar logo menggunakan PHP -->
        <img src="<?php echo 'logo.png'; ?>" alt="Logo Coffee">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
                <option value="pemimpin">Pemimpin</option>

            </select>

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>

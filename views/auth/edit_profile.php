<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = pdo();

$msg = [];

if (!isset($_SESSION['user_id'])){
    die("kamu harus login!");
}

$id = $_SESSION['user_id'];
$target = __DIR__ . '/../../public/uploads/';

$stmt = $pdo->prepare("select * from users where id = :id");
$stmt->execute(['id' => $id]);
$user = $stmt->fetch();

if (!$user){
    die("User tidak ada!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!validate_csrf($_POST['csrf'] ?? '')){
        $msg[] = 'invalid token csrf';
    }

    $nama = trim($_POST['name'] ?? '');
    $password = $_POST['password'];

    $hash = $password ? password_hash($password, PASSWORD_DEFAULT) : $user['password'];

    $foto = $user['foto'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0){
        
        $newfoto = $_FILES['foto'];
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($newfoto['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)){
            die("Format file tidak didukung");
        }

        $oldfoto = $user['foto'];
        if ($oldfoto && file_exists($target . $oldfoto)){
            unlink($target . $oldfoto);
        }

        $namafile = time() . '_' . uniqid() . '.' . $ext;
        $destination = $target . $namafile;

        if(move_uploaded_file($newfoto['tmp_name'], $destination)){
            $foto = $namafile;
        } else {
            $msg[] = "gagal memindahkan";
        }
    }

    $stmt = $pdo->prepare("update users set name = :name, password = :password, foto = :foto where id = :id");
    $stmt->execute([
        'name' => $nama,
        'password' => $hash,
        'foto' => $foto,
        'id' => $id
    ]);

    header('Location: profile.php?id= '  . $id);
}
?>

<?php
session_start();
require_once 'conexion.php';

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// ✅ Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $username = trim($_POST['username']);

    // Verifica si se subió una nueva imagen
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $imagenData = file_get_contents($_FILES['imagen']['tmp_name']);
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, username = ?, imagen = ? WHERE id = ?");
        $stmt->execute([$nombre, $username, $imagenData, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, username = ? WHERE id = ?");
        $stmt->execute([$nombre, $username, $user_id]);
    }

    // Actualiza los datos de sesión
    $_SESSION['nombre'] = $nombre;
    $_SESSION['username'] = $username;

    // Redirige de nuevo al panel
    header('Location: dashboard.php');
    exit;
}

// 🔹 Cargar datos actuales del usuario
$stmt = $pdo->prepare("SELECT nombre, username, imagen FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$imagenSrc = !empty($usuario['imagen'])
    ? "mostrar_imagen.php?id=" . urlencode($user_id)
    : "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil - LearnHub</title>
  <link rel="stylesheet" href="style/editar.css">
</head>
<body>
  <div class="editar-container">
    <h2>Editar Perfil</h2>
    <img src="<?= htmlspecialchars($imagenSrc) ?>" alt="Foto de perfil">
    <form action="" method="POST" enctype="multipart/form-data">
      <input type="text" name="nombre" placeholder="Nombre completo" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
      <input type="text" name="username" placeholder="Nombre de usuario" value="<?= htmlspecialchars($usuario['username']) ?>" required>
      <input type="file" name="imagen" accept="image/*">
      <br>
      <input type="submit" value="Guardar Cambios">

      <input type="button" value="Cancelar" onclick="window.location.href='dashboard.php'">
    </form>
    <a class="volver" href="dashboard.php">Volver al Panel</a>
  </div>
</body>
</html>

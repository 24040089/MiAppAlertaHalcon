<?php
session_start();

// Verificar login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'conexion.php';

// Datos de sesión
$user_id  = $_SESSION['user_id'];
$nombre   = $_SESSION['nombre'];
$username = $_SESSION['username'];

// Obtener datos completos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Imagen dinámica
$imagenSrc = "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
if (!empty($usuario['imagen'])) {
    $imagenSrc = "mostrar_imagen.php?id=" . urlencode($user_id);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - Alerta Halcón</title>
  <link rel="stylesheet" href="style/phalcon.css">
</head>
<body>
  <div class="app">
    

    <!-- Encabezado -->
    <header class="header">
      <div class="logo">🦅</div>
      <h1>Mi Perfil</h1>
      <button class="btn-icon">⚙️</button>
    </header>

    <!-- Barra de navegación superior -->
    <nav class="navbar">
      <a href="dashboard.php" class="nav-item">🏠 Inicio</a>
      <a href="perfil.php" class="nav-item active">👤 Perfil</a>
      <a href="horarios.php" class="nav-item">📅 Calendario</a>
    </nav>

    <!-- Contenido principal -->
    <main class="main perfil">

      <!-- Tarjeta del usuario -->
      <section class="user-card">
        <img src="<?= $imagenSrc ?>" alt="Foto de perfil" class="avatar">
        <h2 class="username"><?= htmlspecialchars($nombre) ?></h2>
        <p class="usertag">@<?= htmlspecialchars($username) ?></p>

        <form action="editar.php" method="GET">
            <button class="btn-edit">✏️ Editar Perfil</button>
        </form>
      </section>

      <!-- Información general -->
      <section class="user-info">
        <h3>Información General</h3>
        <ul>
          <li><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo'] ?? 'No registrado') ?></li>
          <li><strong>Grupo:</strong> <?= htmlspecialchars($usuario['grupo'] ?? 'Sin grupo') ?></li>
          <li><strong>Matrícula:</strong> <?= htmlspecialchars($usuario['matricula'] ?? '---') ?></li>
          <li><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono'] ?? '---') ?></li>
        </ul>
      </section>

      <!-- Configuración -->
      <section class="settings">
        <h3>Configuración</h3>
        <div class="settings-list">
          <button class="setting-item">🔔 Notificaciones</button>
          <button class="setting-item">🎨 Tema de la app</button>
          <button class="setting-item">🔒 Seguridad</button>

          <form action="procesar.php" method="POST">
            <input type="hidden" name="accion" value="logout">
            <button type="submit" class="setting-item logout">🚪 Cerrar sesión</button>
          </form>
        </div>
      </section>

    </main>

    <!-- Barra inferior -->
    <nav class="bottomnav">
      <a href="dashboard.php">🏠 Inicio</a>
      <a href="#">⏰ Alarmas</a>
      <a href="#">📞 Contactos</a>
      <a href="perfil.php" class="active">👤 Perfil</a>
    </nav>

  </div>
</body>
</html>

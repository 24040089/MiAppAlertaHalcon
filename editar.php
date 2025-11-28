<?php
session_start();
require_once 'conexion.php';

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');

    if ($nombre === '' || $username === '') {
        $error = "Por favor completa todos los campos.";
    } else {
        try {
            // Verifica si se subió una nueva imagen
            if (!empty($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                
                // Obtener datos de la imagen
                $imageData = file_get_contents($_FILES['image']['tmp_name']);
                $imageMimeType = $_FILES['image']['type'];
                
                // Validar que sea una imagen válida
                if (strpos($imageMimeType, 'image/') === 0) {
                    $stmt = $pdo->prepare("UPDATE usuarios SET name = ?, username = ?, imagen = ?, tipo_imagen = ? WHERE id = ?");
                    $stmt->execute([$nombre, $username, $imageData, $imageMimeType, $user_id]);
                } else {
                    $error = "El archivo debe ser una imagen válida (JPG, PNG, GIF, etc.)";
                }
                
            } else {
                // Solo actualizar nombre y username
                $stmt = $pdo->prepare("UPDATE usuarios SET name = ?, username = ? WHERE id = ?");
                $stmt->execute([$nombre, $username, $user_id]);
            }

            // Actualiza los datos de sesión
            $_SESSION['nombre'] = $nombre;
            $_SESSION['username'] = $username;

            // Redirige de nuevo al perfil
            header('Location: perfilhalcon.php');
            exit;

        } catch (PDOException $e) {
            $error = "Error al actualizar perfil: " . $e->getMessage();
        }
    }
}

// Cargar datos actuales del usuario
$stmt = $pdo->prepare("SELECT name, username, imagen FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fuente de la imagen
$imageSrc = !empty($user['imagen'])
    ? "mostrar_imagen.php?id=" . urlencode($user_id)
    : "https://cdn-icons-png.flaticon.com/512/3135/3135715.png";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🦅 Editar Perfil - AlertaHalcon</title>
  <link rel="stylesheet" href="style/edit.css">
</head>
<body>
  <!-- Halcones de fondo -->
  <div class="falcon-bg">🦅</div>
  <div class="falcon-bg">🦅</div>

  <div class="container">
    <!-- Botón volver -->
    <a href="perfilhalcon.php" class="back-button">← Volver al perfil</a>

    <div class="card">
      <!-- Destellos decorativos -->
      <div class="sparkle"></div>
      <div class="sparkle"></div>
      <div class="sparkle"></div>
      <div class="sparkle"></div>

      <div class="falcon-icon">🦅</div>
      <h2>EDITAR PERFIL</h2>
      <p class="subtitle">Actualiza tu información 🌟</p>

      <?php if (!empty($error)): ?>
        <div class="error-message">
          ⚠️ <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <!-- Vista previa de la imagen -->
      <div class="avatar-preview">
        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="Avatar" id="avatarPreview">
        <div class="avatar-overlay">
          <span>📷</span>
        </div>
      </div>

      <form action="" method="POST" enctype="multipart/form-data">
        <div class="input-group">
          <label for="name">Nombre Completo</label>
          <div class="input-wrapper">
            <span class="input-icon">✨</span>
            <input 
              type="text" 
              id="name" 
              name="name" 
              placeholder="Tu nombre completo" 
              value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
              required>
          </div>
        </div>

        <div class="input-group">
          <label for="username">Nombre de Usuario</label>
          <div class="input-wrapper">
            <span class="input-icon">👤</span>
            <input 
              type="text" 
              id="username" 
              name="username" 
              placeholder="Tu nombre de usuario" 
              value="<?= htmlspecialchars($user['username'] ?? '') ?>" 
              required>
          </div>
        </div>

        <div class="input-group">
          <label for="image">Foto de Perfil 📸</label>
          <div class="file-input-wrapper">
            <input 
              type="file" 
              id="image" 
              name="image" 
              accept="image/jpeg,image/png,image/gif,image/webp" 
              onchange="previewImage(this)">
            <label for="image" class="file-input-label">
              <span class="icon">📷</span>
              <span>Cambiar foto de perfil</span>
            </label>
            <div class="file-name" id="fileName"></div>
          </div>
        </div>

        <div class="button-group">
          <button type="submit" class="btn-primary">
            💾 Guardar Cambios
          </button>
          <button type="button" class="btn-secondary" onclick="window.location.href='perfilhalcon.php'">
            ❌ Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function previewImage(input) {
      const fileName = document.getElementById('fileName');
      const avatarPreview = document.getElementById('avatarPreview');
      
      if (input.files && input.files[0]) {
        // Validar tamaño (máximo 5MB)
        if (input.files[0].size > 5 * 1024 * 1024) {
          alert('⚠️ La imagen es muy grande. Máximo 5MB.');
          input.value = '';
          return;
        }
        
        // Actualizar nombre del archivo
        fileName.textContent = '✅ ' + input.files[0].name;
        fileName.classList.add('show');
        
        // Previsualizar imagen
        const reader = new FileReader();
        reader.onload = function(e) {
          avatarPreview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      } else {
        fileName.textContent = '';
        fileName.classList.remove('show');
      }
    }
  </script>
</body>
</html>
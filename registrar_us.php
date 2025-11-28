<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/re.css">
  <title>🦅 Registro - AlertaHalcon</title>
  
</head>
<body>
  <!-- Halcones de fondo -->
  <div class="falcon-bg">🦅</div>
  <div class="falcon-bg">🦅</div>
  <div class="falcon-bg">🦅</div>

  <div class="card">
    <!-- Destellos decorativos -->
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>
    <div class="sparkle"></div>

    <div class="falcon-icon">🦅</div>
    <h2>ÚNETE AL HALCÓN</h2>
    <p class="subtitle">Crea tu cuenta y comienza a volar 🌟</p>

    <form method="POST" action="procesar.php" enctype="multipart/form-data">
      <input type="hidden" name="accion" value="registrar_us">

      <div class="input-group">
        <label for="name">Nombre Completo</label>
        <div class="input-wrapper">
          <span class="input-icon">✨</span>
          <input type="text" id="name" name="name" placeholder="Ingresa tu nombre completo" required>
        </div>
      </div>

      <div class="input-group">
        <label for="username">Usuario</label>
        <div class="input-wrapper">
          <span class="input-icon">👤</span>
          <input type="text" id="username" name="username" placeholder="Elige tu nombre de usuario" required>
        </div>
      </div>

      <div class="input-group">
        <label for="password">Contraseña</label>
        <div class="input-wrapper">
          <span class="input-icon">🔒</span>
          <input type="password" id="password" name="password" placeholder="Crea una contraseña segura" required>
        </div>
      </div>

      <div class="input-group">
        <label for="imagen">Foto de Perfil 📸</label>
        <div class="file-input-wrapper">
          <input type="file" id="imagen" name="imagen" accept="image/*" onchange="updateFileName(this)">
          <label for="imagen" class="file-input-label">
            <span class="icon">📷</span>
            <span>Seleccionar imagen</span>
          </label>
          <div class="file-name" id="fileName"></div>
        </div>
      </div>

      <button type="submit">
        🦅 Registrarse Ahora
      </button>

      <p class="login-link">
        ¿Ya tienes cuenta? <a href="index.php">Inicia Sesión ✨</a>
      </p>
    </form>
  </div>

  <script>
    function updateFileName(input) {
      const fileName = document.getElementById('fileName');
      if (input.files && input.files[0]) {
        fileName.textContent = '✅ ' + input.files[0].name;
        fileName.classList.add('show');
      } else {
        fileName.textContent = '';
        fileName.classList.remove('show');
      }
    }
  </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style/ind.css">
  <title>🦅 Login - AlertaHalcon</title>
  
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

    <div class="falcon-icon">🦅</div>
    <h2>ALERTA HALCÓN</h2>
    <p class="subtitle">Vuela alto, vigila todo 🌟</p>

    <form method="POST" action="procesar.php">
      <input type="hidden" name="accion" value="login">
      
      <div class="input-group">
        <label for="username">Usuario</label>
        <div class="input-wrapper">
          <span class="input-icon">👤</span>
          <input type="text" id="username" name="username" placeholder="Ingresa tu usuario" required>
        </div>
      </div>

      <div class="input-group">
        <label for="password">Contraseña</label>
        <div class="input-wrapper">
          <span class="input-icon">🔒</span>
          <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
        </div>
      </div>

      <button type="submit">
        🦅 Iniciar Sesión
      </button>

      <p class="register-link">
        ¿No tienes cuenta? <a href="registrar_us.php">Regístrate aquí ✨</a>
      </p>
    </form>
  </div>
</body>
</html>
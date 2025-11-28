<?php
session_start();

// Validar sesión correcta
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Construir arreglo compatible con el HTML
$u = [
    'nombre'    => $_SESSION['nombre'] ?? '',
    'usuario'   => $_SESSION['username'] ?? '',
    'foto'      => !empty($_SESSION['imagen']) 
                    ? "data:image/jpeg;base64," . base64_encode($_SESSION['imagen'])
                    : "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
];

// Datos de ejemplo para el dashboard
$hora = date('H');
$saludo = $hora < 12 ? '🌅 Buenos días' : ($hora < 18 ? '☀️ Buenas tardes' : '🌙 Buenas noches');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>🦅 Dashboard - Alerta Halcón</title>
  <link rel="stylesheet" href="style/dashboard.css">
</head>

<body>
  <div class="app">

    <!-- Encabezado -->
    <header class="header">
      <div class="logo">🦅</div>
      <h1>AlertaHalcón</h1>
      <button class="btn-icon" onclick="window.location.href='perfilhalcon.php'">👤</button>
    </header>

    <!-- Barra navegación superior -->
    <nav class="navbar">
      <a href="alertaalcon.php" class="nav-item active">🏠 Inicio</a>
      <a href="perfilhalcon.php" class="nav-item">👤 Perfil</a>
      <a href="horarios.php" class="nav-item">📅 Calendario</a>
    </nav>

    <!-- Contenido principal -->
    <main class="main dashboard">

      <!-- Tarjeta de bienvenida -->
      <section class="welcome-card">
        <div class="welcome-content">
          <img src="<?= htmlspecialchars($u['foto']) ?>" alt="Avatar" class="welcome-avatar">
          <div class="welcome-text">
            <h2><?= $saludo ?></h2>
            <p class="welcome-name"><?= htmlspecialchars($u['nombre']) ?></p>
            <p class="welcome-subtitle">Listo para volar alto hoy 🚀</p>
          </div>
        </div>
        <div class="welcome-decoration">✨</div>
      </section>

      <!-- Estadísticas rápidas -->
      <section class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📚</div>
          <div class="stat-info">
            <h3>8</h3>
            <p>Clases esta semana</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">✅</div>
          <div class="stat-info">
            <h3>5</h3>
            <p>Tareas completadas</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">🎯</div>
          <div class="stat-info">
            <h3>92%</h3>
            <p>Asistencia</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">⭐</div>
          <div class="stat-info">
            <h3>8.5</h3>
            <p>Promedio</p>
          </div>
        </div>
      </section>

      <!-- Accesos rápidos -->
      <section class="quick-access">
        <h3 class="section-title">⚡ Accesos Rápidos</h3>
        <div class="access-grid">
          <a href="horarios.php" class="access-card">
            <div class="access-icon">📅</div>
            <span>Mi Horario</span>
          </a>

          <a href="#" class="access-card">
            <div class="access-icon">📝</div>
            <span>Tareas</span>
          </a>

          <a href="#" class="access-card">
            <div class="access-icon">📊</div>
            <span>Calificaciones</span>
          </a>

          <a href="#" class="access-card">
            <div class="access-icon">🔔</div>
            <span>Notificaciones</span>
          </a>

          <a href="#" class="access-card">
            <div class="access-icon">📞</div>
            <span>Contactos</span>
          </a>

          <a href="#" class="access-card">
            <div class="access-icon">⏰</div>
            <span>Alarmas</span>
          </a>
        </div>
      </section>

      <!-- Próximos eventos -->
      <section class="events-section">
        <h3 class="section-title">📅 Próximos Eventos</h3>
        <div class="events-list">
          <div class="event-card">
            <div class="event-date">
              <span class="event-day">15</span>
              <span class="event-month">NOV</span>
            </div>
            <div class="event-info">
              <h4>Examen de Matemáticas</h4>
              <p>📍 Aula 101 • 10:00 AM</p>
            </div>
            <div class="event-badge important">Importante</div>
          </div>

          <div class="event-card">
            <div class="event-date">
              <span class="event-day">18</span>
              <span class="event-month">NOV</span>
            </div>
            <div class="event-info">
              <h4>Entrega de Proyecto</h4>
              <p>📚 Programación Web</p>
            </div>
            <div class="event-badge pending">Pendiente</div>
          </div>

          <div class="event-card">
            <div class="event-date">
              <span class="event-day">20</span>
              <span class="event-month">NOV</span>
            </div>
            <div class="event-info">
              <h4>Junta de Grupo</h4>
              <p>👥 Salón Principal • 2:00 PM</p>
            </div>
            <div class="event-badge normal">Recordatorio</div>
          </div>
        </div>
      </section>

      <!-- Notificaciones recientes -->
      <section class="notifications-section">
        <h3 class="section-title">🔔 Notificaciones Recientes</h3>
        <div class="notifications-list">
          <div class="notification-item unread">
            <div class="notification-icon">📢</div>
            <div class="notification-content">
              <h4>Nueva tarea asignada</h4>
              <p>Investigación sobre Inteligencia Artificial</p>
              <span class="notification-time">Hace 2 horas</span>
            </div>
          </div>

          <div class="notification-item">
            <div class="notification-icon">✅</div>
            <div class="notification-content">
              <h4>Calificación publicada</h4>
              <p>Tu calificación de Historia ya está disponible</p>
              <span class="notification-time">Hace 5 horas</span>
            </div>
          </div>

          <div class="notification-item">
            <div class="notification-icon">⏰</div>
            <div class="notification-content">
              <h4>Recordatorio</h4>
              <p>Examen de Matemáticas en 2 días</p>
              <span class="notification-time">Ayer</span>
            </div>
          </div>
        </div>
      </section>

    </main>

    <!-- Barra inferior -->
    <nav class="bottomnav">
      <a href="alertaalcon.php" class="active">
        <span class="nav-icon">🏠</span>
        <span class="nav-label">Inicio</span>
      </a>
      <a href="horarios.php">
        <span class="nav-icon">📅</span>
        <span class="nav-label">Horario</span>
      </a>
      <a href="#">
        <span class="nav-icon">⏰</span>
        <span class="nav-label">Alarmas</span>
      </a>
      <a href="perfilhalcon.php">
        <span class="nav-icon">👤</span>
        <span class="nav-label">Perfil</span>
      </a>
    </nav>

  </div>
</body>
</html>
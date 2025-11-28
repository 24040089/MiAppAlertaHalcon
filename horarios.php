<?php
// ===================================
// CALENDARIO ACADÉMICO HALCÓN
// ===================================

session_start();
require_once 'conexion.php';

// Directorio de uploads
define('UPLOAD_DIR', 'uploads/calendarios/');

// Crear directorio si no existe
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}

// Procesar formulario de subida de calendario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir_calendario'])) {
    $nombre = $_POST['nombre_calendario'];
    $archivo = $_FILES['archivo_calendario'];
    
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $permitidos = ['pdf', 'jpg', 'jpeg', 'png'];
    
    if (in_array($extension, $permitidos)) {
        $nombre_archivo = uniqid() . '.' . $extension;
        $ruta_destino = UPLOAD_DIR . $nombre_archivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $stmt = $pdo->prepare("INSERT INTO calendarios (nombre, archivo, tipo) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $nombre_archivo, $extension]);
            $_SESSION['mensaje'] = "✅ Calendario subido exitosamente";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $_SESSION['mensaje'] = "❌ Error al subir el archivo";
        }
    } else {
        $_SESSION['mensaje'] = "❌ Solo se permiten archivos PDF, JPG o PNG";
    }
}

// Procesar formulario de agregar clase
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_clase'])) {
    $materia = $_POST['materia'];
    $maestro = $_POST['maestro'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $dias = isset($_POST['dias']) ? implode(',', $_POST['dias']) : '';
    $aula = $_POST['aula'];
    $color = $_POST['color'];
    
    if (empty($dias)) {
        $_SESSION['mensaje'] = "❌ Debes seleccionar al menos un día";
    } else {
        $stmt = $pdo->prepare("INSERT INTO clases (materia, maestro, hora_inicio, hora_fin, dias, aula, color) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$materia, $maestro, $hora_inicio, $hora_fin, $dias, $aula, $color]);
        $_SESSION['mensaje'] = "✅ Clase agregada exitosamente";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Eliminar clase
if (isset($_GET['eliminar_clase'])) {
    $id = intval($_GET['eliminar_clase']);
    $stmt = $pdo->prepare("DELETE FROM clases WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['mensaje'] = "✅ Clase eliminada";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Eliminar calendario
if (isset($_GET['eliminar_calendario'])) {
    $id = intval($_GET['eliminar_calendario']);
    $stmt = $pdo->prepare("SELECT archivo FROM calendarios WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        @unlink(UPLOAD_DIR . $row['archivo']);
        $stmt = $pdo->prepare("DELETE FROM calendarios WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['mensaje'] = "✅ Calendario eliminado";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Obtener clases
$stmt_clases = $pdo->query("SELECT * FROM clases ORDER BY hora_inicio");
$clases = $stmt_clases->fetchAll(PDO::FETCH_ASSOC);

// Obtener calendarios
$stmt_calendarios = $pdo->query("SELECT * FROM calendarios ORDER BY fecha_subida DESC");
$calendarios = $stmt_calendarios->fetchAll(PDO::FETCH_ASSOC);

// Días de la semana
$dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/Cale.css">
    <title>Calendario Académico Halcón</title>
</head>
<body>
    <div class="app">
        <header class="header">
            <div class="logo">🦅</div>
            <h1>CALENDARIO ACADÉMICO HALCÓN</h1>
            <div class="logo">📅</div>
        </header>

        <main class="main">
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="mensaje">
                    <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="tabs">
                    <button class="tab active" onclick="cambiarTab(0)">📚 Mis Clases</button>
                    <button class="tab" onclick="cambiarTab(1)">➕ Agregar Clase</button>
                    <button class="tab" onclick="cambiarTab(2)">📄 Calendarios</button>
                    <button class="tab" onclick="cambiarTab(3)">⬆️ Subir Calendario</button>
                </div>

                <!-- Tab 1: Lista de Clases -->
                <div class="tab-content active">
                    <h2>📚 Mis Clases</h2>
                    <?php if (count($clases) > 0): ?>
                        <?php foreach($clases as $clase): ?>
                            <div class="clase-item" style="border-left-color: <?php echo htmlspecialchars($clase['color']); ?>">
                                <div class="clase-header">
                                    <div>
                                        <div class="clase-materia"><?php echo htmlspecialchars($clase['materia']); ?></div>
                                    </div>
                                    <button class="btn-delete" onclick="if(confirm('¿Eliminar esta clase?')) window.location.href='?eliminar_clase=<?php echo $clase['id']; ?>'">
                                        🗑️ Eliminar
                                    </button>
                                </div>
                                <div class="clase-info">
                                    <span>👨‍🏫 Maestro: <?php echo htmlspecialchars($clase['maestro']); ?></span>
                                    <span>🕐 Horario: <?php echo date('h:i A', strtotime($clase['hora_inicio'])); ?> - <?php echo date('h:i A', strtotime($clase['hora_fin'])); ?></span>
                                    <span class="clase-dias">📅 Días: <?php echo str_replace(',', ', ', htmlspecialchars($clase['dias'])); ?></span>
                                    <?php if($clase['aula']): ?>
                                        <span>🚪 Aula: <?php echo htmlspecialchars($clase['aula']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📚</div>
                            <p>No hay clases registradas aún</p>
                            <p style="font-size: 13px; margin-top: 10px;">Comienza agregando tu primera clase</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 2: Agregar Clase -->
                <div class="tab-content">
                    <h2>➕ Agregar Nueva Clase</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>📚 Materia *</label>
                            <input type="text" name="materia" required placeholder="Ej: Matemáticas Avanzadas">
                        </div>

                        <div class="form-group">
                            <label>👨‍🏫 Maestro *</label>
                            <input type="text" name="maestro" required placeholder="Ej: Prof. Juan Pérez">
                        </div>

                        <div class="form-group">
                            <label>🕐 Hora de Inicio *</label>
                            <input type="time" name="hora_inicio" required>
                        </div>

                        <div class="form-group">
                            <label>🕐 Hora de Fin *</label>
                            <input type="time" name="hora_fin" required>
                        </div>

                        <div class="form-group">
                            <label>📅 Días de la Semana *</label>
                            <div class="checkbox-group">
                                <?php foreach($dias_semana as $dia): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="dias[]" value="<?php echo $dia; ?>" id="dia_<?php echo $dia; ?>">
                                        <label for="dia_<?php echo $dia; ?>" style="margin: 0; cursor: pointer;"><?php echo $dia; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>🚪 Aula (Opcional)</label>
                            <input type="text" name="aula" placeholder="Ej: A-201">
                        </div>

                        <div class="form-group">
                            <label>🎨 Color</label>
                            <input type="color" name="color" value="#3b82f6">
                        </div>

                        <button type="submit" name="agregar_clase" class="btn">✅ Agregar Clase</button>
                    </form>
                </div>

                <!-- Tab 3: Ver Calendarios -->
                <div class="tab-content">
                    <h2>📄 Calendarios Subidos</h2>
                    <?php if (count($calendarios) > 0): ?>
                        <?php foreach($calendarios as $calendario): ?>
                            <div class="calendario-item">
                                <div class="calendario-info">
                                    <div class="calendario-icon">
                                        <?php echo $calendario['tipo'] == 'pdf' ? '📄' : '🖼️'; ?>
                                    </div>
                                    <div>
                                        <div class="calendario-nombre"><?php echo htmlspecialchars($calendario['nombre']); ?></div>
                                        <div class="calendario-fecha">
                                            Subido: <?php echo date('d/m/Y H:i', strtotime($calendario['fecha_subida'])); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="calendario-actions">
                                    <a href="<?php echo UPLOAD_DIR . $calendario['archivo']; ?>" target="_blank" class="btn-view">👁️ Ver</a>
                                    <button class="btn-delete" onclick="if(confirm('¿Eliminar este calendario?')) window.location.href='?eliminar_calendario=<?php echo $calendario['id']; ?>'">🗑️</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📄</div>
                            <p>No hay calendarios subidos</p>
                            <p style="font-size: 13px; margin-top: 10px;">Sube tu primer calendario en formato PDF o imagen</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 4: Subir Calendario -->
                <div class="tab-content">
                    <h2>⬆️ Subir Calendario</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>📝 Nombre del Calendario *</label>
                            <input type="text" name="nombre_calendario" required placeholder="Ej: Calendario Semestre Enero-Mayo 2024">
                        </div>

                        <div class="form-group">
                            <label>📎 Archivo (PDF, JPG, PNG) *</label>
                            <input type="file" name="archivo_calendario" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>

                        <button type="submit" name="subir_calendario" class="btn">⬆️ Subir Calendario</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="calendario.js"></script>
</body>
</html>
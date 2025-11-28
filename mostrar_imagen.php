<?php
// NO usar session_start() aquí para evitar conflictos con headers de imagen
require_once 'conexion.php';

// Verifica que se haya pasado un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirigir a imagen por defecto
    header("Location: https://cdn-icons-png.flaticon.com/512/3135/3135715.png");
    exit;
}

$id = intval($_GET['id']);

try {
    // Consulta la imagen Y su tipo del usuario
    $stmt = $pdo->prepare("SELECT imagen, tipo_imagen FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // DEBUG: ACTIVADO - Comentar después de probar
    echo "ID: $id<br>";
    echo "Row: " . print_r($row, true) . "<br>";
    echo "Imagen existe: " . (!empty($row['imagen']) ? 'SÍ' : 'NO') . "<br>";
    echo "Tamaño: " . (isset($row['imagen']) ? strlen($row['imagen']) : 0) . " bytes<br>";
    echo "Tipo: " . ($row['tipo_imagen'] ?? 'NO DEFINIDO');
    exit;

    // Si el usuario tiene imagen, la mostramos con el tipo correcto
    if ($row && !empty($row['imagen'])) {
        
        // Detectar tipo de imagen
        $tipoImagen = 'image/jpeg'; // Por defecto
        
        if (!empty($row['tipo_imagen'])) {
            // Usar el tipo guardado en la BD
            $tipoImagen = $row['tipo_imagen'];
        } else {
            // Si no hay tipo guardado, detectarlo desde los datos binarios
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoDetectado = $finfo->buffer($row['imagen']);
            
            if ($tipoDetectado && strpos($tipoDetectado, 'image/') === 0) {
                $tipoImagen = $tipoDetectado;
            }
        }
        
        // Limpiar cualquier output previo
        if (ob_get_length()) {
            ob_clean();
        }
        
        // Establecer headers
        header("Content-Type: " . $tipoImagen);
        header("Content-Length: " . strlen($row['imagen']));
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        
        // Enviar imagen
        echo $row['imagen'];
        exit;
    }
    
} catch (PDOException $e) {
    // Log del error (opcional)
    error_log("Error en mostrar_imagen.php: " . $e->getMessage());
}

// Si no tiene imagen o hay error, redirigir a imagen por defecto
header("Location: https://cdn-icons-png.flaticon.com/512/3135/3135715.png");
exit;
?>  



<?php
/**
 * ---------------------------------------------
 * Archivo: conexion.php
 * Descripción: Establece la conexión a la base de datos
 * utilizando PDO de manera estructurada.
 * ---------------------------------------------
 */

// 🔹 Datos de configuración de la base de datos REMOTA
    //$host = 'localhost';
    //$nombreBD = 'david_belmares';
    //$usuario = 'david_belmares';
    //$contraseña = '7mdr4SzSXPa4m4KazAiTNANn1';
    //$charset = 'utf8mb4';

// 🔹 Datos de configuración de la base de datos LOCAL
    $host = 'localhost';
    $nombreBD = 'login_app';
    $usuario = 'root';
    $contraseña = '';
    $charset = 'utf8mb4';

// 🔹 Función para crear la conexión PDO
function conectarBD($host, $nombreBD, $usuario, $contraseña, $charset) {
    try {
        // Data Source Name (DSN)
        $dsn = "mysql:host=$host;dbname=$nombreBD;charset=$charset";

        // Crear objeto PDO
        $conexion = new PDO($dsn, $usuario, $contraseña);

        // Configurar errores como excepciones
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retornar la conexión si todo fue exitoso
        return $conexion;

    } catch (PDOException $e) {
        // Mostrar mensaje de error en caso de fallo
        mostrarErrorConexion($e->getMessage());
    }
}

function mostrarErrorConexion($mensaje) {
    echo <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Error de Conexión</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    background: linear-gradient(135deg, #74ebd5, #9face6);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: 'Poppins', sans-serif;
                }
                .card {
                    max-width: 500px;
                    padding: 30px;
                    border: none;
                    border-radius: 20px;
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
                    background: #fff;
                    text-align: center;
                }
                .card img {
                    width: 120px;
                    margin-bottom: 20px;
                }
                h2 {
                    color: #dc3545;
                    font-weight: 700;
                }
                .mensaje {
                    background: #f8d7da;
                    color: #842029;
                    padding: 12px;
                    border-radius: 8px;
                    font-size: 0.9rem;
                    margin-top: 15px;
                    word-wrap: break-word;
                }
                .btn {
                    margin-top: 20px;
                    background: #0d6efd;
                    color: white;
                    border: none;
                    border-radius: 30px;
                    padding: 10px 25px;
                    transition: 0.3s;
                }
                .btn:hover {
                    background: #0b5ed7;
                }
            </style>
        </head>
        <body>
            <div class="card">
                <img src="https://cdn-icons-png.flaticon.com/512/564/564619.png" alt="Error de conexión">
                <h2>Error de conexión a la base de datos</h2>
                <p>Ocurrió un problema al intentar conectar con el servidor MySQL.</p>
                <div class="mensaje">
                    <strong>Detalles técnicos:</strong><br>
                    $mensaje
                </div>
                <a href="index.html" class="btn">Volver al inicio</a>
            </div>
        </body>
        </html>
    HTML;

}

// 🔹 Llamar a la función y guardar la conexión en una variable global
$pdo = conectarBD($host, $nombreBD, $usuario, $contraseña, $charset);

// 🔹 Mensaje opcional (solo para pruebas)
// echo "✅ Conexión exitosa a la base de datos.";
?>

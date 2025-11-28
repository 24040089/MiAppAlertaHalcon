<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$accion = $_POST['accion'] ?? '';

/* ============================================================
    🔹 REGISTRAR USUARIO
   ============================================================ */
if ($accion === 'registrar_us') {

    $nombre   = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($nombre === '' || $username === '' || $password === '') {
        echo "⚠️ Por favor completa todos los campos.";
        exit();
    }

    try {
        // Encriptar contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 📸 Imagen opcional
        $imagen = null;
        if (!empty($_FILES['imagen']['tmp_name'])) {

            $infoImagen = getimagesize($_FILES['imagen']['tmp_name']);
            if ($infoImagen !== false) {
                $rutaTemp = $_FILES['imagen']['tmp_name'];
                $imagen = fopen($rutaTemp, 'rb');
            }
        }

        // 🗄 INSERTAR USUARIO
        $sql = "INSERT INTO usuarios (name, username, password, imagen, fecha_registro)
                VALUES (:name, :username, :password, :imagen, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $nombre);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_LOB);

        $stmt->execute();

        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        echo "⚠️ Error al registrar: " . $e->getMessage();
        exit();
    }
}

/* ============================================================
    🔹 LOGIN
   ============================================================ */
if ($accion === 'login') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        echo "⚠️ Por favor completa todos los campos.";
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // Guardar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre']  = $user['name'];
            $_SESSION['username']= $user['username'];
            $_SESSION['imagen']  = $user['imagen']; // LOB (binario)

            header("Location: dashboard.php");
            exit();
        }

        echo "❌ Usuario o contraseña incorrectos.";
        exit();

    } catch (PDOException $e) {
        echo "⚠️ Error al iniciar sesión: " . $e->getMessage();
        exit();
    }
}

/* ============================================================
    🔹 LOGOUT
   ============================================================ */
if ($accion === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

/* ============================================================
    SI NINGUNA ACCIÓN ES VÁLIDA
   ============================================================ */
echo "Acción no válida.";
exit();
?>

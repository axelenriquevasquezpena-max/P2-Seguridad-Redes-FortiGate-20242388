<?php
ini_set('display_errors', '0');
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');

try {
    $config = require '/etc/p2/database.php';
    $db = new PDO($config['dsn'], $config['user'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    $productos = $db->query(
        'SELECT id, nombre, precio FROM productos ORDER BY id'
    )->fetchAll();
} catch (Throwable $e) {
    error_log('P2 database error: ' . $e->getMessage());
    http_response_code(503);
    exit('No se pudo consultar la base de datos.');
}

function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Productos — P2</title>
  <style>
    body {font-family: Arial, sans-serif; max-width: 800px;
          margin: 50px auto; padding: 20px; background: #f4f7fb;}
    h1 {color: #164166;}
    table {border-collapse: collapse; width: 100%; background: white;}
    th, td {padding: 14px; border: 1px solid #ccd5df; text-align: left;}
    th {background: #164166; color: white;}
  </style>
</head>
<body>
  <h1>Catálogo de productos</h1>
  <p>Laboratorio P2 — Matrícula 2024-2388</p>
  <p>Productos consultados en MariaDB desde el servidor WEB.</p>
  <table>
    <thead><tr><th>ID</th><th>Producto</th><th>Precio</th></tr></thead>
    <tbody>
    <?php foreach ($productos as $producto): ?>
      <tr>
        <td><?= escapar($producto['id']) ?></td>
        <td><?= escapar($producto['nombre']) ?></td>
        <td><?= escapar($producto['precio']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <p>Hora UTC: <?= gmdate('Y-m-d H:i:s') ?></p>
</body>
</html>

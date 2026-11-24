<?php
/**
 * db.php - Conexión PDO y utilidades de base de datos.
 * Se usa PDO con consultas preparadas para evitar inyección SQL.
 */
require_once dirname(__DIR__) . '/config.php';

function db() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En producción conviene mostrar un mensaje genérico
            die('Error de conexión a la base de datos: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }
    return $pdo;
}

/** Devuelve todos los registros de una consulta preparada */
function q($sql, $params = []) {
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

/** Devuelve el primer registro o null */
function q1($sql, $params = []) {
    $st = db()->prepare($sql);
    $st->execute($params);
    $r = $st->fetch();
    return $r === false ? null : $r;
}

/** Ejecuta INSERT/UPDATE/DELETE y devuelve la cantidad de filas afectadas */
function exec_sql($sql, $params = []) {
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->rowCount();
}

/** Escapa texto para imprimir en HTML */
function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>

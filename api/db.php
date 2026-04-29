<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, '/etc/ssl/certs/ca-certificates.crt', NULL, NULL);
mysqli_real_connect(
    $conn,
    $host,
    $user,
    $pass,
    $db,
    3306,
    NULL,
    MYSQLI_CLIENT_SSL
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');
?>

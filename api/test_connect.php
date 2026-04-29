<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = getenv('DB_HOST');
$port = 3306;

$fp = @fsockopen($host, $port, $errno, $errstr, 5);

if (!$fp) {
    echo "❌ Could not connect to $host:$port ($errstr)";
} else {
    echo "✅ Connected to $host:$port successfully";
    fclose($fp);
}

<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM animals WHERE id=$id");
header("Location: list.php");
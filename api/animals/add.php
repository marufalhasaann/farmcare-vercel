<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

if(isset($_POST['add'])){
    $name = $_POST['name'];
    $type = $_POST['type'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    
    $image_path = NULL;
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK){
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if(in_array($ext, $allowed)){
            $filename = uniqid('animal_') . '.' . $ext;
            $destination = "../assets/animals/" . $filename;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)){
                $image_path = "assets/animals/" . $filename;
            }
        }
    }
    
    mysqli_query($conn, "INSERT INTO animals (name, type, age, weight, status, image_path, last_check) 
                         VALUES ('$name','$type','$age','$weight','$status', '$image_path', CURDATE())");
    header("Location: list.php");
    exit;
}
?>

<h2 class="text-3xl font-bold mb-6">Add New Animal</h2>
<div class="bg-white p-6 rounded-xl shadow max-w-lg">
    <form method="POST" enctype="multipart/form-data">
        <label class="block mb-1">Name *</label>
        <input type="text" name="name" required class="w-full border p-2 mb-4 rounded">
        
        <label class="block mb-1">Type *</label>
        <select name="type" class="w-full border p-2 mb-4 rounded">
            <option value="Cow">Cow</option>
            <option value="Goat">Goat</option>
        </select>
        
        <label class="block mb-1">Age (years) *</label>
        <input type="number" name="age" step="0.1" required class="w-full border p-2 mb-4 rounded">
        
        <label class="block mb-1">Weight (kg) *</label>
        <input type="number" name="weight" step="0.1" required class="w-full border p-2 mb-4 rounded">
        
        <label class="block mb-1">Status</label>
        <input type="text" name="status" value="Healthy" class="w-full border p-2 mb-4 rounded">
        
        <label class="block mb-1">Photo</label>
        <input type="file" name="image" accept="image/*" class="w-full border p-2 mb-4 rounded">
        
        <button name="add" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700">Add Animal</button>
    </form>
</div>
<?php include '../layout/footer.php'; ?>
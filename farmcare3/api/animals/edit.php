<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$id = $_GET['id'];
$animal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM animals WHERE id=$id"));

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $type = $_POST['type'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $status = $_POST['status'];
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK){
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if(in_array($ext, $allowed)){
            $filename = uniqid('animal_') . '.' . $ext;
            $destination = "../assets/animals/" . $filename;
            if(move_uploaded_file($_FILES['image']['tmp_name'], $destination)){
                $image_path = "assets/animals/" . $filename;
                mysqli_query($conn, "UPDATE animals SET image_path='$image_path' WHERE id=$id");
            }
        }
    }
    
    mysqli_query($conn, "UPDATE animals SET name='$name', type='$type', age='$age', weight='$weight', status='$status' WHERE id=$id");
    header("Location: list.php");
    exit;
}
?>

<h2 class="text-3xl font-bold mb-6">Edit Animal</h2>
<div class="bg-white p-6 rounded-xl shadow max-w-lg">
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">
        <label class="block mb-1">Name</label>
        <input type="text" name="name" value="<?= $animal['name'] ?>" required class="w-full border p-2 mb-4 rounded">
        <label class="block mb-1">Type</label>
        <select name="type" class="w-full border p-2 mb-4 rounded">
            <option <?= $animal['type']=='Cow'?'selected':'' ?>>Cow</option>
            <option <?= $animal['type']=='Goat'?'selected':'' ?>>Goat</option>
        </select>
        <label class="block mb-1">Age</label>
        <input type="number" step="0.1" name="age" value="<?= $animal['age'] ?>" required class="w-full border p-2 mb-4 rounded">
        <label class="block mb-1">Weight (kg)</label>
        <input type="number" step="0.1" name="weight" value="<?= $animal['weight'] ?>" required class="w-full border p-2 mb-4 rounded">
        <label class="block mb-1">Status</label>
        <input type="text" name="status" value="<?= $animal['status'] ?>" class="w-full border p-2 mb-4 rounded">
        <label class="block mb-1">Current Photo:</label>
        <?php if($animal['image_path'] && file_exists("../".$animal['image_path'])): ?>
            <img src="../<?= $animal['image_path'] ?>" class="w-32 h-32 object-cover rounded mb-2">
        <?php else: ?>
            <p class="text-gray-400 mb-2">No photo</p>
        <?php endif; ?>
        <label class="block mb-1">New Photo (optional)</label>
        <input type="file" name="image" accept="image/*" class="w-full border p-2 mb-4 rounded">
        <button name="update" class="bg-yellow-600 text-white px-6 py-2 rounded hover:bg-yellow-700">Update</button>
        <a href="list.php" class="ml-2 text-gray-500">Cancel</a>
    </form>
</div>
<?php include '../layout/footer.php'; ?>
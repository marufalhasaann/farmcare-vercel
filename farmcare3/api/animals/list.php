<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$search = $_GET['search'] ?? '';
$where = '';
if($search) $where = "WHERE name LIKE '%$search%' OR type LIKE '%$search%'";

$q = mysqli_query($conn, "SELECT * FROM animals $where ORDER BY name");
?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold">Animals</h2>
    <a href="add.php" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
        <i class="fas fa-plus"></i> Add Animal
    </a>
</div>
<form class="mb-4 flex gap-2">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search animals..." class="border p-2 rounded w-64">
    <button type="submit" class="bg-gray-200 px-4 py-2 rounded">Search</button>
</form>
<?php if(mysqli_num_rows($q) > 0): ?>
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Photo</th>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Age</th>
                <th class="p-3 text-left">Weight</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($q)): ?>
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">
                    <?php if($row['image_path'] && file_exists("../".$row['image_path'])): ?>
                        <img src="../<?= $row['image_path'] ?>" class="w-12 h-12 object-cover rounded-full">
                    <?php else: ?>
                        <span class="text-2xl">🐄</span>
                    <?php endif; ?>
                </td>
                <td class="p-3 font-medium"><?= $row['name'] ?></td>
                <td class="p-3"><?= $row['type'] ?></td>
                <td class="p-3"><?= $row['age'] ?> yrs</td>
                <td class="p-3"><?= $row['weight'] ?> kg</td>
                <td class="p-3"><?= $row['status'] ?></td>
                <td class="p-3 space-x-2">
                    <a href="detail.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline"><i class="fas fa-eye"></i></a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="text-yellow-600 hover:underline"><i class="fas fa-edit"></i></a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="bg-white p-8 rounded-xl text-center text-gray-500">No animals found.</div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>
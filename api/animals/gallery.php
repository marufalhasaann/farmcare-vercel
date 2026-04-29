<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$animals = mysqli_query($conn, "SELECT * FROM animals ORDER BY name");
?>

<h2 class="text-3xl font-bold mb-6">Animal Gallery</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php while($a = mysqli_fetch_assoc($animals)): ?>
    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
        <?php if($a['image_path'] && file_exists("../" . $a['image_path'])): ?>
            <img src="../<?= $a['image_path'] ?>" alt="<?= $a['name'] ?>" class="w-full h-48 object-cover">
        <?php else: ?>
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400 text-6xl">
                🐄
            </div>
        <?php endif; ?>
        <div class="p-4">
            <h3 class="font-bold text-lg mb-1"><?= $a['name'] ?></h3>
            <p class="text-sm text-gray-500"><?= $a['type'] ?> • <?= $a['age'] ?> yrs</p>
            <p class="text-xl font-semibold text-emerald-700 mt-2"><?= $a['weight'] ?> kg</p>
            <a href="detail.php?id=<?= $a['id'] ?>" class="inline-block mt-2 text-blue-600 text-sm hover:underline">View details →</a>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php if(mysqli_num_rows($animals) == 0): ?>
<div class="bg-white p-8 rounded-xl text-center text-gray-500">No animals yet. <a href="add.php" class="text-emerald-600 underline">Add one</a>.</div>
<?php endif; ?>

<?php include '../layout/footer.php'; ?>
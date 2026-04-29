<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
include '../layout/header.php';
include '../layout/sidebar.php';

$apiKey = 'YOUR_OPENWEATHER_API_KEY'; // Replace
$city = 'Dhaka';
$url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";
$weather = null;
$data = @file_get_contents($url);
if($data) $weather = json_decode($data);
?>

<h2 class="text-3xl font-bold mb-6">Weather Advisory</h2>

<?php if($weather && $weather->cod == 200):
    $temp = $weather->main->temp;
    $desc = $weather->weather[0]->description;
    $rain = isset($weather->rain) ? $weather->rain->{'1h'} ?? 0 : 0;
    $wind = $weather->wind->speed;
    $humidity = $weather->main->humidity;
?>
<div class="bg-white p-6 rounded-xl shadow max-w-xl">
    <p class="text-xl">📍 <?= $city ?> – <b><?= ucfirst($desc) ?></b></p>
    <p class="text-6xl font-bold my-2"><?= $temp ?>°C</p>
    <p>💧 Humidity: <?= $humidity ?>% | 💨 Wind: <?= $wind ?> m/s</p>

    <div class="mt-6 space-y-3">
        <?php if($temp > 35): ?>
            <div class="p-3 bg-red-100 text-red-900 rounded">🔥 <b>Extreme Heat</b> – Provide extra water, shade, avoid feeding at noon.</div>
        <?php elseif($temp < 10): ?>
            <div class="p-3 bg-blue-100 text-blue-900 rounded">❄️ <b>Cold</b> – Dry bedding, increase energy feed, close shelters at night.</div>
        <?php endif; ?>
        <?php if($rain > 0): ?>
            <div class="p-3 bg-indigo-100 text-indigo-900 rounded">🌧️ <b>Rain</b> – Move animals to shelter, store hay dry.</div>
        <?php endif; ?>
        <?php if($wind > 10): ?>
            <div class="p-3 bg-yellow-100 text-yellow-900 rounded">💨 <b>Strong Wind</b> – Secure equipment, protect drafts.</div>
        <?php endif; ?>
        <?php if($temp <= 35 && $temp >= 10 && $rain == 0 && $wind <= 10): ?>
            <div class="p-3 bg-green-100 text-green-900 rounded">✅ Normal conditions – good for grazing.</div>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
<div class="bg-red-100 p-4 rounded text-red-800">Could not load weather. Check API key.</div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>
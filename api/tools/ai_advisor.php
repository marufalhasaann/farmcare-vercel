<?php
session_start();
if(!isset($_SESSION['user'])) header("Location: ../index.php");
require '../db.php';
include '../layout/header.php';
include '../layout/sidebar.php';

$api_key = 'YOUR_GEMINI_API_KEY'; // Replace with your key
$answer = '';

if(isset($_POST['question'])){
    // Build context
    $animals = mysqli_query($conn, "SELECT name, type, status, last_check FROM animals");
    $context = "Farm animals:\n";
    while($a = mysqli_fetch_assoc($animals)){
        $context .= "- {$a['name']} ({$a['type']}), status: {$a['status']}, last check: {$a['last_check']}\n";
    }

    // Weather (optional)
    $weather_api = 'YOUR_OPENWEATHER_API_KEY'; // Replace
    $weather_url = "https://api.openweathermap.org/data/2.5/weather?q=Dhaka&appid=$weather_api&units=metric";
    $weather_data = @file_get_contents($weather_url);
    if($weather_data){
        $weather = json_decode($weather_data);
        $context .= "Weather: {$weather->main->temp}°C, {$weather->weather[0]->description}.\n";
    } else {
        $context .= "Weather data unavailable.\n";
    }

    $user_question = $_POST['question'];
    $full_prompt = "You are an expert livestock farm assistant. Use the farm data below to give specific, actionable advice.\n\n$context\n\nFarmer's question: $user_question";

    $model = "gemini-1.5-flash-latest"; // stable model
    $gemini_url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$api_key";

    $post_data = json_encode([
        "contents" => [
            ["parts" => [["text" => $full_prompt]]]
        ]
    ]);

    $ch = curl_init($gemini_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $answer = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not get an answer.';
}
?>

<h2 class="text-3xl font-bold mb-4">🤖 AI Farm Advisor</h2>
<form method="POST" class="bg-white p-6 rounded-xl shadow max-w-xl">
    <textarea name="question" rows="3" placeholder="Ask about health, feeding, profit..." class="w-full border p-2 mb-4"></textarea>
    <button class="bg-emerald-600 text-white px-4 py-2 rounded">Ask AI</button>
</form>
<?php if($answer): ?>
<div class="bg-white p-4 rounded-xl shadow mt-4"><?= nl2br(htmlspecialchars($answer)) ?></div>
<?php endif; ?>
<?php include '../layout/footer.php'; ?>
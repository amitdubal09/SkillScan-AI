<?php
header('Content-Type: application/json');

// Get user input
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($input['message'] ?? '');

if ($userMessage === '') {
    echo json_encode(["reply" => "Please enter a message."]);
    exit;
}

// Gemini API key
$apiKey = "AIzaSyCzbj3O4rvLCao_i3Hc8lkfoCyrJ4od-vQ";

// Gemini 1.5 model endpoint
$url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

// Correct request payload
$data = [
    "contents" => [
        [
            "parts" => [
                [
                    "text" => "You are SkillScan, an expert resume assistant. Respond concisely in 2-3 sentences to this query: " . $userMessage
                ]
            ]
        ]
    ]
];


// Initialize cURL
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(["reply" => "cURL Error: " . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Decode the API response
$result = json_decode($response, true);

// Extract the reply
$reply = "Sorry, I couldn't process that. Check the server logs for the full Gemini response.";

if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $result['candidates'][0]['content']['parts'][0]['text'];
} elseif (isset($result['error']['message'])) {
    $reply = "Gemini API Error: " . $result['error']['message'];
}

echo json_encode(["reply" => $reply]);
?>

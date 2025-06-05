<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define supported languages and default
$availableLangs = ['en', 'hi', 'ta', 'kn', 'te', 'ml'];
$defaultLang = 'en';

// Get selected language from session, or default
$langCode = $_SESSION['lang'] ?? $defaultLang;
$langCode = in_array($langCode, $availableLangs) ? $langCode : $defaultLang;

// Load fallback (English)
include_once "lang_en.php";
$lang_en = $lang ?? [];

// Load selected language if not English
if ($langCode !== 'en') {
    $selectedFile = "lang_$langCode.php";
    if (file_exists($selectedFile)) {
        include_once $selectedFile;
    }
}

// Ensure $lang is an array and fallback to English keys if missing
$lang_selected = $lang ?? [];
$lang = [];

foreach ($lang_en as $key => $val) {
    $lang[$key] = $lang_selected[$key] ?? $val;
}
?>

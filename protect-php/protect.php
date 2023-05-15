<?php
// protect.php

// Hide Potentially Dangerous Information
header('Server: ');

// Mitigate the Risk of Clickjacking
header('X-Frame-Options: DENY');

// Mitigate the Risk of Cross-Site Scripting (XSS) Attacks
header('X-XSS-Protection: 1; mode=block');

// Avoid Inferring the Response MIME Type
header('X-Content-Type-Options: nosniff');

// Prevent IE from Opening Untrusted HTML
header('X-Download-Options: noopen');

// Ask Browsers to Access Your Site via HTTPS Only
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// Disable DNS Prefetching
header('X-DNS-Prefetch-Control: off');

// Disable Client-Side Caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Set a Content Security Policy
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
?>

<?php
// Loads a gitignored .env file (project root) into the environment, so
// getenv('SMTP_HOST') etc. in config.php work the same way locally and on
// the deploy target without needing true OS-level env vars set up (not
// always exposed per-domain on shared hosting like DreamHost). Required
// from config.php before anything there calls getenv().
$envFile = __DIR__ . '/../../.env';

if (is_file($envFile) === false) {
    return;
}

foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);

    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }

    [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
    $key   = trim($key);
    $value = trim(trim($value), "\"'");

    if ($key !== '' && getenv($key) === false) {
        putenv("{$key}={$value}");
    }
}

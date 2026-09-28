<?php

/**
 * Lightweight local env loader.
 *
 * This repo's tooling blocks editing `.env` files, so we support a non-dotfile
 * `env.local` at the project root for local development.
 *
 * Format: KEY=VALUE (blank lines and lines starting with # are ignored).
 */
function load_env_local(string $basePath): void
{
    $path = rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'env.local';

    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }

        $key = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos + 1));

        if ($key === '') {
            continue;
        }

        // Strip optional wrapping quotes.
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }

        // Do not override real environment variables.
        if (getenv($key) !== false) {
            continue;
        }

        putenv($key.'='.$value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}



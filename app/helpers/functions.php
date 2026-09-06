<?php

declare(strict_types=1);

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

function base_url(string $path = ''): string
{
    $app = require dirname(__DIR__, 2) . '/config/app.php';

    return rtrim($app['url'], '/') . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . base_url($path));
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function oid(string $value): ObjectId
{
    return new ObjectId($value);
}

function now(): UTCDateTime
{
    return new UTCDateTime();
}

function format_date(mixed $value, string $format = 'd/m/Y'): string
{
    $timestamp = null;

    if ($value instanceof UTCDateTime) {
        $timestamp = $value->toDateTime()->getTimestamp();
    } elseif ($value instanceof \DateTimeInterface) {
        $timestamp = $value->getTimestamp();
    } elseif (is_string($value) && $value !== '') {
        $timestamp = strtotime($value);
    }

    return $timestamp === null || $timestamp === false
        ? '—'
        : date($format, (int) $timestamp);
}

function format_money(float $value): string
{
    return number_format($value, 2, ',', '.');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $token = $_POST['_token'] ?? '';
    if (!hash_equals(csrf_token(), (string) $token)) {
        http_response_code(419);
        exit('Token CSRF inválido.');
    }
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function current_user_id(): ?string
{
    return $_SESSION['user_id'] ?? null;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flash(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $flash;
}

function old(string $key, string $default = ''): string
{
    $value = $_SESSION['old'][$key] ?? $default;

    return is_scalar($value) ? (string) $value : $default;
}

function remember_input(array $data): void
{
    $_SESSION['old'] = $data;
}

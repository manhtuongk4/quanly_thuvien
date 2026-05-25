<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url(string $path = ''): string {
    $root = '/quanly_thuvien';
    return $root . ($path ? '/' . ltrim($path, '/') : '');
}

function redirect(string $path): void {
    header('Location: ' . base_url($path));
    exit;
}

function is_logged_in(): bool {
    return isset($_SESSION['admin']);
}

function require_login(): void {
    if (!is_logged_in()) {
        redirect('index.php');
    }
}

function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'] === 'success' ? 'success' : 'danger';
        echo '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">';
        echo e($_SESSION['flash']['message']);
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['flash']);
    }
}

function next_code(PDO $pdo, string $table, string $field, string $prefix, int $digits = 3): string {
    $stmt = $pdo->prepare("SELECT $field FROM $table WHERE $field LIKE ? ORDER BY $field DESC LIMIT 1");
    $stmt->execute([$prefix . '%']);
    $last = $stmt->fetchColumn();
    $num = $last ? ((int)preg_replace('/\D/', '', $last) + 1) : 1;
    return $prefix . str_pad((string)$num, $digits, '0', STR_PAD_LEFT);
}

function book_image_url(?string $path): string {
    $path = trim((string)$path);
    if ($path === '') {
        return base_url('assets/images/book-placeholder.svg');
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    return base_url(ltrim($path, '/'));
}

function money_vnd($amount): string {
    return number_format((float)$amount, 0, ',', '.') . 'đ';
}


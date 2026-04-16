<?php
use App\Core\Auth;
use App\Core\Session;

$loggedUser  = Auth::user();
$flashError  = Session::flash('error');
$flashSuccess= Session::flash('success');
$pageTitle   = $title ?? 'VortexHost';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle . ' · VortexHost') ?></title>
    <meta name="description" content="Hospedagem de jogos, sites e VPS premium com presença no Brasil e Canadá. Anti-DDoS incluso, ativação rápida e suporte humano.">
    <meta name="theme-color" content="#050c08">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
</head>
<body>

<header class="site-header">
    <div class="container">
        <nav class="nav-inner">
            <a class="brand" href="<?= e(base_url('/')) ?>">
                <div class="brand-icon">V</div>
                <span>VortexHost</span>
            </a>

            <ul class="nav-links">
                <li><a href="<?= e(base_url('/')) ?>">Início</a></li>
                <li><a href="<?= e(base_url('/host')) ?>">Hospedagem</a></li>
                <li><a href="<?= e(base_url('/vps')) ?>">VPS</a></li>
                <li><a href="<?= e(base_url('/cpanel')) ?>">cPanel</a></li>
            </ul>

            <div class="nav-actions">
                <?php if ($loggedUser): ?>
                    <a class="btn btn-ghost btn-sm" href="<?= e(base_url('/carrinho')) ?>">Carrinho</a>
                    <a class="btn btn-ghost btn-sm" href="<?= e(base_url('/conta')) ?>"><?= e($loggedUser['name']) ?></a>
                    <?php if (($loggedUser['role'] ?? '') === 'root'): ?>
                        <a class="btn btn-outline btn-sm" href="<?= e(base_url('/admin')) ?>">Admin</a>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="nav-area-note">Cliente?</span>
                    <a class="btn btn-ghost btn-sm" href="<?= e(base_url('/login')) ?>">Entrar</a>
                    <a class="btn btn-primary btn-sm" href="<?= e(base_url('/registro')) ?>">Criar conta</a>
                <?php endif; ?>
                <button class="hamburger" id="hamburgerBtn" aria-label="Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </nav>
        <nav class="mobile-nav" id="mobileNav">
            <a href="<?= e(base_url('/')) ?>">Início</a>
            <a href="<?= e(base_url('/host')) ?>">Hospedagem</a>
            <a href="<?= e(base_url('/vps')) ?>">VPS</a>
            <a href="<?= e(base_url('/cpanel')) ?>">cPanel</a>
            <?php if ($loggedUser): ?>
                <a href="<?= e(base_url('/carrinho')) ?>">Carrinho</a>
                <a href="<?= e(base_url('/conta')) ?>">Minha Conta</a>
                <?php if (($loggedUser['role'] ?? '') === 'root'): ?>
                    <a href="<?= e(base_url('/admin')) ?>">Painel Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?= e(base_url('/login')) ?>">Entrar</a>
                <a href="<?= e(base_url('/registro')) ?>">Criar conta</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<?php if ($flashError): ?>
    <div class="container"><div class="flash flash-error"><?= e($flashError) ?></div></div>
<?php endif; ?>
<?php if ($flashSuccess): ?>
    <div class="container"><div class="flash flash-success"><?= e($flashSuccess) ?></div></div>
<?php endif; ?>

<main>
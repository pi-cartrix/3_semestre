<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Pi Cartrix') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body>
    <div class="m-stripe"></div>

    <header>
        <nav>
            <a href="<?= base_url('/') ?>" class="brand">
                <span class="brand-m">
                    <span class="stripe-blue-light">C</span><span class="stripe-blue-dark">A</span><span class="stripe-red">R</span>
                </span>TRIX
            </a>
            <?php if (is_logged_in()): ?>
                <span class="nav-sep"></span>
                <a href="<?= base_url('/veiculos') ?>">Frota</a>
                <a href="<?= base_url('/revisoes/criar') ?>">Revisões</a>
                <span class="spacer"></span>
                <span class="user"><?= e($_SESSION['user']['name'] ?? '') ?></span>
                <form method="POST" action="<?= base_url('/logout') ?>" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="link-btn">Sair</button>
                </form>
            <?php else: ?>
                <span class="spacer"></span>
                <a href="<?= base_url('/login') ?>">Login</a>
                <a href="<?= base_url('/register') ?>">Cadastro</a>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <?php require __DIR__ . '/../partials/flash.php'; ?>
        <?= $content ?>
    </main>

    <footer>
        <?php require __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</body>
</html>

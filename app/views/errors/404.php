<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;">
    <div>
        <div style="font-size:6rem;font-weight:700;color:#fff;line-height:1;">404</div>
        <p style="font-size:1.125rem;color:#7e7e7e;margin:1rem 0 2rem;">Pagina nao encontrada.</p>
        <a href="<?= base_url('/') ?>" class="btn btn-primary">Voltar ao inicio</a>
    </div>
</body>
</html>

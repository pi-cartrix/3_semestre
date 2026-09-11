<div class="page-head">
    <h1>Frota de veículos</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos/criar') ?>" class="btn btn-primary">Cadastrar veículo</a>
</div>

<?php if (!empty($statusCounts)): ?>
    <div class="status-summary">
        <?php foreach ($statuses as $key => $label): ?>
            <div class="stat-card">
                <span class="badge badge-<?= e($key) ?>"><?= e($label) ?></span>
                <strong><?= (int) ($statusCounts[$key] ?? 0) ?></strong>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$filtersUrl = '/veiculos';
require __DIR__ . '/../partials/listagem_veiculos.php';
?>

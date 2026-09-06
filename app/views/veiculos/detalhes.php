<?php
use App\Models\Dano;
?>
<div class="page-head">
    <h1><?= e((string) $vehicle->marca) ?> <?= e((string) $vehicle->modelo) ?></h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos/' . (string) $vehicle->_id . '/editar') ?>" class="btn btn-primary">Editar</a>
    <a href="<?= base_url('/revisoes/criar?vehicle_id=' . (string) $vehicle->_id) ?>" class="btn btn-light">Registrar revisao</a>
    <a href="<?= base_url('/danos/criar?vehicle_id=' . (string) $vehicle->_id) ?>" class="btn btn-light">Registrar dano</a>
</div>

<div class="card-grid">
    <div class="card">
        <h3>Dados do veiculo</h3>
        <dl class="detail">
            <dt>Placa</dt><dd><?= e((string) $vehicle->placa) ?></dd>
            <dt>Chassi</dt><dd><?= e((string) $vehicle->chassi) ?></dd>
            <dt>Quilometragem</dt><dd><?= e(number_format((int) $vehicle->quilometragem, 0, ',', '.')) ?> km</dd>
            <dt>Status</dt><dd><?php $status = (string) $vehicle->status; require __DIR__ . '/../partials/badge_status.php'; ?></dd>
        </dl>
    </div>

    <div class="card">
        <h3>Atualizar status</h3>
        <form method="POST" action="<?= base_url('/veiculos/' . (string) $vehicle->_id . '/status') ?>" class="inline-form">
            <?= csrf_field() ?>
            <select name="status">
                <?php foreach ($statuses as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= ($vehicle->status ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Alterar</button>
        </form>
    </div>
</div>

<?php if (isset($danoLocalizacoes) && !empty($vehicle->danos ?? [])): ?>
    <div class="card">
        <h3>Danos registrados</h3>
        <?php if (is_array($vehicle->danos) || $vehicle->danos instanceof Countable): ?>
            <?php if (count($vehicle->danos) > 0): ?>
                <ul>
                    <?php foreach ($vehicle->danos as $dano): ?>
                        <li><?= e((string) ($dano['descricao'] ?? '')) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="muted">Nenhum dano registrado.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (!empty($damages)): ?>
    <div class="card">
        <h3>Ultimos danos</h3>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr><th>Data</th><th>Gravidade</th><th>Descricao</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($damages as $dano): ?>
                        <tr>
                            <td><?= e(format_date($dano->data)) ?></td>
                            <td><span class="badge badge-<?= e((string) $dano->gravidade) ?>"><?= e(Dano::GRAVIDADES[$dano->gravidade] ?? $dano->gravidade) ?></span></td>
                            <td><?= e((string) $dano->descricao) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <p class="muted">Nenhum dano registrado no historico.</p>
<?php endif; ?>

<?php
use App\Models\Dano;
?>
<div class="page-head">
    <h1>Danos do veículo</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos/' . (string) $vehicle->_id) ?>" class="btn btn-light">Voltar</a>
    <a href="<?= base_url('/danos/criar?vehicle_id=' . (string) $vehicle->_id) ?>" class="btn btn-primary">Registrar dano</a>
</div>

<div class="card">
    <h3>
        <?= e((string) $vehicle->marca) ?> <?= e((string) $vehicle->modelo) ?>
        <span class="muted">— placa <?= e((string) $vehicle->placa) ?></span>
    </h3>
    <p>Status atual:
        <?php $status = (string) $vehicle->status; require __DIR__ . '/../partials/badge_status.php'; ?>
    </p>
</div>

<?php if (empty($damages)): ?>
    <div class="empty-state">
        <p>Nenhum dano registrado para este veículo.</p>
        <a href="<?= base_url('/danos/criar?vehicle_id=' . (string) $vehicle->_id) ?>" class="btn btn-primary">Registrar primeiro dano</a>
    </div>
<?php else: ?>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Gravidade</th>
                    <th>Localização</th>
                    <th>Descrição</th>
                    <th>Observações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($damages as $dano): ?>
                    <tr>
                        <td><?= e(format_date($dano->data)) ?></td>
                        <td><span class="badge badge-<?= e((string) $dano->gravidade) ?>"><?= e(Dano::GRAVIDADES[$dano->gravidade] ?? $dano->gravidade) ?></span></td>
                        <td><?= e(Dano::localizacoes()[$dano->localizacao] ?? $dano->localizacao) ?></td>
                        <td><?= e((string) $dano->descricao) ?></td>
                        <td><?= e((string) $dano->observacoes) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

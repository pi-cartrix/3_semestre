<?php
use App\Models\Revisao;
?>
<div class="page-head">
    <h1>Historico de revisoes</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos') ?>" class="btn btn-light">Voltar</a>
</div>

<div class="card">
    <h3>
        <?= e((string) $vehicle->marca) ?> <?= e((string) $vehicle->modelo) ?>
        <span class="muted">— placa <?= e((string) $vehicle->placa) ?></span>
    </h3>

    <?php if (!empty($vehicle->quilometragem)): ?>
        <p>Quilometragem atual: <?= e(number_format((int) $vehicle->quilometragem, 0, ',', '.')) ?> km</p>
    <?php endif; ?>
</div>

<?php if (empty($maintenances)): ?>
    <div class="empty-state">
        <p>Nenhuma revisao registrada para este veiculo.</p>
        <a href="<?= base_url('/revisoes/criar?vehicle_id=' . (string) $vehicle->_id) ?>" class="btn btn-primary">Registrar primeira revisao</a>
    </div>
<?php else: ?>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Quilometragem</th>
                    <th>Descricao</th>
                    <th>Pecas</th>
                    <th>Valor</th>
                    <th>Responsavel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maintenances as $maintenance): ?>
                    <tr>
                        <td><?= e(format_date($maintenance->data_revisao)) ?></td>
                        <td><?= e(Revisao::TIPOS[$maintenance->tipo] ?? $maintenance->tipo) ?></td>
                        <td><?= e(number_format((int) $maintenance->quilometragem, 0, ',', '.')) ?> km</td>
                        <td><?= e((string) $maintenance->descricao) ?></td>
                        <td><?= e((string) $maintenance->pecas_substituidas) ?></td>
                        <td>R$ <?= e(format_money((float) $maintenance->valor)) ?></td>
                        <td><?= e((string) $maintenance->responsavel) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><strong>Total investido em revisoes</strong></td>
                    <td colspan="2"><strong>R$ <?= e(format_money($total)) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php endif; ?>

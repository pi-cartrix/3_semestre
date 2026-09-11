<form method="GET" action="<?= base_url($filtersUrl ?? '/veiculos') ?>" class="filters">
    <input type="text" name="q" value="<?= e($query) ?>" placeholder="Buscar por placa, marca, modelo ou chassi...">

    <select name="status">
        <option value="">Todos os status</option>
        <?php foreach ($statuses as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= $status === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn btn-primary">Buscar</button>
    <?php if ($query !== '' || $status !== ''): ?>
        <a href="<?= base_url($filtersUrl ?? '/veiculos') ?>" class="btn btn-light">Limpar</a>
    <?php endif; ?>
</form>

<?php if (empty($vehicles)): ?>
    <p class="muted">Nenhum veículo encontrado.</p>
<?php else: ?>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Quilometragem</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><strong><?= e((string) $vehicle->placa) ?></strong></td>
                        <td><?= e((string) $vehicle->marca) ?></td>
                        <td><?= e((string) $vehicle->modelo) ?></td>
                        <td><?= e(number_format((int) $vehicle->quilometragem, 0, ',', '.')) ?> km</td>
                        <td>
                            <?php $status = (string) $vehicle->status; require __DIR__ . '/badge_status.php'; ?>
                        </td>
                        <td class="actions">
                            <a href="<?= base_url('/veiculos/' . (string) $vehicle->_id) ?>" class="btn btn-sm btn-light">Ver</a>
                            <a href="<?= base_url('/veiculos/' . (string) $vehicle->_id . '/editar') ?>" class="btn btn-sm btn-light">Editar</a>
                            <a href="<?= base_url('/veiculos/' . (string) $vehicle->_id . '/revisoes') ?>" class="btn btn-sm btn-light">Revisões</a>
                            <a href="<?= base_url('/veiculos/' . (string) $vehicle->_id . '/danos') ?>" class="btn btn-sm btn-light">Danos</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

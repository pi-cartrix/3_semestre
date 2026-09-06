<div class="page-head">
    <h1>Registrar revisao</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos') ?>" class="btn btn-light">Voltar</a>
</div>

<form method="POST" action="<?= base_url('/revisoes') ?>" class="form-grid">
    <?= csrf_field() ?>

    <div class="field field-wide">
        <label>Veiculo *</label>
        <select name="vehicle_id" required>
            <option value="">Selecione um veiculo...</option>
            <?php foreach ($vehicles as $vehicle): ?>
                <option value="<?= e((string) $vehicle->_id) ?>" <?= old('vehicle_id', $selectedVehicleId) === (string) $vehicle->_id ? 'selected' : '' ?>>
                    <?= e((string) $vehicle->placa) ?> — <?= e((string) $vehicle->marca) ?> <?= e((string) $vehicle->modelo) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Data da revisao *</label>
        <input type="date" name="data_revisao" value="<?= e(old('data_revisao', date('Y-m-d'))) ?>" required>
    </div>

    <div class="field">
        <label>Quilometragem</label>
        <input type="number" name="quilometragem" min="0" step="1" value="<?= e(old('quilometragem', '0')) ?>">
    </div>

    <div class="field">
        <label>Tipo de revisao *</label>
        <select name="tipo">
            <option value="">Selecione...</option>
            <?php foreach ($types as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= old('tipo') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Valor (R$)</label>
        <input type="number" name="valor" min="0" step="0.01" value="<?= e(old('valor', '0')) ?>">
    </div>

    <div class="field field-wide">
        <label>Pecas substituidas</label>
        <input type="text" name="pecas_substituidas" value="<?= e(old('pecas_substituidas')) ?>" placeholder="Ex.: pastilhas de freio, oleo 5W30...">
    </div>

    <div class="field">
        <label>Responsavel *</label>
        <input type="text" name="responsavel" value="<?= e(old('responsavel')) ?>" required>
    </div>

    <div class="field field-wide">
        <label>Descricao *</label>
        <textarea name="descricao" rows="4" required><?= e(old('descricao')) ?></textarea>
    </div>

    <div class="field field-wide">
        <button type="submit" class="btn btn-primary">Registrar revisao</button>
    </div>
</form>

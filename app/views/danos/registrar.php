<div class="page-head">
    <h1>Registrar dano</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos') ?>" class="btn btn-light">Voltar</a>
</div>

<form method="POST" action="<?= base_url('/danos') ?>" class="form-grid">
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
        <label>Data do dano</label>
        <input type="date" name="data" value="<?= e(old('data', date('Y-m-d'))) ?>">
    </div>

    <div class="field">
        <label>Gravidade *</label>
        <select name="gravidade">
            <option value="">Selecione...</option>
            <?php foreach ($severities as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= old('gravidade') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field field-wide">
        <label>Localizacao do dano *</label>
        <select name="localizacao">
            <option value="">Selecione...</option>
            <option value="dianteira" <?= old('localizacao') === 'dianteira' ? 'selected' : '' ?>>Dianteira</option>
            <option value="traseira" <?= old('localizacao') === 'traseira' ? 'selected' : '' ?>>Traseira</option>
            <option value="lateral_esquerda" <?= old('localizacao') === 'lateral_esquerda' ? 'selected' : '' ?>>Lateral esquerda</option>
            <option value="lateral_direita" <?= old('localizacao') === 'lateral_direita' ? 'selected' : '' ?>>Lateral direita</option>
            <option value="teto" <?= old('localizacao') === 'teto' ? 'selected' : '' ?>>Teto</option>
            <option value="interior" <?= old('localizacao') === 'interior' ? 'selected' : '' ?>>Interior</option>
            <option value="outro" <?= old('localizacao') === 'outro' ? 'selected' : '' ?>>Outro</option>
        </select>
    </div>

    <div class="field field-wide">
        <label>Descricao do dano *</label>
        <textarea name="descricao" rows="4" required><?= e(old('descricao')) ?></textarea>
    </div>

    <div class="field field-wide">
        <label>Observacoes</label>
        <textarea name="observacoes" rows="3"><?= e(old('observacoes')) ?></textarea>
    </div>

    <div class="field field-wide">
        <button type="submit" class="btn btn-primary">Registrar dano</button>
    </div>
</form>

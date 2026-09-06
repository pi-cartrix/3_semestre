<div class="page-head">
    <h1>Editar veiculo</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos') ?>" class="btn btn-light">Voltar</a>
</div>

<form method="POST" action="<?= base_url('/veiculos/' . (string) $vehicle->_id) ?>" class="form-grid">
    <?= csrf_field() ?>

    <div class="field">
        <label>Marca</label>
        <select disabled>
            <?php foreach ($marcas as $marca): ?>
                <option value="<?= e($marca) ?>" <?= (string) $vehicle->marca === $marca ? 'selected' : '' ?>><?= e($marca) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Chassi</label>
        <input type="text" value="<?= e((string) $vehicle->chassi) ?>" disabled>
    </div>

    <div class="field">
        <label>Modelo *</label>
        <input type="text" name="modelo" value="<?= e(old('modelo', (string) $vehicle->modelo)) ?>" required>
    </div>

    <div class="field">
        <label>Placa *</label>
        <input type="text" name="placa" id="placa" value="<?= e(old('placa', (string) $vehicle->placa)) ?>" required>
    </div>

    <div class="field">
        <label>Quilometragem</label>
        <input type="number" name="quilometragem" min="0" step="1" value="<?= e(old('quilometragem', (string) $vehicle->quilometragem)) ?>">
    </div>

    <div class="field">
        <label>Status</label>
        <select name="status">
            <?php foreach ($statuses as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= ($vehicle->status ?? 'disponivel') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field field-wide">
        <button type="submit" class="btn btn-primary">Salvar alteracoes</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('placa');
    input.addEventListener('input', function () {
        input.value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 7);
    });
});
</script>

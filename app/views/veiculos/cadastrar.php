<div class="page-head">
    <h1>Cadastrar veiculo</h1>
    <span class="spacer"></span>
    <a href="<?= base_url('/veiculos') ?>" class="btn btn-light">Voltar</a>
</div>

<form method="POST" action="<?= base_url('/veiculos') ?>" class="form-grid">
    <?= csrf_field() ?>

    <div class="field">
        <label>Marca *</label>
        <select name="marca" required>
            <option value="">Selecione a marca</option>
            <?php foreach ($marcas as $marca): ?>
                <option value="<?= e($marca) ?>" <?= old('marca') === $marca ? 'selected' : '' ?>><?= e($marca) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label>Modelo *</label>
        <input type="text" name="modelo" value="<?= e(old('modelo')) ?>" required>
    </div>

    <div class="field">
        <label>Placa *</label>
        <input type="text" name="placa" id="placa" value="<?= e(old('placa')) ?>" placeholder="ABC1D23" required>
    </div>

    <div class="field">
        <label>Chassi *</label>
        <input type="text" name="chassi" value="<?= e(old('chassi')) ?>" placeholder="Minimo 11 caracteres" required>
    </div>

    <div class="field">
        <label>Quilometragem</label>
        <input type="number" name="quilometragem" min="0" step="1" value="<?= e(old('quilometragem', '0')) ?>">
    </div>

    <div class="field">
        <label>Status</label>
        <select name="status">
            <?php foreach ($statuses as $key => $label): ?>
                <option value="<?= e($key) ?>" <?= old('status') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field field-wide">
        <button type="submit" class="btn btn-primary">Salvar veiculo</button>
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

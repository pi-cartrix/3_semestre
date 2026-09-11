<div class="auth-box">
    <h1>Cadastro de usuário</h1>

    <div class="card">
        <form method="POST" action="<?= base_url('/register') ?>">
            <?= csrf_field() ?>

            <label>Nome fantasia</label>
            <input type="text" name="name" value="<?= e(old('name')) ?>" placeholder="Ex.: Cartrix Locadora">

            <label>CNPJ</label>
            <input type="text" name="cnpj" id="cnpj" value="<?= e(old('cnpj')) ?>" placeholder="00.000.000/0000-00" required>

            <label>E-mail</label>
            <input type="email" name="email" value="<?= e(old('email')) ?>" required>

            <label>Senha</label>
            <input type="password" name="password" minlength="8" required>

            <label>Confirmar senha</label>
            <input type="password" name="password_confirm" minlength="8" required>

            <button type="submit" class="btn btn-primary btn-block">Criar conta</button>
        </form>

        <p>
            Já possui uma conta? <a href="<?= base_url('/login') ?>">Entrar</a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cnpj');
    input.addEventListener('input', function () {
        let v = input.value.replace(/\D/g, '').slice(0, 14);
        if (v.length > 12) v = v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
        else if (v.length > 8) v = v.replace(/(\d{2})(\d{3})(\d{3})(\d{0,4})/, '$1.$2.$3/$4');
        else if (v.length > 5) v = v.replace(/(\d{2})(\d{3})(\d{0,3})/, '$1.$2.$3');
        else if (v.length > 2) v = v.replace(/(\d{2})(\d{0,3})/, '$1.$2');
        input.value = v;
    });
});
</script>

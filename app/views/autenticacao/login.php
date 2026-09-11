<div class="auth-box">
    <h1>Login</h1>

    <div class="card">
        <form method="POST" action="<?= base_url('/login') ?>">
            <?= csrf_field() ?>

            <label>E-mail ou CNPJ</label>
            <input type="text" name="email" value="<?= e(old('email')) ?>" required autofocus>

            <label>Senha</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>

        <p>
            Ainda não tem uma conta? <a href="<?= base_url('/register') ?>">Cadastre-se</a>
        </p>
    </div>
</div>

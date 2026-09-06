<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class AutenticacaoController extends Controller
{
    public function showLogin(): void
    {
        if (is_logged_in()) {
            redirect('/');
        }

        $this->render('autenticacao/login', [
            'title' => 'Login',
        ]);
    }

    public function login(): void
    {
        csrf_check();

        $identifier = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($identifier === '') {
            remember_input(['email' => $identifier]);
            set_flash('error', 'Informe o e-mail ou CNPJ.');
            redirect('/login');
        }

        $cnpj = preg_replace('/\D/', '', $identifier);
        $user = $cnpj !== '' && strlen($cnpj) === 14
            ? Usuario::findByCnpj($cnpj)
            : Usuario::findByEmail(strtolower($identifier));

        if ($user === null || !password_verify($password, (string) $user->password)) {
            remember_input(['email' => $identifier]);
            set_flash('error', 'Credenciais inválidas.');
            redirect('/login');
        }

        $_SESSION['user_id'] = (string) $user->_id;
        $_SESSION['user'] = [
            'id' => (string) $user->_id,
            'name' => (string) $user->name,
            'cnpj' => (string) $user->cnpj,
            'email' => (string) $user->email,
        ];

        set_flash('success', 'Login realizado com sucesso.');
        redirect('/');
    }

    public function showRegister(): void
    {
        if (is_logged_in()) {
            redirect('/');
        }

        $this->render('autenticacao/registro', [
            'title' => 'Cadastro de usuário',
        ]);
    }

    public function register(): void
    {
        csrf_check();

        $name = trim((string) ($_POST['name'] ?? ''));
        $cnpj = preg_replace('/\D/', '', (string) ($_POST['cnpj'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');

        $errors = [];

        if ($cnpj === '' ) {
            $errors[] = 'O CNPJ é obrigatório.';
        } elseif (strlen($cnpj) !== 14) {
            $errors[] = 'O CNPJ deve ter 14 dígitos.';
        } elseif (Usuario::findByCnpj($cnpj) !== null) {
            $errors[] = 'Já existe uma conta cadastrada com este CNPJ.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Informe um e-mail válido.';
        } elseif (Usuario::findByEmail($email) !== null) {
            $errors[] = 'Já existe uma conta cadastrada com este e-mail.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter no mínimo 8 caracteres.';
        } elseif ($password !== $confirm) {
            $errors[] = 'A confirmação da senha não confere.';
        }

        if (!empty($errors)) {
            remember_input(['name' => $name, 'cnpj' => $cnpj, 'email' => $email]);
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
            redirect('/register');
        }

        $userId = Usuario::create($cnpj, $email, $password, $name);

        $_SESSION['user_id'] = (string) $userId;
        $_SESSION['user'] = [
            'id' => (string) $userId,
            'name' => $name,
            'cnpj' => $cnpj,
            'email' => $email,
        ];

        set_flash('success', 'Conta criada com sucesso.');
        redirect('/');
    }

    public function logout(): void
    {
        csrf_check();

        unset($_SESSION['user_id'], $_SESSION['user']);
        session_regenerate_id(true);

        set_flash('success', 'Sessão encerrada.');
        redirect('/login');
    }
}
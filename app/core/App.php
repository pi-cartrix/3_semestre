<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

class App
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/home', 'HomeController@index');

        // Autenticação
        $this->router->get('/login', 'AutenticacaoController@showLogin');
        $this->router->post('/login', 'AutenticacaoController@login');
        $this->router->get('/register', 'AutenticacaoController@showRegister');
        $this->router->post('/register', 'AutenticacaoController@register');
        $this->router->post('/logout', 'AutenticacaoController@logout');

        // Veículos
        $this->router->get('/veiculos', 'VeiculoController@index');
        $this->router->get('/veiculos/criar', 'VeiculoController@create');
        $this->router->post('/veiculos', 'VeiculoController@store');
        $this->router->get('/veiculos/{id}', 'VeiculoController@show');
        $this->router->get('/veiculos/{id}/editar', 'VeiculoController@edit');
        $this->router->post('/veiculos/{id}', 'VeiculoController@update');
        $this->router->post('/veiculos/{id}/status', 'VeiculoController@updateStatus');
        $this->router->delete('/veiculos/{id}', 'VeiculoController@destroy');

        // Revisões
        $this->router->get('/veiculos/{id}/revisoes', 'RevisaoController@history');
        $this->router->get('/revisoes/criar', 'RevisaoController@create');
        $this->router->post('/revisoes', 'RevisaoController@store');

        // Danos
        $this->router->get('/veiculos/{id}/danos', 'DanoController@index');
        $this->router->get('/danos/criar', 'DanoController@create');
        $this->router->post('/danos', 'DanoController@store');
    }

    public function run(): void
    {
        try {
            Database::getConnection();
        } catch (Throwable $e) {
            $this->renderError(500, 'Database connection failed: ' . $e->getMessage());
            return;
        }

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $appConfig = require dirname(__DIR__, 2) . '/config/app.php';
        $basePath = rtrim($appConfig['base_path'] ?? '', '/');
        if ($basePath !== '' && str_starts_with($uri, $basePath . '/')) {
            $uri = substr($uri, strlen($basePath)) ?: '/';
        }

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $this->router->dispatch($method, $uri);
    }

    private function renderError(int $code, string $message): void
    {
        http_response_code($code);
        echo sprintf('<h1>%s</h1><p>%s</p>', $code, htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
    }
}
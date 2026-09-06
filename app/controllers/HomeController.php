<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Veiculo;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $query = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));

        $vehicles = Veiculo::search($query, $status);

        $this->render('home/index', [
            'title' => 'Frota de veículos',
            'vehicles' => $vehicles,
            'query' => $query,
            'status' => $status,
            'statuses' => Veiculo::STATUSES,
        ]);
    }
}
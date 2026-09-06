<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dano;
use App\Models\Marca;
use App\Models\Revisao;
use App\Models\Veiculo;

class VeiculoController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $query = trim((string) ($_GET['q'] ?? ''));
        $status = trim((string) ($_GET['status'] ?? ''));

        $vehicles = Veiculo::search($query, $status);
        $statusCounts = [];

        foreach (Veiculo::STATUSES as $key => $label) {
            $statusCounts[$key] = 0;
        }

        foreach ($vehicles as $vehicle) {
            $key = (string) $vehicle->status;
            if (isset($statusCounts[$key])) {
                $statusCounts[$key]++;
            }
        }

        $this->render('veiculos/listar', [
            'title' => 'Frota de veículos',
            'vehicles' => $vehicles,
            'query' => $query,
            'status' => $status,
            'statuses' => Veiculo::STATUSES,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->render('veiculos/cadastrar', [
            'title' => 'Cadastrar veículo',
            'statuses' => Veiculo::STATUSES,
            'marcas' => Marca::getNames(),
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();
        csrf_check();

        $data = [
            'marca' => trim((string) ($_POST['marca'] ?? '')),
            'modelo' => trim((string) ($_POST['modelo'] ?? '')),
            'placa' => strtoupper(preg_replace('/\s+/', '', (string) ($_POST['placa'] ?? ''))),
            'chassi' => strtoupper(preg_replace('/\s+/', '', (string) ($_POST['chassi'] ?? ''))),
            'quilometragem' => (int) ($_POST['quilometragem'] ?? 0),
            'status' => trim((string) ($_POST['status'] ?? 'disponivel')),
            'danos' => [],
        ];

        $errors = $this->validate($data);

        if (empty($errors) && Veiculo::findByPlate($data['placa']) !== null) {
            $errors[] = 'Já existe um veículo cadastrado com esta placa.';
        }

        if (!empty($errors)) {
            remember_input($data);
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
            redirect('/veiculos/criar');
        }

        Veiculo::create($data);
        set_flash('success', 'Veículo cadastrado com sucesso.');
        redirect('/veiculos');
    }

    public function show(string $id): void
    {
        $this->requireLogin();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        $maintenances = Revisao::porVeiculo($id);
        $damages = Dano::porVeiculo($id);

        $this->render('veiculos/detalhes', [
            'title' => 'Detalhes do veículo',
            'vehicle' => $vehicle,
            'maintenances' => $maintenances,
            'damages' => $damages,
            'statuses' => Veiculo::STATUSES,
        ]);
    }

    public function edit(string $id): void
    {
        $this->requireLogin();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        $this->render('veiculos/editar', [
            'title' => 'Editar veículo',
            'vehicle' => $vehicle,
            'statuses' => Veiculo::STATUSES,
            'marcas' => Marca::getNames(),
            'danoLocalizacoes' => ['dianteira', 'traseira', 'lateral_esquerda', 'lateral_direita', 'teto', 'interior', 'outro'],
        ]);
    }

    public function update(string $id): void
    {
        $this->requireLogin();
        csrf_check();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        $data = [
            'modelo' => trim((string) ($_POST['modelo'] ?? '')),
            'placa' => strtoupper(preg_replace('/\s+/', '', (string) ($_POST['placa'] ?? ''))),
            'quilometragem' => (int) ($_POST['quilometragem'] ?? 0),
            'status' => trim((string) ($_POST['status'] ?? 'disponivel')),
            'danos' => [],
        ];

        $errors = [];

        if ($data['modelo'] === '') {
            $errors[] = 'O modelo é obrigatório.';
        }
        if ($data['placa'] === '') {
            $errors[] = 'A placa é obrigatória.';
        }

        $duplicate = Veiculo::findByPlate($data['placa']);
        if ($duplicate !== null && (string) $duplicate->_id !== $id) {
            $errors[] = 'Já existe outro veículo com esta placa.';
        }

        if (!empty($errors)) {
            remember_input($data);
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
            redirect('/veiculos/' . $id . '/editar');
        }

        Veiculo::atualizarVeiculo($id, $data);
        set_flash('success', 'Veículo atualizado com sucesso.');
        redirect('/veiculos');
    }

    public function updateStatus(string $id): void
    {
        $this->requireLogin();
        csrf_check();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        $status = trim((string) ($_POST['status'] ?? ''));

        if (!isset(Veiculo::STATUSES[$status])) {
            set_flash('error', 'Status inválido.');
            redirect('/veiculos');
        }

        Veiculo::atualizarStatus($id, $status);
        set_flash('success', 'Status do veículo atualizado para "' . Veiculo::STATUSES[$status] . '".');
        redirect('/veiculos');
    }

    public function destroy(string $id): void
    {
        $this->requireLogin();
        csrf_check();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        Veiculo::delete($id);
        set_flash('success', 'Veículo removido da frota.');
        redirect('/veiculos');
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['marca'] === '') {
            $errors[] = 'A marca é obrigatória.';
        }
        if ($data['modelo'] === '') {
            $errors[] = 'O modelo é obrigatório.';
        }
        if ($data['placa'] === '') {
            $errors[] = 'A placa é obrigatória.';
        }
        if ($data['chassi'] === '' || strlen($data['chassi']) < 11) {
            $errors[] = 'O chassi deve ter no mínimo 11 caracteres.';
        }
        if (!isset(Veiculo::STATUSES[$data['status']])) {
            $errors[] = 'Status inválido.';
        }

        return $errors;
    }
}
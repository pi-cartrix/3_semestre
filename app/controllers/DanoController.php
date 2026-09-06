<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dano;
use App\Models\Veiculo;
use MongoDB\BSON\UTCDateTime;

class DanoController extends Controller
{
    public function index(string $id): void
    {
        $this->requireLogin();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        $damages = Dano::porVeiculo($id);

        $this->render('danos/listar', [
            'title' => 'Danos do veículo',
            'vehicle' => $vehicle,
            'damages' => $damages,
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->render('danos/registrar', [
            'title' => 'Registrar dano',
            'vehicles' => Veiculo::all(),
            'severities' => Dano::GRAVIDADES,
            'selectedVehicleId' => (string) ($_GET['vehicle_id'] ?? ''),
        ]);
    }

    public function store(): void
    {
        $this->requireLogin();
        csrf_check();

        $vehicle = Veiculo::find((string) ($_POST['vehicle_id'] ?? ''));

        if ($vehicle === null) {
            set_flash('error', 'Selecione um veículo válido.');
            redirect('/danos/criar');
        }

        $data = [
            'vehicle_id' => $vehicle->_id,
            'descricao' => trim((string) ($_POST['descricao'] ?? '')),
            'data' => $this->parseDate((string) ($_POST['data'] ?? '')),
            'gravidade' => trim((string) ($_POST['gravidade'] ?? '')),
            'localizacao' => trim((string) ($_POST['localizacao'] ?? '')),
            'observacoes' => trim((string) ($_POST['observacoes'] ?? '')),
        ];

        $errors = [];

        if ($data['descricao'] === '') {
            $errors[] = 'Descreva o dano identificado.';
        }
        if ($data['gravidade'] === '' ) {
            $errors[] = 'Selecione a gravidade do dano.';
        }
        if ($data['localizacao'] === '') {
            $errors[] = 'Informe a localização do dano.';
        }

        if (!empty($errors)) {
            remember_input($_POST);
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
            redirect('/danos/criar');
        }

        Dano::create($data);

        if ((string) $vehicle->status !== 'danificado') {
            Veiculo::atualizarStatus((string) $vehicle->_id, 'danificado');
        }

        set_flash('success', 'Dano registrado. O veículo foi marcado como danificado.');
        redirect('/veiculos/' . (string) $vehicle->_id . '/danos');
    }

    private function parseDate(string $value): ?UTCDateTime
    {
        if ($value === '') {
            return null;
        }

        $ts = strtotime($value);

        return $ts === false ? null : new UTCDateTime($ts * 1000);
    }
}
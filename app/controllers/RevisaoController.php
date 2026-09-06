<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Revisao;
use App\Models\Veiculo;
use MongoDB\BSON\UTCDateTime;

class RevisaoController extends Controller
{
    public function history(string $id): void
    {
        $this->requireLogin();

        $vehicle = Veiculo::find($id);

        if ($vehicle === null) {
            set_flash('error', 'Veículo não encontrado.');
            redirect('/veiculos');
        }

        $maintenances = Revisao::porVeiculo($id);
        $total = 0;

        foreach ($maintenances as $maintenance) {
            $total += (float) $maintenance->valor;
        }

        $this->render('revisoes/historico', [
            'title' => 'Histórico de revisões',
            'vehicle' => $vehicle,
            'maintenances' => $maintenances,
            'total' => $total,
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->render('revisoes/registrar', [
            'title' => 'Registrar revisão',
            'vehicles' => Veiculo::all(),
            'types' => Revisao::TIPOS,
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
            redirect('/revisoes/criar');
        }

        $data = [
            'vehicle_id' => $vehicle->_id,
            'data_revisao' => $this->parseDate((string) ($_POST['data_revisao'] ?? '')),
            'quilometragem' => (int) ($_POST['quilometragem'] ?? 0),
            'tipo' => trim((string) ($_POST['tipo'] ?? '')),
            'descricao' => trim((string) ($_POST['descricao'] ?? '')),
            'pecas_substituidas' => trim((string) ($_POST['pecas_substituidas'] ?? '')),
            'valor' => (float) str_replace(',', '.', (string) ($_POST['valor'] ?? '0')),
            'responsavel' => trim((string) ($_POST['responsavel'] ?? '')),
        ];

        $errors = [];

        if ($data['data_revisao'] === null) {
            $errors[] = 'Informe uma data válida.';
        }
        if ($data['tipo'] === '' ) {
            $errors[] = 'Selecione o tipo de revisão.';
        }
        if ($data['descricao'] === '') {
            $errors[] = 'Descreva a revisão realizada.';
        }
        if ($data['responsavel'] === '') {
            $errors[] = 'Informe o responsável.';
        }

        if (!empty($errors)) {
            remember_input($_POST);
            foreach ($errors as $error) {
                set_flash('error', $error);
            }
            redirect('/revisoes/criar');
        }

        Revisao::create($data);

        if ((int) $vehicle->quilometragem < $data['quilometragem']) {
            Veiculo::atualizarVeiculo((string) $vehicle->_id, ['quilometragem' => $data['quilometragem']]);
        }

        set_flash('success', 'Revisão registrada com sucesso.');
        redirect('/veiculos/' . (string) $vehicle->_id . '/revisoes');
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
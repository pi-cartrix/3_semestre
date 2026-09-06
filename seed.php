<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Acesso permitido apenas via linha de comando.');
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/bootstrap.php';

use App\Core\Database;
use App\Models\Dano;
use App\Models\Marca;
use App\Models\Revisao;
use App\Models\Usuario;
use App\Models\Veiculo;
use MongoDB\BSON\UTCDateTime;

echo "Criando dados de exemplo em pi_cartrix...\n";

Database::getDatabase()->drop();
echo "  banco resetado.\n";

$marcas = ['Chevrolet', 'Fiat', 'Honda', 'Hyundai', 'Renault', 'Toyota', 'Volkswagen','Jeep','BYD',
    'Nissan', 'Chery', 'Ford', 'GWM', 'Citroën', 'RAM', 'Mitsubishi','Peugeot', 'Audi', 'BMW','Mercedes-Benz',
    'Volvo', 'Porsche', 'Kia', 'Subaru', 'Suzuki', 'Hyundai'];
foreach ($marcas as $marca) {
    Marca::insert(['nome' => $marca]);
    echo "  marca: $marca\n";
}
echo '  marcas: ' . count($marcas) . " inseridas.\n";

Usuario::create('11222333000181', 'demo@cartrix.com', 'demo1234', 'Cartrix Demo');
echo "  usuario: demo@cartrix.com / demo1234\n";

$specs = [
    ['Toyota', 'Corolla XEi 2.0', 'ABC1D23', '9BWZZZ377VT004251', 45230, 'disponivel'],
    ['Fiat', 'Uno Way 1.0', 'XRT2E44', '9BG139BB6PD123456', 81200, 'em_manutencao'],
    ['Volkswagen', 'Golf GTI', 'PLK9A11', '3VWSX7B29JM456789', 62740, 'em_revisao'],
    ['Honda', 'Civic Touring', 'MNO8D55', '93HFC8480RZ401234', 18300, 'disponivel'],
    ['Renault', 'Duster 1.6', 'KJH3F66', '9BDBB2A0KF078901', 109500, 'danificado'],
    ['Chevrolet', 'Onix Premier', 'QWE1G77', '9BGFS44E1PC345678', 27450, 'indisponivel'],
];

$vehicleIds = [];
foreach ($specs as [$marca, $modelo, $placa, $chassi, $km, $status]) {
    $vehicleIds[] = Veiculo::create([
        'marca' => $marca,
        'modelo' => $modelo,
        'placa' => $placa,
        'chassi' => $chassi,
        'quilometragem' => $km,
        'status' => $status,
        'danos' => [],
    ]);
    echo "  veiculo: $placa ($marca $modelo) [$status]\n";
}

$dp = fn (string $date): UTCDateTime => new UTCDateTime(strtotime($date) * 1000);

$revisoes = [
    [$vehicleIds[0], '2026-08-01', 45230, 'troca_oleo', 'Substituição de óleo do motor e filtro de ar.', 'Filtro de óleo, óleo 5W30 sintético', 320.50, 'Carlos Mecânico'],
    [$vehicleIds[0], '2026-07-05', 43800, 'preventiva', 'Revisão preventiva completa.', 'Pastilhas de freio, velas de ignição', 890.00, 'Oficina Central'],
    [$vehicleIds[1], '2026-08-12', 81200, 'corretiva', 'Troca do alternador após pane elétrica.', 'Alternador 90A', 1350.75, 'Auto Elétrica Silva'],
    [$vehicleIds[2], '2026-08-20', 62740, 'revisao_programada', 'Revisão de 60.000 km programada pela montadora.', 'Kit de correia dentada, bomba d\'água', 2140.00, 'Concessionária VW'],
    [$vehicleIds[4], '2026-07-28', 109500, 'troca_pneus', 'Troca dos quatro pneus e alinhamento.', '4× pneu 215/65R16, alinhamento', 1780.00, 'Pneu Center'],
];

foreach ($revisoes as [$vid, $data, $km, $tipo, $desc, $pecas, $valor, $resp]) {
    Revisao::create([
        'vehicle_id' => $vid,
        'data_revisao' => $dp($data),
        'quilometragem' => $km,
        'tipo' => $tipo,
        'descricao' => $desc,
        'pecas_substituidas' => $pecas,
        'valor' => $valor,
        'responsavel' => $resp,
    ]);
}
echo '  revisoes: ' . count($revisoes) . " inseridas.\n";

$danos = [
    [$vehicleIds[1], '2026-08-18', 'media', 'lateral_direita', 'Arranhão profundo na porta do passageiro e quebra da maçaneta.', 'Aguardando orçamento da funilaria.'],
    [$vehicleIds[4], '2026-08-22', 'alta', 'dianteira', 'Para-choque dianteiro danificado após colisão.', 'Veículo fora de operação.'],
    [$vehicleIds[4], '2026-06-10', 'baixa', 'traseira', 'Pequeno amassado na tampa do porta-malas.', 'Sem risco estrutural.'],
    [$vehicleIds[5], '2026-08-25', 'critica', 'teto', 'Danos estruturais graves no teto.', 'Avaliar possibilidade de sucateamento.'],
];

foreach ($danos as [$vid, $data, $grav, $loc, $desc, $obs]) {
    Dano::create([
        'vehicle_id' => $vid,
        'descricao' => $desc,
        'data' => $dp($data),
        'gravidade' => $grav,
        'localizacao' => $loc,
        'observacoes' => $obs,
    ]);
}
echo '  danos: ' . count($danos) . " inseridos.\n";

echo "\nConcluido. Login: demo@cartrix.com / senha demo1234 (CNPJ 11.222.333/0001-81)\n";
<?php
use App\Models\Veiculo;
?>
<span class="badge badge-<?= e((string) $status) ?>"><?= e(Veiculo::STATUSES[$status] ?? $status) ?></span>

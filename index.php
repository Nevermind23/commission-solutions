<?php

use Nevermind23\CommissionSolutions\Service\Commission;
use Nevermind23\CommissionSolutions\Service\Csv;

require __DIR__.'/vendor/autoload.php';

$csvService = new Csv();
$commissionService = new Commission();

$filePath = $argv[1];
$data = $csvService->toArray($filePath);
$commissions = $commissionService->process($data);

foreach ($commissions as $commission) {
    echo $commission . "\n";
}

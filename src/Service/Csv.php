<?php

declare(strict_types=1);

namespace Nevermind23\CommissionSolutions\Service;

use Exception;

class Csv
{
    /**
     * @throws Exception
     */
    public function toArray(string $path): array
    {
        $data = [];
        $csv = fopen($path, 'r');

        if (!$csv) {
            throw new Exception("File doesn't exist");
        }

        while (($line = fgetcsv($csv)) !== false) {
            $data[] = $line;
        }

        return $data;
    }
}

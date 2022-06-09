<?php

namespace Nevermind23\CommissionSolutions\Tests;

use PHPUnit\Framework\TestCase;
use Nevermind23\CommissionSolutions\Service\Commission;

final class CommissionTest extends TestCase
{
    private $commission;

    public function setUp()
    {
        $this->commission = new Commission();
    }

    /**
     * @param array $data
     * @param array $result
     *
     * @dataProvider dataProviderForProcessTesting
     */
    public function testProcess(array $data, array $result)
    {
        $commissions = $this->commission->process($data);

        foreach ($commissions as $key => $commission) {
            $this->assertEquals($result[$key], $commission);
        }
    }

    public function dataProviderForProcessTesting(): array
    {
        return [
            "sample data" => [
                [
                    ["2014-12-31", 4, "private", "withdraw", 1200.00, "EUR"],
                    ["2015-01-01", 4, "private", "withdraw", 1000.00, "EUR"],
                    ["2016-01-05", 4, "private", "withdraw", 1000.00, "EUR"],
                    ["2016-01-05", 1, "private", "deposit", 200.00, "EUR"],
                    ["2016-01-06", 2, "business", "withdraw", 300.00, "EUR"],
                    ["2016-01-06", 1, "private", "withdraw", 30000, "JPY"],
                    ["2016-01-07", 1, "private", "withdraw", 1000.00, "EUR"],
                    ["2016-01-07", 1, "private", "withdraw", 100.00, "USD"],
                    ["2016-01-10", 1, "private", "withdraw", 100.00, "EUR"],
                    ["2016-01-10", 2, "business", "deposit", 10000.00, "EUR"],
                    ["2016-01-10", 3, "private", "withdraw", 1000.00, "EUR"],
                    ["2016-02-15", 1, "private", "withdraw", 300.00, "EUR"],
                    ["2016-02-19", 5, "private", "withdraw", 3000000, "JPY"]
                ],
                [
                    0.6,
                    3,
                    0,
                    0.06,
                    1.5,
                    0,
                    0.7,
                    0.3,
                    0.3,
                    3,
                    0,
                    0,
                    8611.41
                ]
            ]
        ];
    }
}
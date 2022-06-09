<?php

declare(strict_types=1);

namespace Nevermind23\CommissionSolutions\Service;

class Commission
{
    private $config;

    public function __construct()
    {
        $this->config = json_decode(file_get_contents(__DIR__.'/../../config.json'));
    }

    public function process(array $items): array
    {
        $users = [];
        $commissions = [];

        foreach ($items as $item) {
            $commission = $this->config->commissions->{$item[3]}->{$item[2]} / 100;
            $amountToCharge = $item[4];

            if ($item[2] === 'private' && $item[3] === 'withdraw') {
                // Getting year+week to match transactions made in same week.
                $week = date('oW', strtotime($item[0]));

                $amount = $item[4];
                $currency = strtolower($item[5]);

                // If currency is different from our base currency, we are converting it
                if ($currency !== $this->config->currency) {
                    $amount = $amount / $this->config->rates->{$currency};
                }

                // Storing withdrawals with same user_id and made in same week in a single array
                $users[$item[1]][$week][] = $amount;

                if (count($users[$item[1]][$week]) <= 3) {
                    $totalWithdrawn = array_sum($users[$item[1]][$week]);
                    if ($totalWithdrawn <= $this->config->commission_free_amount) {
                        $amountToCharge = 0.00;
                        $commission = 0.00;
                    } else {
                        // Checking if totalWithdrawn - commission_free_amount < currentWithdrawal,
                        // if so, we subtract commission_free_amount then check if it needs currency
                        // conversion and assign appropriate value to the $changeableAmount
                        if ($totalWithdrawn - $this->config->commission_free_amount < $item[4]) {
                            $chargeableAmount = $totalWithdrawn - $this->config->commission_free_amount;

                            // If currency is different, we are converting it back to original
                            if ($currency !== $this->config->currency) {
                                $chargeableAmount = $chargeableAmount * $this->config->rates->{$currency};
                            }
                        } else {
                            $chargeableAmount = $item[4];
                        }
                        $amountToCharge = $chargeableAmount;
                    }
                }
            }

            $commissions[] = round_up($amountToCharge * $commission, 2);
        }

        return $commissions;
    }
}

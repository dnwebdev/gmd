<?php

namespace App\Traits;

use App\Models\JournalGXP;


trait GxpTrait
{
    /**
     * Trait function to get my current GXP
     */
    public function mygxp()
    {
        if (auth('web')->check()) {
            $company = auth('web')->user()->company;
            $totalIn = JournalGXP::whereIdCompany($company->id_company)->where('type', 'in')->sum('nominal');
            $totalOut = JournalGXP::whereIdCompany($company->id_company)->where('type', 'out')->sum('nominal');
            $saldoGXP = $totalIn - $totalOut;
            $result = [
                'error' => false,
                'gxp' => $saldoGXP,
                // 'gxp_format' => $saldoGXP,
                // 'gxp_format' => format_priceID($saldoGXP, 'IDR')
            ];
            return $result;
        }
        $result = [
            'error' => true,
            'message' => 'Unauthenticated',
        ];
        return $result;
    }
}

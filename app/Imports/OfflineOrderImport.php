<?php

namespace App\Imports;

use App\Models\Hhbk;
use App\Models\OfflineOrder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OfflineOrderImport implements ToCollection, WithHeadingRow
{
    use Importable;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $routeAs = request()->route()->getAction('as');
        $productAbleType = null;
        $productModel = null;
        $client = null;
        $channel = 'distribution';
        switch ($routeAs) {
            case "admin:hhbk-distribution.import":
                $productAbleType = Hhbk::class;
                $productModel = Hhbk::query();
                break;
        }
        foreach ($collection as $row) {
            $productId = null;
            $type = null;
            $p = null;
            if ($productModel):
                if ($p = $productModel->where('id', $row['kode_barang'])->first()):
                    $productId = $p->id;
                    $type = $productAbleType;
                    return
                        OfflineOrder::create([
                            'productable_id' => $productId,
                            'productable_type' => $type,
                            'company_id' => $p ? $p->company ? $p->company->id_company : null : null,
                            'product_name' => $p->product_name,
                            'amount' => $row['harga'],
                            'channel' => $channel,
                            'client' => $row['distribusi_ke']
                        ]);
                endif;
            endif;
           return null;
        }
    }
}

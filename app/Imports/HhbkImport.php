<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Hhbk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HhbkImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        $company_id = null;
        if (!empty($row['domain_gomodo'])) :
            $company = Company::where('domain_memoria', $row['domain_gomodo'])->first();
            if ($company) {
                $company_id = $company->id_company;
            }
        endif;
        if (isset(
            $row['kode_barang'],
            $row['kota'],
            $row['nama_produk']
        )):
            return Hhbk::updateOrCreate(
                [
                    'id' => $row['kode_barang']
                ],
                [
                    'city' => $row['kota'],
                    'product_name' => $row['nama_produk'],
                    'company_id' => $company_id,
                    'product_description' => $row['deskripsi_produk'],
                    'product_detail' => $row['detail_produk']
                ]);
        endif;
    }
}

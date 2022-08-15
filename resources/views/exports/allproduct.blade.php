<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Created At</th>
        <th>
            Nama Produk
        </th>
        <th>
            URL Produk
        </th>
        <th>
            Nama Provider
        </th>
        <th>
            URL Provider
        </th>
        <th>
            Deskripsi Singkat Produk
        </th>
        <th>
            Kota
        </th>
        <th>
            Provinsi
        </th>
        <th>
            Harga
        </th>
        <th>
            Tanggal Ketersedian
        </th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @php
        $number = 1;
        $chunk=100;
            $products->chunk($chunk,function ($prods,$index) use ($number,$chunk){
            foreach($prods as $product){
    @endphp
    <tr>
        <td>{{ ($chunk*($index-1)) + $number++ }}</td>
        <td>{{ ($product->created_at->format('d M Y H:i:s')}}</td>
        <td>{{ $product->product_name }}</td>
        <td>http{{ env('HTTPS', false) == true ? 's' : '' }}://{{ $product->company->domain_memoria }}/product/detail/{{ $product->unique_code }}</td>
        <td>{{ $product->company->company_name }}</td>
        <td>http{{ env('HTTPS', false) == true ? 's' : '' }}://{{ $product->company->domain_memoria }}</td>
        <td>{{ $product->brief_description }}</td>
        @if($product->city)
            <td>{{ optional($product->city)->city_name }}</td>
            <td>{{ optional($product->city->state)->state_name }}</td>
        @else
            <td>-</td>
            <td>-</td>
        @endif
        <td>{{ $product->pricing->map(function ($value) {
                $return = $value->price_from;
                if ($value->price_until > $value->price_from) {
                    $return .= '- '.$value->price_until;
                }
                if ($value->unit):
                $return .= ' '.$value->unit->name_id;
                endif;
                $return .= ' Rp. '.number_format($value->price, 0, ',', '.');

                return $return;
            })->implode(', ') }}</td>
        <td>
            {{ optional($product->first_schedule)->start_date->format('d/m/Y') }}
            @if (optional($product->first_schedule)->end_date != optional($product->first_schedule)->start_date)
                - {{ optional($product->first_schedule)->end_date->format('d/m/Y') }}
            @endif
        </td>
        <td>
            @if (optional($product->first_schedule)->end_date->isPast())
                Expired
            @else
                {{ $product->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
            @endif
        </td>
    </tr>

    @php
        }
            });
    @endphp

    </tbody>
</table>

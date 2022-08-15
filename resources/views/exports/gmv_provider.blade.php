<table>
    <thead>
    <tr>
{{--        <th>No</th>--}}
        <th>
            Nama Perusahaan
        </th>
        <th>
            Alamat
        </th>
        <th>
            PIC
        </th>
        <th>
            Email
        </th>
        <th>
            Phone
        </th>
        <th>
            Status Perusahaan
        </th>
        <th>
            Nama Produk
        </th>
        <th>
            Harga
        </th>
        <th>
            GMV Per Produk
        </th>
        <th>Link Web</th>
        <th>Status Produk</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products->chunk(3) as $chunk)
        @foreach($chunk as $product)
        <tr>
{{--            <td>{{ $loop->iteration }}</td>--}}
            <td>{{ $product->company->company_name }}</td>
            <td>{{ $product->company->address_company }}</td>
            <td>{{ $product->company->agent->first_name }}</td>
            <td>{{ $product->company->agent->email }}</td>
            <td>{{ $product->company->agent->phone }}</td>
            <td>{{ $product->company->status == 1 ? 'Aktif' : 'Banned' }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->pricing->map(function ($value) {
                $return = $value->price_from;
                if ($value->price_until > $value->price_from) {
                    $return .= '- '.$value->price_until;
                }
                $return .= ' '.$value->unit->name_id;
                $return .= ' Rp. '.number_format($value->price, 0, ',', '.');

                return $return;
            })->implode(', ') }}</td>
            <td>{{ $product->sumOrder() }}</td>
            <td>http{{ env('HTTPS', false) == true ? 's' : '' }}://{{ $product->company->domain_memoria }}</td>
            <td>
                @if (optional($product->first_schedule)->end_date->isPast())
                    Expired
                @else
                    {{ $product->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                @endif
            </td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

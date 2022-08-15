<table>
    <thead>
    <tr>
        <th>No</th>
        <th>
            Nama Operator
        </th>
        <th>
            Nama Produk
        </th>
        <th>
            Lokasi
        </th>
        <th>
            Status
        </th>
        <th>
            Total di Pesan
        </th>

    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ $product->company->company_name }}</th>
            <th>{{ $product->product_name }}</th>
            <th>{{ optional($product->city)->city_name }}, {{ optional($product->city->state)->state_name }}</th>
            <th>{{ $product->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</th>
            <th>{{ $product->orders }}/{{ $product->max_people }}</th>
        </tr>
    @endforeach
    </tbody>
</table>

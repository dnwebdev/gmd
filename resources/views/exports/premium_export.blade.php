<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Date</th>
        <th>Company Name</th>
        <th>PIC</th>
        <th>Kategori Iklan</th>
        <th>Payment Gateway</th>
        <th>Voucher</th>
        <th>Voucher By Gomodo</th>
        <th>Gxp</th>
        <th>Fee Credit Card</th>
        <th>Service Fee</th>
        <th>Sub Total</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($premium as $data)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $data->created_at }}</td>
            <td>{{ $data->adsOrder->company->company_name }}</td>
            <td>{{ $data->adsOrder->company->agent->first_name }}</td>
            <td>{{ $data->category_ads }}</td>
            <td>{{ $data->payment_gateway }}</td>
            <td>{{ $data->voucher == null ? 0 : $data->voucher }}</td>
            <td>{{ optional($data->voucherAds)->nominal }}</td>
            <td>{{ $data->gxp_amount == null ? 0 : $data->gxp_amount }}</td>
            <td>{{ $data->fee_credit_card == null ? 0 : $data->fee_credit_card }}</td>
            <td>{{ $data->adsOrder->service_fee == null ? 0 : $data->adsOrder->service_fee }}</td>
            <td>{{ $data->amount }}</td>
            <td>{{ $data->total_price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

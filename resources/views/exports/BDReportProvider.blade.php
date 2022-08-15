<table>
    <thead>
    <tr>
        <th>Register At</th>
        <th>Company Name</th>
        <th>Company Address</th>
        <th>Company City</th>
        <th>Owner Name</th>
        <th>Phone Company</th>
        <th>Email Company</th>
        <th>Domain Company</th>
       <th>GMV</th>
       <th>Status</th>
    </tr>
    </thead>
    <tbody>

    @php

        use App\Models\Company;Company::withoutGlobalScope(\App\Scopes\ActiveProviderScope::class)->latest()->chunk(10, function ($c) {
                foreach ($c as $company){
    @endphp
    <tr>
        <td>{{$company->created_at}}</td>
        <td>{{$company->company_name}}</td>
        <td>{{strip_tags($company->address_company)}}</td>
        <td>{{$company->city->city_name ?? '-'}}</td>
        <td>{{$company->agent->first_name.' '.$company->agent->last_name}}</td>
        <td>{{$company->phone_company}}</td>
        <td>{{$company->email_company}}</td>
        <td>{{$company->domain_memoria}}</td>
        <td>{{$company->paid_order()}}</td>
        <td>{{$company->status=='1'?"Active":"Non Active"}}</td>
    </tr>
    @php
        }
          });
    @endphp

    </tbody>
</table>

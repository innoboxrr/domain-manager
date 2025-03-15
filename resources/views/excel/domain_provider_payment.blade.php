<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_provider_payments as $domain_provider_payment)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_provider_payment->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
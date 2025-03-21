<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_payment_methods as $domain_payment_method)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_payment_method->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
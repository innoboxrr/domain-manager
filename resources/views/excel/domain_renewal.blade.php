<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_renewals as $domain_renewal)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_renewal->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
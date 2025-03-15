<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_dns as $domain_dns)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_dns->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
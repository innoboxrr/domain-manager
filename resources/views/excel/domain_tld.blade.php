<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_tlds as $domain_tld)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_tld->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
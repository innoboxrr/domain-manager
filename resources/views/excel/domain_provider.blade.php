<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_providers as $domain_provider)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_provider->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
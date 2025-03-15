<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domains as $domain)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
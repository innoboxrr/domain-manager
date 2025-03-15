<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_contacts as $domain_contact)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_contact->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
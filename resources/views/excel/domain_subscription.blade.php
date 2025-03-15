<table>
    <thead>
        <tr>
            @foreach($exportCols as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($domain_subscriptions as $domain_subscription)
            <tr>
                @foreach($exportCols as $col)
                    <td>{{ $domain_subscription->$col }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
<table class="table table-sm table-bordered">
    <tr>
        <th>Activity By</th>
        <td>{{$log->user->name ?? 'n/a'}}</td>
    </tr>
    <tr>
        <th>Date</th>
        <td>{{get_date($log->created_at)}}</td>
    </tr>
    <tr>
        <th>IP</th>
        <td>{{$log->ip}}</td>
    </tr>
    <tr>
        <th>Platform</th>
        <td>{{$log->platform}}</td>
    </tr>
    <tr>
        <th>User Agent</th>
        <td><small>{{$log->user_agent}}</small></td>
    </tr>
</table>
<table class="table table-sm table-bordered">
    <tr>
        <th>Activity</th>
    </tr>
    <tr>
        <td>{{$log->activity}}</td>
    </tr>
</table>
<table class="table table-sm table-bordered">
    <tr>
        <th>Data</th>
    </tr>
    <tr>
        <td>
            <pre class="code">{{json_encode(json_decode($log->data), JSON_PRETTY_PRINT)}}</pre>
        </td>
    </tr>
</table>
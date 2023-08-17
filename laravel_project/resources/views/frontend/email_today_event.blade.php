<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 50%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
</head>

<body>

    <h2>Coaches List</h2>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Event description</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Event URL</th>
        </tr>

        @foreach ($today_events as $event)
            <tr>
                <td>{{ $event->event_name }}</td>
                <td>{{ $event->event_description }}</td>
                <td>{{ $event->event_start_date }} {{ $event->event_start_hour }}</td>
                <td>{{ $event->event_start_date }} {{ $event->event_end_hour }}</td>
                <td>{{ $event->event_social_url }}</td>
            </tr>
        @endforeach

    </table>


</body>

</html>
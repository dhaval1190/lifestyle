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

    <h2>Contact Coach Mail</h2>

    <table>
        <tr>
            <th>Details</th>
            {{-- <th>URL</th>
            <th>Note</th> --}}

        </tr>

        <tr>
            <td>{!! $message_content !!}</td>
            {{-- <td>{{ $url }}</td>
            <td>{{ $note }}</td> --}}

        </tr>

    </table>

</body>

</html>

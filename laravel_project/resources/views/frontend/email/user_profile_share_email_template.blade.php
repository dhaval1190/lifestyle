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

    <h2>Profile Share</h2>

    <table cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr>
            <th style="font-size: 10px; line-height:30px; width:100%;">Details</th>
            {{-- <th>URL</th>
            <th>Note</th> --}}

        </tr>

        <tr>
            <td style="font-size: 10px; line-height:30px; width:100%;">{!! $message_content !!}</td>
            {{-- <td>{{ $url }}</td>
            <td>{{ $note }}</td> --}}

        </tr>

    </table>

</body>

</html>

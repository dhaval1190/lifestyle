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
          <th>Name</th>
          <th>Email</th>
          <th>Created on</th>
          <th>Is email verified</th>
      </tr>

      @foreach ($coaches as $coach)
          <tr>
              <td>{{ $coach->name }}</td>
              <td>{{ $coach->email }}</td>
              <td>{{ $coach->created_at }}</td>
              <td>{{ $coach->email_verified_at == null ? 'Not verified' : 'Verified' }}</td>
          </tr>
      @endforeach

  </table>


  <h2>Users List</h2>
  <table>
      <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Created on</th>
          <th>Is email verified</th>
      </tr>

      @foreach ($users as $user)
          <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $coach->created_at }}</td>
              <td>{{ $coach->email_verified_at == null ? 'Not verified' : 'Verified' }}</td>
          </tr>
      @endforeach

  </table>

</body>
</html>
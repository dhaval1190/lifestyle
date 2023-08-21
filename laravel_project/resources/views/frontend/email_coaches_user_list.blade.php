<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .coaches_user_template {
            max-width: 1200px;
            margin: auto;
            border: 1px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
            font-family: 'Roboto', sans-serif;
        }

        .coaches_user_template table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .coaches_user_template table tr.heading td {
            /* background: #eee; */
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .coaches_user_template td {
            padding: 10px 4px;
            vertical-align: top;
        }
        .coaches_user_template table tr.border-set{
            border-bottom: 1px solid #000 !important;
        }
    </style>
</head>

<body>

    <div class="coaches_user_template">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="5" style="font-size: 24px; font-weight: 600; padding-bottom:10px; 
                color: #000; letter-spacing: -1px;  line-height: 2; vertical-align: top; text-align: center; border: 1px solid #000; 
                ">
                    Coaches List
                </td>
            </tr>

            <tbody>
                <tr class="heading">
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; font-weight: 700; text-align: center;">
                        Name
                    </td>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; font-weight: 700; width: 100px; text-align: center;">
                        Email
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
                
              ">
                        Created on
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
                
              ">
                        Is email verified
                    </td>

                </tr>
                @foreach ($coaches as $coach)
                <tr>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; text-align: center; width:250px; border-bottom: 1px solid #ddd;">
                        {{ $coach->name }}
                    </td>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif;width:250px;border-bottom: 1px solid #ddd; text-align:center">
                        <a href="harsh.modi@pranshtech.com;">{{ $coach->email }}</a>
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;width:250px;border-bottom: 1px solid #ddd;
              ">
                        {{ $coach->created_at }}
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;width:250px;border-bottom: 1px solid #ddd;
              ">
                        {{ $coach->email_verified_at == null ? 'Not verified' : 'Verified' }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!--User list -->

    <div class="coaches_user_template">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="5" style="font-size: 21px; font-weight: 600; padding-bottom:20px; 
                color: #000; letter-spacing: -1px;  line-height: 1; vertical-align: top; text-align: center; padding-top:10px;
                ">
                    Users List
                </td>
            </tr>

            <tbody>
                <tr class="heading">
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; font-weight: 700; text-align: center;">
                        Name
                    </td>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; font-weight: 700; width: 100px; text-align: center;">
                        Email
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
                
              ">
                        Created on
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;
                font-weight: 700;
                
              ">
                        Is email verified
                    </td>

                </tr>
                @foreach ($users as $user)
                <tr> 
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; width:250px; border-bottom: 1px solid #ddd;text-align: center;">
                        {{ $user->name }}
                    </td>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; width:250px; border-bottom: 1px solid #ddd; text-align: center;">
                        <a href="harsh.modi@pranshtech.com;">{{ $user->email }}</a>
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;
                width:250px;border-bottom: 1px solid #ddd;
              ">
                        {{ $user->created_at }}
                    </td>
                    <td style="
                text-align: center;
                font-size: 15px;
                font-family: 'Roboto', sans-serif;
                width:250px; border-bottom: 1px solid #ddd;
              ">
                        {{ $user->email_verified_at == null ? 'Not verified' : 'Verified' }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



</body>

</html>
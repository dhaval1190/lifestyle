<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changes Your Password</title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .bg {
            background: #edf2f7;
        }

        .invoice-box {
            max-width: 535px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background: #fff;
        }
    </style>
</head>

<body>
    <div class="bg">
        <table cellpadding="0" cellspacing="0" style="width: 100%;">
            <tr>
                <h1 style="text-align: center; padding: 25px 0px; width: 100%;">
                    Welcome to our community!
                </h1>

            </tr>
        </table>
        <div class="invoice-box">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <p style="padding:10px 0px; font-weight: bold;">Hello!</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <p>Please click the button below to change your password.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table cellpadding="0" cellspacing="0" style="margin: auto; padding: 22px;">
                <tbody>
                    <tr>
                        <td
                            style="background: #2d3748; color: #fff; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748; display: inline-block;">
                            <a href="{{ $user_details['url'] }}" style="color: #fff; text-decoration: none;">Change Your Password</a>
                        </td>
                    </tr>
                </tbody>
            </table>
           
            <table>
                <tbody>
                    <tr>
                        <td>
                            <p style="padding-top: 10px;">Regards,</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td>
                            <p style="padding: 0px 0px;">The CoachesHQ Family
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
           
        </div>
        <table cellpadding="0" cellspacing="0" style="margin: auto;">
            <tbody>
                <tr>
                    <td style="padding: 32px;">
                        <p style="text-align: center; color: #b0adc5; font-size: 12px; text-align: center">© {{ date('Y') }} Coach
                            Directory. All rights reserved</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

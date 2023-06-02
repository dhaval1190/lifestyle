<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Share</title>

    <style>
        * {
          margin: 0;
          padding: 0;
        }
        .bg {
          background: #edf2f7;
        }
        .invoice-box {
          max-width: 420px;
          margin: auto;
          padding: 30px;
          border: 1px solid #eee;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
          font-size: 16px;
          line-height: 24px;
          font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
          color: #555;
          background: #fff;
        }
        /* .invoice-box tr td{
          padding: 7px 0px;
        } */
        .invoice-box tr td .ans{
          padding: 0px 25px;
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
                    <td>{!! $message_content !!}</td>
                  </td>
                </tr>
              </tbody>
            </table>
    
            <table cellpadding="0" cellspacing="0" style="margin: auto; padding: 22px;">
                <tbody>
                    <tr>
                        <td style="background: #48bb78; color: #fff; border-bottom: 8px solid #48bb78; border-left: 18px solid #48bb78; border-right: 18px solid #48bb78; border-top: 8px solid #48bb78; display: inline-block;">
                            <a href="{{ $url }}" style="color: #fff; text-decoration: none;">View Article</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td>
                            <p>Regards,</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td>
                            <p style="padding-bottom: 5px 0px; margin-bottom: 20px;">The CoachesHQ Family
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
    
            <table style="border-top: 1px solid #e8e5ef;">
                <tbody>
                    <tr>
                        <td>
                            <p style="padding: 20px 0px;">If you're having trouble clicking the "View Coach" button, copy and paste the URL below into your web browser:
                            <span>
                                <a href="{{ $url }}" target="_blank">{{ $url }}</a>
                            </span>
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
                        <p style="text-align: center; color: #b0adc5; font-size: 12px; text-align: center">© {{ $year }} Coach Directory. All rights reserved</p>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>   
        



    </div>
</body>
</html>
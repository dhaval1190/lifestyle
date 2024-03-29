
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
   
    <style>
      * {
        margin: 0;
        padding: 0;
      }
      .bg {
        background: #edf2f7;
      }
      .invoice-box {
        max-width: 730px;
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
      .invoice-box tr td p{
        padding: 7px 0px;
      }
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
        <table style="width:100%">
          <tbody>
            <tr>
              <td style="font-size: 15px; line-height:30px; width:100%;">
                <p style="padding: 10px 0px; font-weight: bold">
                  Email: {{$email_from}}
                </p>
                <p style="padding: 10px 0px; font-weight: bold">
                Message: {{$note}}
                </p>
                <p style="padding: 10px 0px; font-weight: bold">
                User Type:  {{$user_type}}
                </p>
              </td>
            </tr>           
          </tbody>
        </table>       
        <table>
          <tbody>
            <tr>
              <td style="font-size: 15px; line-height:30px; width:100%;">
                <p style="padding-top: 10px;">Regards,</p>
              </td>
            </tr>
          </tbody>
        </table>

        <table>
          <tbody>
            <tr>
              <td style="font-size: 15px; line-height:30px; width:100%;">
                <p style="padding: 0px 0px;">The CoachesHQ Family</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <table cellpadding="0" cellspacing="0" style="margin: auto">
        <tbody>
          <tr>
            <td style="padding: 32px">
              <p
                style="
                  text-align: center;
                  color: #b0adc5;
                  font-size: 12px;
                  text-align: center;
                ">
                © {{ $year }} Coach Directory. All rights reserved
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </body>
</html>
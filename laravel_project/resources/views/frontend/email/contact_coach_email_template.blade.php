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
                    {!! $message_content !!}
                </p>
              </td>
            </tr>
            <tr>
             <td style="text-align: center"><a href="{{ $url }}">
              <button type="button" style="
              border-radius: 4px;
              color: #fff;
              display: inline-block;
              overflow: hidden;
              text-decoration: none;
              background-color: #48bb78;
              border-bottom: 8px solid #48bb78;
              border-left: 18px solid #48bb78;
              border-right: 18px solid #48bb78; 
              border-top: 8px solid #48bb78;cursor:pointer;">{{ __('View') }}</button>
            </a></td>
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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <link rel="stylesheet" href="coache.css" />
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }

    .coaches_template {
        max-width: 1200px;
        margin: auto;
        padding: 30px 30px 30px 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15) ;
        font-size: 16px;
        line-height: 24px;
        color: #555;
        font-family: 'Roboto', sans-serif;
        
        
    }

    .coaches_template table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .coaches_template table tr.heading td {
        /* background: #eee; */
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .coaches_template td {
        padding: 10px 4px;
        vertical-align: top;
    }
</style>

<body>

    <div class="coaches_template" style="max-width: 1200px;
    margin: auto;
    padding: 20px 0px 30px 0px;
    border: 1px solid #eee;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15) !important ;
    font-size: 16px;
    line-height: 24px;
    color: #555;
    font-family: 'Roboto', sans-serif;">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td colspan="5" style="font-size: 21px; font-weight: 600; padding-bottom: 20px; 
                    color: #000; letter-spacing: -1px;  line-height: 1; vertical-align: top; text-align: center; border-bottom: 1px solid #ddd; 
        
                    ">
                    Today's events
                </td>
            </tr>
            @foreach($today_events as $event)
            <tbody>
                <tr class="heading">
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; font-weight: 700; width: 200px; padding: 20px 20px;">
                        Event Name
                    </td>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; font-weight: 700; padding: 20px 20px;">
                        Event description
                    </td>
                    <td style="
                    text-align: center;
                    font-size: 15px;
                    font-family: 'Roboto', sans-serif;
                    font-weight: 700;
                    width: 200px;
                    padding: 20px 20px;
                ">
                        Start time
                    </td>
                    <td style="
                    text-align: center;
                    font-size: 15px;
                    font-family: 'Roboto', sans-serif;
                    font-weight: 700;
                    width: 200px;
                    padding: 20px 20px;
                ">
                        End time
                    </td>
                    <td style="
                    text-align: center;
                    font-size: 15px;
                    font-family: 'Roboto', sans-serif;
                    font-weight: 700;
                    width: 200px;
                    padding: 20px 20px;
                ">
                        Event URL
                    </td>
                </tr>

                <tr>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; border-bottom: 1px solid #ddd; padding: 20px 20px; ">
                        {{ $event->event_name }}
                    </td>
                    <td style="font-size: 15px; font-family: 'Roboto', sans-serif; border-bottom: 1px solid #ddd; padding: 20px 20px;">
                        {{ $event->event_description }}
                    </td>
                    <td style="
                    text-align: center;
                    font-size: 15px;
                    font-family: 'Roboto', sans-serif;
                    border-bottom: 1px solid #ddd;
                    padding: 20px 20px;
                ">
                        {{ $event->event_start_date }} {{ $event->event_start_hour }}
                    </td>
                    <td style="
                    text-align: center;
                    font-size: 15px;
                    font-family: 'Roboto', sans-serif;
                    border-bottom: 1px solid #ddd;
                    padding: 20px 20px;
                ">
                        {{ $event->event_start_date }} {{ $event->event_end_hour }}
                    </td>
                    <td style="
                    text-align: center;
                    font-size: 15px;
                    font-family: 'Roboto', sans-serif;
                    border-bottom: 1px solid #ddd;
                    padding: 20px 20px;
                ">
                        <a href="{{ $event->event_social_url }}">{{ $event->event_social_url }}</a>
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>
</body>

</html>
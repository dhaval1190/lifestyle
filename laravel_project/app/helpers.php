<?php

use App\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

if (! function_exists('get_item_slug')) {
    function get_item_slug()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';
        $item_slug = '';

        $item_slug_found = true;

        while($item_slug_found)
        {
            for ($i = 0; $i < 11; $i++)
            {
                $item_slug .= $characters[mt_rand(0, 63)];
            }

            $item_exist = \App\Item::where('item_slug', $item_slug)->count();
            if($item_exist == 0)
            {
                $item_slug_found = false;
            }
        }

        return $item_slug;
    }
}

if (! function_exists('site_already_installed')) {
    function site_already_installed()
    {
        return file_exists(storage_path('installed'));
    }
}

if (! function_exists('generate_symlink')) {
    function generate_symlink()
    {
        // create a storage symbolic link in public folder and website root before run installer
        $target = storage_path('app'. DIRECTORY_SEPARATOR .'public');
        $link = public_path('storage');
        $blog_link = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'storage';
        $public_storage = $link;
        $root_storage = $blog_link;

        $vendor_target = $_SERVER["DOCUMENT_ROOT"]. DIRECTORY_SEPARATOR . 'vendor';
        $vendor_link = public_path('vendor');
        $vendor_symlink = $vendor_link;


        if(file_exists($public_storage))
        {
            unlink($public_storage);
        }
        if(file_exists($root_storage))
        {
            unlink($root_storage);
        }
        if(file_exists($vendor_symlink))
        {
            unlink($vendor_symlink);
        }

        symlink($target, $link);
        symlink($target, $blog_link);
        symlink($vendor_target, $vendor_link);
    }
}

if (! function_exists('is_demo_mode')) {
    function is_demo_mode()
    {
        return env("DEMO_MODE", false);
    }
}

if (! function_exists('config_smtp')) {
    function config_smtp($from_name, $from_email, $smtp_host, $smtp_port, $smtp_encryption, $smtp_username, $smtp_password)
    {
        $encryption = null;
        if($smtp_encryption == Setting::SITE_SMTP_ENCRYPTION_SSL)
        {
            $encryption = Setting::SITE_SMTP_ENCRYPTION_SSL_STR;
        }
        elseif($smtp_encryption == Setting::SITE_SMTP_ENCRYPTION_TLS)
        {
            $encryption = Setting::SITE_SMTP_ENCRYPTION_TLS_STR;
        }

        config([
            'mail.host' => $smtp_host,
            'mail.port' => $smtp_port,
            'mail.from' => ['address' => $from_email, 'name' => $from_name],
            'mail.encryption' => $encryption,
            'mail.username' => $smtp_username,
            'mail.password' => $smtp_password,
        ]);
    }
}

if (! function_exists('config_re_captcha')) {
    function config_re_captcha($recaptcha_site_key, $recaptcha_secret_key)
    {
        config([
            'recaptcha.api_site_key' => $recaptcha_site_key,
            'recaptcha.api_secret_key' => $recaptcha_secret_key,
        ]);
    }
}

if (! function_exists('generate_website_route_cache')) {
    function generate_website_route_cache()
    {
        Artisan::call('route:cache');
    }
}

if (! function_exists('generate_website_view_cache')) {
    function generate_website_view_cache()
    {
        Artisan::call('view:cache');
    }
}

if (! function_exists('clear_website_cache')) {
    function clear_website_cache()
    {
        Artisan::call('optimize:clear');
    }
}

if (! function_exists('do_license_verify')) {
    function do_license_verify($url,
                               $codecanyon_purchase_code,
                               $codecanyon_username,
                               $call_function,
                               $revoke_domain=null)
    {
        try
        {
            $register_domain = $_SERVER['HTTP_HOST'];

            if(empty($register_domain))
            {
                return array(
                    'status' => false,
                    'message' => __('license_verify.helper-redirect-no-url'),
                    'body' => null,
                );
            }
            else
            {
                /**
                 * Start curl function
                 */
                $data = http_build_query(array(
                    'api_secret' => \App\SettingLicense::LICENSE_API_SECRET,
                    'register_domain' => $register_domain,
                    'purchase_code' => $codecanyon_purchase_code,
                    'codecanyon_username' => strtolower($codecanyon_username),
                    'call_function' => $call_function,
                    'revoke_domain' => $revoke_domain,
                ));

                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_POSTFIELDS => $data,
                ));

                // Send the request with warnings supressed
                $result = @curl_exec($ch);

                // Handle connection errors (such as an API outage)
                // You should show users an appropriate message asking to try again later
                if (curl_errno($ch) > 0) {

                    Log::error(curl_error($ch));

                    /* Handle error */
                    return array(
                        'status' => false,
                        'message' => __('license_verify.helper-redirect-unknown-error'),
                        'body' => null,
                    );
                }

                // handle curl error
                if ($result === false)
                {
                    /* Handle error */
                    return array(
                        'status' => false,
                        'message' => __('license_verify.helper-redirect-connect-fail'),
                        'body' => null,
                    );
                }

                $body = @json_decode($result);

                // Close cURL session handle
                curl_close($ch);

                return array(
                    'status' => true,
                    'message' => "",
                    'body' => $body,
                );
                /**
                 * End curl function
                 */
            }
        }
        catch (\Exception $e)
        {
            Log::error($e->getMessage() . "\n" . $e->getTraceAsString());

            return array(
                'status' => false,
                'message' => __('license_verify.helper-redirect-unknown-error'),
                'body' => null,
            );
        }
    }
}

if (! function_exists('get_domain_verify_token_url')) {
    function get_domain_verify_token_url($codecanyon_purchase_code, $codecanyon_username)
    {
        $url = \App\SettingLicense::LICENSE_API_HOST . \App\SettingLicense::LICENSE_API_ROUTE_DOMAIN_VERIFY_TOKEN;

        $register_domain = $_SERVER['HTTP_HOST'];

        $domain_host_hash = sha1($register_domain);
        $license_purchase_code_codecanyon_username_hash = sha1($codecanyon_purchase_code . strtolower($codecanyon_username));

        return $url . '?domain_host_hash=' . $domain_host_hash . '&license_purchase_code_codecanyon_username_hash=' . $license_purchase_code_codecanyon_username_hash;
    }
}

if (! function_exists('get_domain_verify_do_url')) {
    function get_domain_verify_do_url($codecanyon_purchase_code, $codecanyon_username)
    {
        $url = \App\SettingLicense::LICENSE_API_HOST . \App\SettingLicense::LICENSE_API_ROUTE_DOMAIN_VERIFY_DO;

        $register_domain = $_SERVER['HTTP_HOST'];

        $domain_host_hash = sha1($register_domain);
        $license_purchase_code_codecanyon_username_hash = sha1($codecanyon_purchase_code . strtolower($codecanyon_username));

        return $url . '?domain_host_hash=' . $domain_host_hash . '&license_purchase_code_codecanyon_username_hash=' . $license_purchase_code_codecanyon_username_hash;
    }
}
if (! function_exists('get_vincenty_great_circle_distance')) {

    /**
     * Calculates the great-circle distance between two points, with
     * the Vincenty formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function get_vincenty_great_circle_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

    function profileShareEmailTemplateConstants()
    {
        return [
            '[TO_EMAIL]',
            '[USER_NAME]',            
            '[NOTE]',
            '[URL]'
        ];
    }

    function contactCoachEmailTemplateConstants()
    {
        return [
            '[COACH_EMAIL]',
            '[YOUR_NAME]',
            '[YOUR_EMAIL]' ,
        ];
    }

    function userContactCoachEmailTemplateConstants()
    {
        return [
            '[COACH_EMAIL]',
            '[YOUR_NAME]',
            '[YOUR_EMAIL]' ,
            '[QUESTIONS]'
        ];
    }

    function getUserEventTimezone($utcDatetime)
    {
        if(Auth::check())
        {
            $time_zone =  Auth::user()->time_zone;
        }else{
            $time_zone = 'UTC';
        }
        
        // $utcDatetime = '2023-08-17 18:00:00'; // UTC datetime
        // $time_zone = 'America/New_York'; // Target timezone

        // Create a DateTime object for the UTC datetime
        $utcDateTimeObject = new \DateTime($utcDatetime, new \DateTimeZone('UTC'));

        // Set the target timezone
        $targetTimezone = new \DateTimeZone($time_zone);

        // Convert the UTC datetime to the target timezone
        $convertedDateTime = $utcDateTimeObject->setTimezone($targetTimezone);

        return $convertedDateTime->format('Y-m-d H:i:s');
    }

    function printErrorLogs($error_txt)
    {
        $txt = print_r($error_txt, true);
        $all_txt = date("Y-m-d H:i:s").':- '.$txt;
        
        $file=fopen(base_path().'/'.'public/storage/error_log/check_log.log','a');
        fwrite($file,$all_txt."\r\n");
        fclose($file);
    }

    function getAllTimezonesList()
    {
        return $all_time_zomnes = [
            "UTC",
            "America/New_York",
            "America/Los_Angeles",
            "America/Mexico_City",
            "America/Toronto",
            "America/Chicago",
            "America/Adak",
            "America/Anchorage",
            "America/Anguilla",
            "America/Antigua",
            "America/Araguaina",
            "America/Argentina/Buenos_Aires",
            "America/Argentina/Catamarca",
            "America/Argentina/Cordoba",
            "America/Argentina/Jujuy",
            "America/Argentina/La_Rioja",
            "America/Argentina/Mendoza",
            "America/Argentina/Rio_Gallegos",
            "America/Argentina/Salta",
            "America/Argentina/San_Juan",
            "America/Argentina/San_Luis",
            "America/Argentina/Tucuman",
            "America/Argentina/Ushuaia",
            "America/Aruba",
            "America/Asuncion",
            "America/Atikokan",
            "America/Bahia",
            "America/Bahia_Banderas",
            "America/Barbados",
            "America/Belem",
            "America/Belize",
            "America/Blanc-Sablon",
            "America/Boa_Vista",
            "America/Bogota",
            "America/Boise",
            "America/Cambridge_Bay",
            "America/Campo_Grande",
            "America/Cancun",
            "America/Caracas",
            "America/Cayenne",
            "America/Cayman",
            "America/Chihuahua",
            "America/Costa_Rica",
            "America/Creston",
            "America/Cuiaba",
            "America/Curacao",
            "America/Danmarkshavn",
            "America/Dawson",
            "America/Dawson_Creek",
            "America/Denver",
            "America/Detroit",
            "America/Dominica",
            "America/Edmonton",
            "America/Eirunepe",
            "America/El_Salvador",
            "America/Fort_Nelson",
            "America/Fortaleza",
            "America/Glace_Bay",
            "America/Goose_Bay",
            "America/Grand_Turk",
            "America/Grenada",
            "America/Guadeloupe",
            "America/Guatemala",
            "America/Guayaquil",
            "America/Guyana",
            "America/Halifax",
            "America/Havana",
            "America/Hermosillo",
            "America/Indiana/Indianapolis",
            "America/Indiana/Knox",
            "America/Indiana/Marengo",
            "America/Indiana/Petersburg",
            "America/Indiana/Tell_City",
            "America/Indiana/Vevay",
            "America/Indiana/Vincennes",
            "America/Indiana/Winamac",
            "America/Inuvik",
            "America/Iqaluit",
            "America/Jamaica",
            "America/Juneau",
            "America/Kentucky/Louisville",
            "America/Kentucky/Monticello",
            "America/Kralendijk",
            "America/La_Paz",
            "America/Lima",
            "America/Lower_Princes",
            "America/Maceio",
            "America/Managua",
            "America/Manaus",
            "America/Marigot",
            "America/Martinique",
            "America/Matamoros",
            "America/Mazatlan",
            "America/Menominee",
            "America/Merida",
            "America/Metlakatla",
            "America/Miquelon",
            "America/Moncton",
            "America/Monterrey",
            "America/Montevideo",
            "America/Montserrat",
            "America/Nassau",
            "America/Nipigon",
            "America/Nome",
            "America/Noronha",
            "America/North_Dakota/Beulah",
            "America/North_Dakota/Center",
            "America/North_Dakota/New_Salem",
            "America/Nuuk",
            "America/Ojinaga",
            "America/Panama",
            "America/Pangnirtung",
            "America/Paramaribo",
            "America/Phoenix",
            "America/Port-au-Prince",
            "America/Port_of_Spain",
            "America/Porto_Velho",
            "America/Puerto_Rico",
            "America/Punta_Arenas",
            "America/Rainy_River",
            "America/Rankin_Inlet",
            "America/Recife",
            "America/Regina",
            "America/Resolute",
            "America/Rio_Branco",
            "America/Santarem",
            "America/Santiago",
            "America/Santo_Domingo",
            "America/Sao_Paulo",
            "America/Scoresbysund",
            "America/Sitka",
            "America/St_Barthelemy",
            "America/St_Johns",
            "America/St_Kitts",
            "America/St_Lucia",
            "America/St_Thomas",
            "America/St_Vincent",
            "America/Swift_Current",
            "America/Tegucigalpa",
            "America/Thule",
            "America/Thunder_Bay",
            "America/Tijuana",
            "America/Tortola",
            "America/Vancouver",
            "America/Whitehorse",
            "America/Winnipeg",
            "America/Yakutat",
            "America/Yellowknife",
            "Asia/Aden",
            "Asia/Almaty",
            "Asia/Amman",
            "Asia/Anadyr",
            "Asia/Aqtau",
            "Asia/Aqtobe",
            "Asia/Ashgabat",
            "Asia/Atyrau",
            "Asia/Baghdad",
            "Asia/Bahrain",
            "Asia/Baku",
            "Asia/Bangkok",
            "Asia/Barnaul",
            "Asia/Beirut",
            "Asia/Bishkek",
            "Asia/Brunei",
            "Asia/Chita",
            "Asia/Choibalsan",
            "Asia/Colombo",
            "Asia/Damascus",
            "Asia/Dhaka",
            "Asia/Dili",
            "Asia/Dubai",
            "Asia/Dushanbe",
            "Asia/Famagusta",
            "Asia/Gaza",
            "Asia/Hebron",
            "Asia/Ho_Chi_Minh",
            "Asia/Hong_Kong",
            "Asia/Hovd",
            "Asia/Irkutsk",
            "Asia/Jakarta",
            "Asia/Jayapura",
            "Asia/Jerusalem",
            "Asia/Kabul",
            "Asia/Kamchatka",
            "Asia/Karachi",
            "Asia/Kathmandu",
            "Asia/Khandyga",
            "Asia/Kolkata",
            "Asia/Krasnoyarsk",
            "Asia/Kuala_Lumpur",
            "Asia/Kuching",
            "Asia/Kuwait",
            "Asia/Macau",
            "Asia/Magadan",
            "Asia/Makassar",
            "Asia/Manila",
            "Asia/Muscat",
            "Asia/Nicosia",
            "Asia/Novokuznetsk",
            "Asia/Novosibirsk",
            "Asia/Omsk",
            "Asia/Oral",
            "Asia/Phnom_Penh",
            "Asia/Pontianak",
            "Asia/Pyongyang",
            "Asia/Qatar",
            "Asia/Qostanay",
            "Asia/Qyzylorda",
            "Asia/Riyadh",
            "Asia/Sakhalin",
            "Asia/Samarkand",
            "Asia/Seoul",
            "Asia/Shanghai",
            "Asia/Singapore",
            "Asia/Srednekolymsk",
            "Asia/Taipei",
            "Asia/Tashkent",
            "Asia/Tbilisi",
            "Asia/Tehran",
            "Asia/Thimphu",
            "Asia/Tokyo",
            "Asia/Tomsk",
            "Asia/Ulaanbaatar",
            "Asia/Urumqi",
            "Asia/Ust-Nera",
            "Asia/Vientiane",
            "Asia/Vladivostok",
            "Asia/Yakutsk",
            "Asia/Yangon",
            "Asia/Yekaterinburg",
            "Asia/Yerevan",
            "Africa/Abidjan",
            "Africa/Accra",
            "Africa/Addis_Ababa",
            "Africa/Algiers",
            "Africa/Asmara",
            "Africa/Bamako",
            "Africa/Bangui",
            "Africa/Banjul",
            "Africa/Bissau",
            "Africa/Blantyre",
            "Africa/Brazzaville",
            "Africa/Bujumbura",
            "Africa/Cairo",
            "Africa/Casablanca",
            "Africa/Ceuta",
            "Africa/Conakry",
            "Africa/Dakar",
            "Africa/Dar_es_Salaam",
            "Africa/Djibouti",
            "Africa/Douala",
            "Africa/El_Aaiun",
            "Africa/Freetown",
            "Africa/Gaborone",
            "Africa/Harare",
            "Africa/Johannesburg",
            "Africa/Juba",
            "Africa/Kampala",
            "Africa/Khartoum",
            "Africa/Kigali",
            "Africa/Kinshasa",
            "Africa/Lagos",
            "Africa/Libreville",
            "Africa/Lome",
            "Africa/Luanda",
            "Africa/Lubumbashi",
            "Africa/Lusaka",
            "Africa/Malabo",
            "Africa/Maputo",
            "Africa/Maseru",
            "Africa/Mbabane",
            "Africa/Mogadishu",
            "Africa/Monrovia",
            "Africa/Nairobi",
            "Africa/Ndjamena",
            "Africa/Niamey",
            "Africa/Nouakchott",
            "Africa/Ouagadougou",
            "Africa/Porto-Novo",
            "Africa/Sao_Tome",
            "Africa/Tripoli",
            "Africa/Tunis",
            "Africa/Windhoek",
            "Antarctica/Casey",
            "Antarctica/Davis",
            "Antarctica/DumontDUrville",
            "Antarctica/Macquarie",
            "Antarctica/Mawson",
            "Antarctica/McMurdo",
            "Antarctica/Palmer",
            "Antarctica/Rothera",
            "Antarctica/Syowa",
            "Antarctica/Troll",
            "Antarctica/Vostok",
            "Arctic/Longyearbyen",
            "Atlantic/Azores",
            "Atlantic/Bermuda",
            "Atlantic/Canary",
            "Atlantic/Cape_Verde",
            "Atlantic/Faroe",
            "Atlantic/Madeira",
            "Atlantic/Reykjavik",
            "Atlantic/South_Georgia",
            "Atlantic/St_Helena",
            "Atlantic/Stanley",
            "Australia/Adelaide",
            "Australia/Brisbane",
            "Australia/Broken_Hill",
            "Australia/Darwin",
            "Australia/Eucla",
            "Australia/Hobart",
            "Australia/Lindeman",
            "Australia/Lord_Howe",
            "Australia/Melbourne",
            "Australia/Perth",
            "Australia/Sydney",
            "Europe/Amsterdam",
            "Europe/Andorra",
            "Europe/Astrakhan",
            "Europe/Athens",
            "Europe/Belgrade",
            "Europe/Berlin",
            "Europe/Bratislava",
            "Europe/Brussels",
            "Europe/Bucharest",
            "Europe/Budapest",
            "Europe/Busingen",
            "Europe/Chisinau",
            "Europe/Copenhagen",
            "Europe/Dublin",
            "Europe/Gibraltar",
            "Europe/Guernsey",
            "Europe/Helsinki",
            "Europe/Isle_of_Man",
            "Europe/Istanbul",
            "Europe/Jersey",
            "Europe/Kaliningrad",
            "Europe/Kiev",
            "Europe/Kirov",
            "Europe/Lisbon",
            "Europe/Ljubljana",
            "Europe/London",
            "Europe/Luxembourg",
            "Europe/Madrid",
            "Europe/Malta",
            "Europe/Mariehamn",
            "Europe/Minsk",
            "Europe/Monaco",
            "Europe/Moscow",
            "Europe/Oslo",
            "Europe/Paris",
            "Europe/Podgorica",
            "Europe/Prague",
            "Europe/Riga",
            "Europe/Rome",
            "Europe/Samara",
            "Europe/San_Marino",
            "Europe/Sarajevo",
            "Europe/Saratov",
            "Europe/Simferopol",
            "Europe/Skopje",
            "Europe/Sofia",
            "Europe/Stockholm",
            "Europe/Tallinn",
            "Europe/Tirane",
            "Europe/Ulyanovsk",
            "Europe/Uzhgorod",
            "Europe/Vaduz",
            "Europe/Vatican",
            "Europe/Vienna",
            "Europe/Vilnius",
            "Europe/Volgograd",
            "Europe/Warsaw",
            "Europe/Zagreb",
            "Europe/Zaporozhye",
            "Europe/Zurich",
            "Indian/Antananarivo",
            "Indian/Chagos",
            "Indian/Christmas",
            "Indian/Cocos",
            "Indian/Comoro",
            "Indian/Kerguelen",
            "Indian/Mahe",
            "Indian/Maldives",
            "Indian/Mauritius",
            "Indian/Mayotte",
            "Indian/Reunion",
            "Pacific/Apia",
            "Pacific/Auckland",
            "Pacific/Bougainville",
            "Pacific/Chatham",
            "Pacific/Chuuk",
            "Pacific/Easter",
            "Pacific/Efate",
            "Pacific/Fakaofo",
            "Pacific/Fiji",
            "Pacific/Funafuti",
            "Pacific/Galapagos",
            "Pacific/Gambier",
            "Pacific/Guadalcanal",
            "Pacific/Guam",
            "Pacific/Honolulu",
            "Pacific/Kanton",
            "Pacific/Kiritimati",
            "Pacific/Kosrae",
            "Pacific/Kwajalein",
            "Pacific/Majuro",
            "Pacific/Marquesas",
            "Pacific/Midway",
            "Pacific/Nauru",
            "Pacific/Niue",
            "Pacific/Norfolk",
            "Pacific/Noumea",
            "Pacific/Pago_Pago",
            "Pacific/Palau",
            "Pacific/Pitcairn",
            "Pacific/Pohnpei",
            "Pacific/Port_Moresby",
            "Pacific/Rarotonga",
            "Pacific/Saipan",
            "Pacific/Tahiti",
            "Pacific/Tarawa",
            "Pacific/Tongatapu",
            "Pacific/Wake",
            "Pacific/Wallis",
        ];
    }
}

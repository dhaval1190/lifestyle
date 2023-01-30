<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CityController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.city.cities', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $state_id = $request->state;

        if($state_id)
        {
            $state = State::findOrFail($state_id);
            $all_cities = $state->cities()->orderBy('city_name')->paginate(10);
        }
        else
        {
            $all_cities = City::orderBy('city_name')->paginate(10);
        }

        $all_states = collect();
        $all_countries = Country::orderBy('country_name')->get();

        foreach($all_countries as $key => $country)
        {
            $states = $country->states()->orderBy('state_name')->get();
            $all_states = $all_states->merge($states);
        }

        return response()->view('backend.admin.city.index',
            compact('all_states', 'state_id', 'all_cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.city.create-city', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_states = collect();
        $all_countries = Country::orderBy('country_name')->get();

        foreach($all_countries as $key => $country)
        {
            $states = $country->states()->orderBy('state_name')->get();
            $all_states = $all_states->merge($states);
        }

        return response()->view('backend.admin.city.create',
            compact('all_states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|numeric',
            //'city_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'city_name' => 'required|max:255',
            'city_lat' => 'required|numeric',
            'city_lng' => 'required|numeric',
            'city_slug' => 'required|max:255|regex:/^[\w-]*$/',
        ]);

        $state = State::find($request->state_id);

        if($state)
        {
            $city_name = $request->city_name;
            $city_state = $state->state_abbr;
            $city_slug = $request->city_slug;
            $city_lat = $request->city_lat;
            $city_lng = $request->city_lng;

            $validate_error = array();
            $city_name_exist = $state->cities()
                ->where('city_name', $city_name)
                ->count();
            if($city_name_exist > 0)
            {
                $validate_error['city_name'] = __('prefer_country.error.city-name-exist');

            }

            $city_slug_exist = $state->cities()
                ->where('city_slug', $city_slug)
                ->count();
            if($city_slug_exist > 0)
            {
                $validate_error['city_slug'] = __('setting_language.location.url-slug-exist');

            }

            if(count($validate_error) > 0)
            {
                throw ValidationException::withMessages($validate_error);
            }
            else
            {
                // now can store
                $new_city = new City(array(
                    'state_id' => $state->id,
                    'city_name' => $city_name,
                    'city_state' => $city_state,
                    'city_slug' => $city_slug,
                    'city_lat' => $city_lat,
                    'city_lng' => $city_lng,
                ));

                $created_city = $state->cities()->save($new_city);

                \Session::flash('flash_message', __('alert.city-created'));
                \Session::flash('flash_type', 'success');

                return redirect()->route('admin.cities.edit', $created_city);
            }
        }
        else
        {
            throw ValidationException::withMessages(['state_id' => 'State & Country not found']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param City $city
     * @return RedirectResponse
     */
    public function show(City $city)
    {
        return redirect()->route('admin.cities.edit', $city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param City $city
     * @return Response
     */
    public function edit(City $city)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.city.edit-city', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.city.edit',
            compact( 'city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param City $city
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, City $city)
    {
        $request->validate([
            //'city_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'city_name' => 'required|max:255',
            'city_lat' => 'required|numeric',
            'city_lng' => 'required|numeric',
            'city_slug' => 'required|max:255|regex:/^[\w-]*$/',
        ]);

        $state = State::find($city->state_id);

        if($state)
        {
            $city_name = $request->city_name;
            $city_lat = $request->city_lat;
            $city_lng = $request->city_lng;
            $city_slug = $request->city_slug;

            $validate_error = array();
            $city_name_exist = $state->cities()
                ->where('city_name', $city_name)
                ->where('id', '!=', $city->id)
                ->count();
            if($city_name_exist > 0)
            {
                $validate_error['city_name'] = __('prefer_country.error.city-name-exist');

            }

            $city_slug_exist = $state->cities()
                ->where('city_slug', $city_slug)
                ->where('id', '!=', $city->id)
                ->count();
            if($city_slug_exist > 0)
            {
                $validate_error['city_slug'] = __('setting_language.location.url-slug-exist');

            }

            if(count($validate_error) > 0)
            {
                throw ValidationException::withMessages($validate_error);
            }

            $city->city_name = $city_name;
            $city->city_slug = $city_slug;
            $city->city_lat = $city_lat;
            $city->city_lng = $city_lng;
            $city->save();

            \Session::flash('flash_message', __('alert.city-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.cities.edit', $city);
        }
        else
        {
            \Session::flash('flash_message', __('alert.state-country-not-found'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.cities.edit', $city);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param City $city
     * @return RedirectResponse
     */
    public function destroy(City $city)
    {
        $city->deleteCity();

        \Session::flash('flash_message', __('alert.city-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.cities.index');
    }
    
    public function add_canada_city(Request $request)
    {
            
        $i = 1;
        $str = file_get_contents("D:/wamp64/www/coach_directory/laravel_project/public/cities_canada.json");
        $json = json_decode($str, true);
        echo "<pre>";
        print_r($json);
        echo "</pre>";
        foreach($json as $key=>$val){
            if($val['country_code'] == 'CA'){

                        if($val['state_id'] == '872'){
                            $stateId = '6254929';
                                
                        }
                        if($val['state_id'] == '875'){
                            
                            $stateId = '6254930';
                            }
                        if($val['state_id'] == '867'){
                            
                            $stateId = '6254931';
                            }
                        if($val['state_id'] == '868'){
                            
                            $stateId = '6254932';
                            }
                        if($val['state_id'] == '877'){
                            
                            $stateId = '6254933';
                            }
                        if($val['state_id'] == '878'){
                            
                            $stateId = '6254934';
                            }
                        if($val['state_id'] == '874'){
                            
                            $stateId = '6254935';
                            }
                        if($val['state_id'] == '876'){
                            
                            $stateId = '6254936';
                            }
                        if($val['state_id'] == '866'){
                            
                            $stateId = '6254937';
                            }
                        if($val['state_id'] == '871'){
                            
                            $stateId = '6254938';
                            }
                        if($val['state_id'] == '873'){
                            
                            $stateId = '6254939';
                            }
                        if($val['state_id'] == '870'){
                            
                            $stateId = '6254940';
                            }
                        if($val['state_id'] == '869'){
                            
                            $stateId = '6254941';
                            }
                $city_name = $val['name'];
                $state_name = $val['state_name'];
                $latitude = $val['latitude'];
                $longitude = $val['longitude'];
                
                // echo $stateId." ".$city_name." ".$state_name." ".$latitude." ".$longitude;
                // echo "<br>";

                if($i > 1){
                            // mysqli_query($conn,"SET NAMES utf8");
                            // echo $i;
                            $new_city = new City(array(
                                    'state_id' => $stateId,
                                    'city_name' => $city_name,
                                    'city_state' => $state_name,
                                    'city_slug' => 'Jesús-María',
                                    'city_lat' => $latitude,
                                    'city_lng' => $longitude,
                                ));
    
                                $new_city->save();
                                $city_id = $new_city->id;
                                
                               $city_slug =  preg_replace("/[^a-zA-Z0-9\s!?.,\"]/", "", strtolower($city_name))."-".$city_id;
                            //    $city_slug = preg_replace("/[^a-zA-Z0-9\s!?.,\'\"]/", "", $city_slug);
                               echo $city_slug;
                            
                                echo "<br>";
    
                                $city = City::find($city_id);
                                $city->city_slug = $city_slug;
                                $city->save();
                        }
                        $i++;
                    }

            }

    }


    public function add_mexico_city(Request $request)
    {
            
        $i = 1;
        $str = file_get_contents("D:/wamp64/www/coach_directory/laravel_project/public/cities_canada.json");
        $json = json_decode($str, true);
        echo "<pre>";
        print_r($json);
        echo "</pre>";
        exit;
        foreach($json as $key=>$val){
            if($val['country_code'] == 'MX'){

                        if($val['state_id'] == '3456'){
                            $stateId = '6254942';
                                
                        }
                        if($val['state_id'] == '3457'){
                            
                            $stateId = '6254943';
                            }
                        if($val['state_id'] == '3460'){
                            
                            $stateId = '6254944';
                            }
                        if($val['state_id'] == '3475'){
                            
                            $stateId = '6254945';
                            }
                        if($val['state_id'] == '3451'){
                            
                            $stateId = '6254946';
                            }
                        if($val['state_id'] == '3447'){
                            
                            $stateId = '6254947';
                            }
                        if($val['state_id'] == '3473'){
                            
                            $stateId = '6254948';
                            }
                        if($val['state_id'] == '3471'){
                            
                            $stateId = '6254949';
                            }
                        if($val['state_id'] == '3472'){
                            
                            $stateId = '6254950';
                            }
                        if($val['state_id'] == '3453'){
                            
                            $stateId = '6254951';
                            }
                        if($val['state_id'] == '3450'){
                            
                            $stateId = '6254952';
                            }
                        if($val['state_id'] == '3469'){
                            
                            $stateId = '6254953';
                            }
                        if($val['state_id'] == '3459'){
                            
                            $stateId = '6254954';
                            }

                        if($val['state_id'] == '3470'){
                            
                            $stateId = '6254955';
                        }
                        if($val['state_id'] == '4857'){
                            
                        $stateId = '6254956';
                        }
                        if($val['state_id'] == '3474'){
                            
                            $stateId = '6254957';
                        }
                        if($val['state_id'] == '3465'){
                            
                            $stateId = '6254958';
                        }
                        if($val['state_id'] == '3477'){
                            
                            $stateId = '6254959';
                        }
                        if($val['state_id'] == '3452'){
                            
                            $stateId = '6254960';
                        }
                        if($val['state_id'] == '3448'){
                            
                            $stateId = '6254961';
                        }
                        if($val['state_id'] == '3476'){
                            
                            $stateId = '6254962';
                        }
                        if($val['state_id'] == '3455'){
                            
                            $stateId = '6254963';
                        }
                        if($val['state_id'] == '3467'){
                            
                            $stateId = '6254964';
                        }
                        if($val['state_id'] == '3461'){
                            
                            $stateId = '6254965';
                        }
                        if($val['state_id'] == '3449'){
                            
                            $stateId = '6254966';
                        }
                        if($val['state_id'] == '3468'){
                            
                            $stateId = '6254967';
                        }
                        if($val['state_id'] == '3454'){
                            
                            $stateId = '6254968';
                        }
                        if($val['state_id'] == '3463'){
                            
                            $stateId = '6254969';
                        }
                        if($val['state_id'] == '3458'){
                            
                            $stateId = '6254970';
                        }
                        if($val['state_id'] == '3464'){
                            
                            $stateId = '6254971';
                        }
                        if($val['state_id'] == '3466'){
                            
                            $stateId = '6254972';
                        }
                        if($val['state_id'] == '3462'){
                            
                            $stateId = '6254973';
                        }

                $city_name = $val['name'];
                $state_name = $val['state_name'];
                $latitude = $val['latitude'];
                $longitude = $val['longitude'];
                
                // echo $stateId." ".$city_name." ".$state_name." ".$latitude." ".$longitude;
                // echo "<br>";

                    // if($i > 1)
                    // {
                            
                            $new_city = new City(array(
                                    'state_id' => $stateId,
                                    'city_name' => $city_name,
                                    'city_state' => $state_name,
                                    'city_slug' => 'Jesús-María',
                                    'city_lat' => $latitude,
                                    'city_lng' => $longitude,
                                ));
    
                                $new_city->save();
                                $city_id = $new_city->id;
                                
                               $city_slug =  preg_replace("/[^a-zA-Z0-9\s!?.,\"]/", "", strtolower($city_name))."-".$city_id;
                               $city_slug = str_replace(" ","-",$city_slug);
                            //    $city_slug = preg_replace("/[^a-zA-Z0-9\s!?.,\'\"]/", "", $city_slug);
                               echo $city_slug;
                            
                                echo "<br>";
    
                                $city = City::find($city_id);
                                $city->city_slug = $city_slug;
                                $city->save();
                    // }
                        $i++;
                }

            }

        }
    
        

    }

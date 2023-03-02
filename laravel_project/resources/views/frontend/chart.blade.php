
@extends('frontend.layouts.app')

@section('styles')   

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif
    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
<style>
        /* Graph */
        #wrapper {
            margin: 25px auto;
            width: 60%;
        }
</style>

@section('content')
        <div id="wrapper">
                <div data-v-937e0192="" class="my-3"><h1 data-v-937e0192="">Stats Of Visitors</h1> </div>
                    <div data-v-937e0192="" class="card-deck mt-5">                        
                            <div data-v-937e0192=""
                                class="card-header pb-0 bg-transparent d-flex justify-content-between align-middle border-0">
                                <h2><p data-v-937e0192="" class="font-weight-bold text-muted small text-uppercase">Total Visitor(s) : {{$visit}} </p>                                                    </h2>
                            </div>
                        
                            <!-- <div data-v-937e0192="" class="card shadow border-0">
                                <div data-v-937e0192=""class="card-header pb-0 bg-transparent d-flex justify-content-between align-middle border-0">
                                    <p data-v-937e0192="" class="font-weight-bold text-muted small text-uppercase">Visitor(s)</p>
                                    <p data-v-937e0192=""><span data-v-937e0192=""class="badge badge-pill badge-primary p-2 font-weight-bold">Last 30 days</span></p>
                                </div>
                                <div data-v-937e0192="" class="card-body pt-0 pb-2">
                                    <p data-v-937e0192="" class="card-text display-4">
                                    {{$view}}
                                    </p>
                                </div>
                            </div> -->
                    </div>
                    <div class="chart"> 
                            <input class="btn btn-secondary" type="button" value="Month" id="mon" onClick="showHideDiv('month')"/>                         
                            <input class="btn btn-secondary" style="margin-right:20px" type="button" value="Weekly" id="wee"onClick="showHideDiv('weekly')"/> 
                        
                            <div id="month">
                                <canvas id="ROIchart"></canvas>
                            </div>
                            <div id="weekly" style="display:none">
                                <canvas id="ROIchartt"></canvas>
                            </div>
                    </div>    
                </div>
        </div>

    <script type="text/javascript">
        function showHideDiv(ele) {
            var srcElement = document.getElementById(ele);
            if (srcElement != null) {
                if (srcElement.style.display == "block") {
                    
                }
                else {
                    srcElement.style.display = 'block';
                }
                return false;
            }
        }
        
         $(document).ready(function () {
            $("#mon").click(function () {
                
            $("#weekly").hide();
            });
            
         });
         $(document).ready(function () {
            $("#wee").click(function () {
                
            $("#month").hide();
            });
         });
    
        var ctx = document.getElementById('ROIchart').getContext('2d');
        var array1 = <?php echo json_encode($data['value'])  ?>;
        var months =[] ;
        for (let i = 0; i < 12; i++) {
        months.push(moment().year(2023).month(i+1).date(0).startOf('month'))
        }
        var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
            label: 'Visitors',
            backgroundColor: 'aqua',
            borderColor: 'green',
            fill: true,
            data: array1,
            }]
        },
        options: {
            responsive: true,
            scales: {
            xAxes: [{
                type: 'time',
                time: {
                unit: 'month'
                }
            }]
            }
        }
        });

        var ctx = document.getElementById('ROIchartt').getContext('2d');
        var array1 = <?php echo json_encode($weekdata['week'])  ?>;
        var months =<?php echo json_encode($weekdata['weeks_ar'])  ?>;     
        // var weekNumber = moment().week();
        // console.log(weekNumber);
        // for (let i = 0; i < 5; i++) {
        //     console.log(weekNumber)
        // months.push(moment().year(2023).month(i+1).date(0).startOf('month'))
        
        // }
        console.log(months)     
    
        var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels:months,
            datasets: [{
            label: 'Visitors',
            backgroundColor: 'aqua',
            borderColor: 'green',
            fill: true,
            data: array1,
            }]
        },
        options: {
            responsive: true,
            scales: {
            xAxes: [{
                type: 'time',
                displayFormats: {
                    'week': 'MMM DD'
                },
                time: {
                unit: 'week'
                }
            }]
            }
        }
        });
    </script>
@endsection
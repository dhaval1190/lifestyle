
@extends('frontend.layouts.app')

@section('styles')   

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
</head>
<style>
body {
    background: #fff;
    color: #333;
    font: 12px/20px 'Helvetica Neue', Arial, sans-serif;
    margin: 0;
    padding: 0;
}

h2 {
    font-size: 18px;
    font-weight: normal;
    line-height: 20px;
    margin: 0 0 20px 0;
    padding: 0;
    text-align: center;
}

h4 {
    color: #545454;
    font-size: 14px;
    font-weight: normal;
    line-height: 20px;
    margin: 0 0 20px 0;
    padding: 0;
    text-align: center;
}

a {
    color: #333;
}

/* Table */
#data-table {
    border: none;
    /* Turn off all borders */
    border-top: 1px solid #ccc;
    width: 60%;
}

#data-table th,
#data-table td {
    border: none;
    /* Turn off all borders */
    border-bottom: 1px solid #ccc;
    margin: 0;
    padding: 10px;
    text-align: left;
}

/* Toggle */
.toggles {
    background: #ebebeb;
    color: #545454;
    height: 20px;
    padding: 15px;
}

.toggles p {
    margin: 0;
}

.toggles a {
    background: #222;
    border-radius: 3px;
    color: #fff;
    display: block;
    float: left;
    margin: 0 10px 0 0;
    padding: 0 6px;
    text-decoration: none;
}

.toggles a:hover {
    background: #666;
}

#reset-graph-button {
    float: right;
}

/* Graph */
/* Containers */
#wrapper {
    margin: 25px auto;
    width: 60%;
}

#figure {
    height: 380px;
    position: relative;
}

#figure ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.graph {
    height: 283px;
    position: relative;
}

/* Legend */
.legend {
    background: #f0f0f0;
    border-radius: 4px;
    bottom: 0;
    position: absolute;
    text-align: left;
    width: 540px;
}

.legend li {
    display: block;
    float: left;
    height: 20px;
    margin: 0;
    padding: 10px 30px;
    width: 120px;
}

.legend span.icon {
    background-position: 50% 0;
    border-radius: 2px;
    display: block;
    float: left;
    height: 16px;
    margin: 2px 10px 0 0;
    width: 16px;
}

/* X-Axis */
.x-axis {
    bottom: 0;
    color: #555;
    position: absolute;
    text-align: center;
    width: 100%;
}

.x-axis li {
    float: left;
    margin: 0 15px;
    padding: 5px 0;
    width: 10%;
}

.x-axis li span {
    float: left;
}

/* Y-Axis */
.y-axis {
    color: #555;
    position: absolute;
    text-align: right;
    width: 100%;
}

.y-axis li {
    border-top: 1px solid #ccc;
    display: block;
    height: 62px;
    width: 100%;
}

.y-axis li span {
    display: block;
    margin: -10px 0 0 -60px;
    padding: 0 10px;
    width: 40px;
}

/* Graph Bars */
.bars {
    height: 253px;
    position: absolute;
    width: 100%;
    z-index: 10;
}

.bar-group {
    float: left;
    height: 100%;
    margin: 0 15px;
    position: relative;
    width: 10%;
}

.bar {
    border-radius: 3px 3px 0 0;
    bottom: 0;
    cursor: pointer;
    height: 0;
    position: absolute;
    text-align: center;
    width: 24px;
}

.bar span {
    background: #fefefe;
    border-radius: 3px;
    left: -8px;
    display: none;
    margin: 0;
    position: relative;
    text-shadow: rgba(255, 255, 255, 0.8) 0 1px 0;
    width: 40px;
    z-index: 20;

    -webkit-box-shadow: rgba(0, 0, 0, 0.6) 0 1px 4px;
    box-shadow: rgba(0, 0, 0, 0.6) 0 1px 4px;
}

.bar:hover span {
    display: block;
    margin-top: -25px;
}

#data-table.js {
    display: none;
}

.bar span {
    background: #fefefe;
}

.fig0 {
    background: #a22;
}
</style>

<body>
    
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
                    <div class="details"> 
                        <input class="btn btn-secondary" type="button" value="Month" id="mon" onClick="showHideDiv('month')"/>                         
                        <input class="btn btn-secondary" style="margin-right:20px" type="button" value="Weekly" id="wee"onClick="showHideDiv('weekly')"/> 
                   </div>
                    <div id="month">
                        <canvas id="ROIchart"></canvas>
                        </div>
                        <div id="weekly" style="display:none">
                        <canvas id="ROIchartt"></canvas>
                    </div>
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
      </script>
    
    <!-- CHARTS -->
    <!-- array1.length -->
    <script>
        
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
        </script>

        <script>
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
</body>

</html>
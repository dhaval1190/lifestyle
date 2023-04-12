
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-12 p-0">
                    <div class="upper_white_bg">
                        <div class="upper_middle_img">
                            @if(empty($user_detail['user_cover_image']))                            
                                <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">
                                    <div class="container">
                                        <div class="row align-items-center justify-content-center text-center">
                                          <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">
                                            <div class="row justify-content-center mt-5">
                                                <div class="col-md-8 text-center">
                                                    <h1>{{ __('frontend.stats.title') }}</h1>
                                                    <p class="mb-0">View performance stats of your content below.</p>                                
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>                  
                            
                            @else
                                <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ Storage::disk('public')->url('user/'. $user_detail['header-inner.webp']) }});">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="wrapper">
                
                    <div data-v-937e0192="" class="card-deck mt-5">                        
                            <div data-v-937e0192="" class="card-header pb-0 bg-transparent d-flex justify-content-between align-middle border-0">
                                <h4><p data-v-937e0192="" class="font-weight-bold text-muted small text-uppercase">Total Visitor(s) : {{$visit}} </p></h4>                               
                            </div>
                            <div data-v-937e0192="" class="card-header pb-0 bg-transparent d-flex justify-content-between align-middle border-0">
                                <h4><p data-v-937e0192="" class="font-weight-bold text-muted small text-uppercase">Today Visitor(s) : {{$Today_Visits_count}}</p></h4>
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
                            <input class="btn btn-secondary" type="button" value="Month" id="mon" />                         
                            <input class="btn btn-secondary" style="margin-right:20px" type="button" value="Weekly" id="wee"/> 
                        
                            
                            <div id="weekly">
                                <canvas id="ROIchartt"></canvas>
                            </div>
                    </div>    
                </div>
        </div>

    <script type="text/javascript">        
        $(document).ready(function () {
            chartdetail('monthly');
            $("#mon").click(function () {
                chartdetail('monthly');
            });
            
            $("#wee").click(function () {
                chartdetail('weekly');
            });
        });

         function chartdetail(charttype){
            var ctx = document.getElementById('ROIchartt').getContext('2d');
            if(charttype=='monthly'){
                var array1 = <?php echo json_encode($data['value'])  ?>;
                var labels = <?php echo json_encode($data['month'])  ?>;
                var montlylabel = 'Monthly Visitors';
            }else{
                var array1 = <?php echo json_encode($weekdata['week'])  ?>;  
                var labels =<?php echo json_encode($weekdata['label'])  ?>;  
                var montlylabel = 'Weekly Visitors';

            }
            const adjustlabel = labels.map(label => label.split(' '));
            const data = {
                labels: adjustlabel,
                datasets: [{
                    label: montlylabel,
                    data: array1,
                    backgroundColor: '#F05127',
                    borderColor: '#F05127',
                    fill: true,
                }]
                };

                // config 
                const config = {
                type: 'line',
                data,
                options: {
                    scales: {
                    y: {
                        beginAtZero: true
                    }
                    }
                }
                };

                // render init block
                const myChart = new Chart(
                document.getElementById('ROIchartt'),
                config
                );
        }
    // Instantly assign Chart.js version
    const chartVersion = document.getElementById('ROIchartt');
    chartVersion.innerText = Chart.version; 
    </script>
    <!-- <script>
         // var weekNumber = moment().week();
        // console.log(weekNumber);
        // for (let i = 0; i < 5; i++) {
        //     console.log(weekNumber)
        // months.push(moment().year(2023).month(i+1).date(0).startOf('month'))
        
        // }
        // var new_month = [];
        // months.forEach(function(month) {
        //     console.log(month);
        //     new_month.push(month.toString());
        // });
        // console.log(new_month)    

    
        // var chart = new Chart(ctx, {
        // type: 'line',
        // data: {
        //     labels:months,
        //     datasets: [{
        //     label: 'Visitors',
        //     backgroundColor: '#F05127',
        //     borderColor: '#F05127',
        //     fill: true,
        //     data: array1,
        //     }]
        // },
        // options: {
        //     responsive: true,
        //     scales: {
        //     xAxes: [{
        //         type: 'time',
        //         displayFormats: {
        //             'week': 'MMM DD'
        //         },
        //         time: {
        //         unit: 'week'
        //         }
        //     }]
        //     }
        // }
        // });
    </script> -->
@endsection
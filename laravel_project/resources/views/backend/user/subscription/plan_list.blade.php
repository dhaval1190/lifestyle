@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.subscription.switch-plan') }}</h1>
            <p class="mb-4">{{ __('backend.subscription.switch-plan-desc-user') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.subscriptions.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <!-- -----------------------------Contact-Main-Section-start------------------------------------->
            <section class="main_contact_section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pricing_plan">
                                <h1 class="p_heading">Pricing Plan</h1>
                                {{-- <p class="p_description">
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna
                      aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                      ullamco laboris nisi ut aliquip
                    </p> --}}
                            </div>
                        </div>

                        <section id="model_1" class="w-100">
                            @php
                                $plan_price = '';
                                if (isset($active_plan->plan_price)) {
                                    $plan_price = $active_plan->plan_price;
                                }
                                
                            @endphp
                            @if (isset($active_plan->plan_price))
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs container" id="myTab" role="tablist">
                                        <div class="nav-tag">
                                            <p class="nav_tag_text">Active</p>
                                            <img src="{{ asset('frontend/images/active_icon.png') }}" alt=""
                                                class="w-100" />
                                        </div>

                                        <div class="nav-bg">
                                            <li class="nav-item">
                                                <p class="nav-link active nav_text" aria-controls="home"
                                                    aria-selected="true"><i
                                                        class="fa fa-home"></i>{{ isset($active_plan->plan_name) ? $active_plan->plan_name : '' }}
                                                </p>
                                            </li>
                                            <li class="nav-item">
                                                <p class="nav-link nav-text-tab" aria-controls="profile"
                                                    aria-selected="false">
                                                    <i
                                                        class="fa fa-money"></i>{{ isset($active_plan->plan_name) ? $active_plan->plan_name : '' }}
                                                    <span
                                                        class="nav-number">{{ $site_global_settings->setting_product_currency_symbol . $plan_price }}</span>
                                                </p>
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                            @endif
                            <div class="tab-content container" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="row">
                                        @php $count= 1; @endphp
                                        @foreach ($all_plans as $plans_key => $plan)
                                            <div class="col-lg-5 col-md-6 card_bottom">
                                                <div class="box-{{ $count }}">
                                                    <div class="plan_heading">
                                                        <div class="plan_title">
                                                            <h3 class="plan_name">{{ $plan->plan_name }}</h3>
                                                            <div class="plan_rate">
                                                                <p class="price">
                                                                    {{ $site_global_settings->setting_product_currency_symbol . $plan->plan_price }}
                                                                </p>
                                                                <p class="plan_month">
                                                                    @if ($plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                                                        {{ __('backend.plan.lifetime') }}
                                                                    @elseif($plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                                                        {{ __('backend.plan.monthly') }}
                                                                    @elseif($plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                                                        {{ __('backend.plan.quarterly') }}
                                                                    @else
                                                                        {{ __('backend.plan.yearly') }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price_list">
                                                        <ul class="values">
                                                            <li class="list_point">
                                                                <img src="{{ asset('frontend/images/orange_tick.png') }}"
                                                                    alt="tick" />
                                                                @if (is_null($plan->plan_max_free_listing))
                                                                    {{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                                                @else
                                                                    {{ $plan->plan_max_free_listing . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                                                @endif

                                                            </li>
                                                            <li class="list_point">
                                                                <img src="{{ asset('frontend/images/orange_tick.png') }}"
                                                                    alt="tick" />
                                                                @if (!empty($plan->plan_features))
                                                                    @php
                                                                        $plan_features_array = [];
                                                                        foreach (preg_split("/((\r?\n)|(\r\n?))/", $plan->plan_features) as $plan_features_line) {
                                                                            $plan_features_array[] = $plan_features_line;
                                                                        }
                                                                    @endphp

                                                                    @foreach ($plan_features_array as $plan_features_array_key => $a_plan_feature)
                                                                        {{ $a_plan_feature }}
                                                                    @endforeach
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="price_pay_btn">
                                                        @if (isset($active_plan))
                                                            @if ($active_plan->id == $plan->id && $subscription_status !== 'canceled')
                                                                <button type="button" class="pay_btn" disabled>Currently
                                                                    Active</a>
                                                                @elseif($active_plan->id == $plan->id && $subscription_status == 'canceled')
                                                                    <button type="button" class="pay_btn"
                                                                        disabled>Canceled</a>
                                                                    {{-- @elseif($subscription_status == 'canceled')
                                                                        <a href="{{ url('user/plans/' . $plan->slug) }}"
                                                                            class="pay_btn">Choose</a> --}}
                                                                    @elseif($subscription_status !== 'canceled' && $subscription_status !== 'active')
                                                                        <a href="{{ url('user/plan/upgrade/' . $plan->slug) }}"
                                                                            class="pay_btn">Upgrade Plan</a>
                                                                    @elseif($subscription_status == 'active' && $active_plan->slug != 'yearly-premium')
                                                                        <a href="{{ url('user/plan/upgrade/' . $plan->slug) }}"
                                                                            class="pay_btn">Upgrade Plan</a>
                                                            @endif
                                                        @else
                                                            <a href="{{ url('user/plans/' . $plan->slug) }}"
                                                                class="pay_btn">Choose</a>
                                                        @endif
                                                        {{-- <a href="#" class="pay_btn">Pay with Stripe</a> --}}
                                                    </div>
                                                    @if (isset($active_plan->id) && $active_plan->id == $plan->id)
                                                        <div class="ribbon ribbon-top-left">
                                                            <span>CURRENT PLAN</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @php $count++; @endphp
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>
                        @if ($subscription_status == 'canceled')  
                            <p>Note: You have canceled the plan. Renew will be done after current period ends.</p>
                        @endif
                    </div>
                </div>
            </section>
            <!-- -----------------------------Contact-Main-Section-End------------------------------------->
        </div>
    </div>
@endsection

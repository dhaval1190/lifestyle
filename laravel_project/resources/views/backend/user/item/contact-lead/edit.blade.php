@extends('backend.user.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row justify-content-between">
        <div class="col-8 col-md-8 col-lg-9">
            <h1 class="h3 mb-2 text-gray-800 font-14">{{ __('role_permission.item-leads.admin-contact-lead') }}</h1>
            <p class="mb-4">{{ __('This page shows you the listing of contact lead sent to you') }}</p>
        </div>
        <div class="col-4 col-md-4 col-lg-3 back01 text-right">
            <a href="{{ route('user.contact-leads.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pb-4 align-items-end">
        <div class="col-md-4">
            <label for="contact_lead_name"
                class="text-black">{{ __('role_permission.item-leads.item-lead-name') }}</label>
            <input id="contact_lead_name" type="text"
                class="form-control @error('contact_lead_name') is-invalid @enderror"
                name="contact_lead_name"
                value="{{ old('contact_lead_name') ? old('contact_lead_name') : $contact_lead->name }}" readonly>
            @error('contact_lead_name')
                <span class="invalid-tooltip">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="contact_lead_email"
                class="text-black">{{ __('role_permission.item-leads.item-lead-email') }}</label>
            <input id="contact_lead_email" type="text"
                class="form-control @error('contact_lead_email') is-invalid @enderror"
                name="contact_lead_email"
                value="{{ old('contact_lead_email') ? old('contact_lead_email') : $contact_lead->email }}" readonly>
            @error('contact_lead_email')
                <span class="invalid-tooltip">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    
        @if($user_role->role_id !== 3)
            <div class="col-md-4">
                <div class="see_coach_btn_set">
                    <a href="{{ route('page.profile', encrypt($contact_lead->sender_id)) }}">
                        <button type="button" class="btn btn-primary">See Coach</button>

                    </a>
                </div>
            </div>
        @endif
    </div>
    
    {{-- ----------------Question  --}}
       <div class="container-fluid bg-white">
        <div class="row">
            <div class="col-md-12">
                <div class="question_set_coaches">
                    <span class="questions">Q1. What are the top 2 challenges you feel this coach can help you navigate?</span>
                    <p class="answer">{{ $contact_lead->question1 }}</p>
                </div>
                <div class="question_set_coaches">
                    <span class="questions">Q2.What type of personality traits would be helpful for a person to have when coaching you?</span>
                    <p class="answer">{{ $contact_lead->question2 }}</p>
                </div>
                <div class="question_set_coaches">
                    <span class="questions">Q3.What specific training, expertise and industry knowledge is important for this coach to possess?</span>
                    <p class="answer">{{ $contact_lead->question3 }}</p>
                </div>
                <div class="question_set_coaches">
                    <span class="questions">Q4.On a sale of 1-10 how structured do you want your coaching experience?</span>
                    <p class="answer">{{ $contact_lead->question4 }}</p>
                </div>
                <div class="question_set_coaches">
                    <span class="questions">Q5.If you invest your time and money with this coach, what is the single biggest change you hope to achieve?</span>
                    <p class="answer">{{ $contact_lead->question5 }}</p>
                </div>
                <div class="question_set_coaches">
                    <span class="questions">Q6.Was there a particular Blog post, Podcast, Video, e-Book, etc that helped you select this coach? If so please share the name of it.</span>
                    <p class="answer">{{ $contact_lead->question6 }}</p>
                </div>
            </div>
        </div>
     
       </div> 
            
    {{-- ------------------------ --}}
@endsection

@section('scripts')
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.user.partials.bootstrap-select-locale')
@endsection

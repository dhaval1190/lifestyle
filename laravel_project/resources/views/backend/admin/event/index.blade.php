@extends('backend.admin.layouts.app')

@section('styles')
    <!-- searchable selector -->
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<style>
    .active_btn{
        background-color: #dddd;
    }
</style>

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('Event') }}</h1>
            <p class="mb-4">{{ __('This page lists all event records that saved in the database.') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.events.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('Create Event') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        @if(Session::has('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-10">
                    <div class="row pb-2">
                        <div class="col-12">
                            <span class="text-gray-800">
                                {{ number_format($event_count) . ' ' . __('category_description.records') }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                @if ($all_events->count() > 0)
                                <table class="table table-bordered table-striped" id="dataTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr class="bg-info text-white">
                                            <th>{{ __('Image') }}</th>
                                            <th>{{ __('backend.category.name') }}</th>
                                            <th>{{ __('Start Time') }}</th>
                                            <th>{{ __('End Time') }}</th>
                                            <th>{{ __('Description') }}</th>
                                            <th>{{ __('Social URL') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Social URL') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($all_events as $event)
                                                <tr>
                                                    <td>
                                                        @if (!empty($event->event_image))
                                                            <img class="img-responsive category-img-preview"
                                                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('event/' . $event->event_image) }}">
                                                        @else
                                                            <img class="img-responsive category-img-preview"
                                                                src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}">
                                                        @endif
                                                    </td>
                                                    <td>{{ $event->event_name }}</td>
                                                    @php
                                                        $event_start_date_time = $event->event_start_date . ' ' . $event->event_start_hour;
                                                        $newStartDatetime = strtotime(date($event_start_date_time));
                                                        date_default_timezone_set('America/New_York');
                                                        $newStartDatetime = date('Y-m-d H:i:s', $newStartDatetime);
                                                        date_default_timezone_set('UTC');                                                             
                                                        $exp_event_start_date = explode(" ",$newStartDatetime);
                                                        $event_start_date = $exp_event_start_date[0];
                                                        $event_start_hour = $exp_event_start_date[1];

                                                        $event_end_date_time = $event->event_end_date . ' ' . $event->event_end_hour;
                                                        $newEndDatetime = strtotime(date($event_end_date_time));
                                                        date_default_timezone_set('America/New_York');
                                                        $newEndDatetime = date('Y-m-d H:i:s', $newEndDatetime);
                                                        date_default_timezone_set('UTC');                                                       
                                                        $exp_event_end_date = explode(" ",$newEndDatetime);
                                                        $event_end_date = $exp_event_end_date[0];        
                                                        $event_end_hour = $exp_event_end_date[1];
                                                    @endphp
                                                    <td>{{ $event_start_date . ' ' . $event_start_hour }}
                                                    </td>
                                                    <td>{{ $event_end_date . ' ' . $event_end_hour }}
                                                    </td>
                                                    <td>{{ $event->event_description }}</td>
                                                    <td>{{ $event->event_social_url }}</td>
                                                    <td>{{ $event->status == '0' ? 'Draft' : 'Publish' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.events.edit', ['event' => $event->id]) }}"
                                                            class="btn btn-primary btn-circle btn-xs">
                                                            <i class="fas fa-cog"></i>
                                                        </a>                                                        
                                                        <button type="button" class="btn btn-danger deleteEventBtn mt-3" value="{{ $event->id }}"><i
                                                            class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                        <p style="text-align: center;font-size:26px;">No Event Found</p>
                                    @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{ $all_events->links() }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-2 pt-3 border-left-info">
                    <div class="row">
                        <div class="col-6">
                            <form method="GET" action="" name="searchFrm" id="searchFrm">
                                <div class="form-group">
                                    {{-- <label for="search_query"
                                            class="text-black">{{ __('frontend.search.search') }}</label> --}}
                                    <input id="search_query" type="hidden"
                                        class="form-control @error('search_query') is-invalid @enderror" name="search_query"
                                        value="upcoming">
                                    <button class="form-control @if(request()->get('search_query') == 'upcoming') active_btn  @endif" type="submit" name="upcoming">Upcoming</button>
                                    @error('search_query')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <div class="col-6">
                            <form method="GET" action="" name="searchFrm" id="searchFrm">
                                <div class="form-group">
                                    {{-- <label for="search_query"
                                            class="text-black">{{ __('frontend.search.search') }}</label> --}}
                                    <input id="search_query" type="hidden"
                                        class="form-control @error('search_query') is-invalid @enderror" name="search_query"
                                        value="past">
                                    <button class="form-control @if(request()->get('search_query') == 'past') active_btn  @endif" type="submit" name="past" >Past</button>
                                    @error('search_query')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <div class="col-6">
                            <form method="GET" action="" name="searchFrm" id="searchFrm">
                                <div class="form-group">
                                    {{-- <label for="search_query"
                                            class="text-black">{{ __('frontend.search.search') }}</label> --}}
                                    <input id="search_query" type="hidden"
                                        class="form-control @error('search_query') is-invalid @enderror" name="search_query"
                                        value="published">
                                    <button class="form-control @if(request()->get('search_query') == 'published') active_btn  @endif" type="submit" name="published">Published</button>
                                    @error('search_query')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </form>
                        </div>

                        <div class="col-6">
                            <form method="GET" action="" name="searchFrm" id="searchFrm">
                                <div class="form-group">
                                    {{-- <label for="search_query"
                                            class="text-black">{{ __('frontend.search.search') }}</label> --}}
                                    <input id="search_query" type="hidden"
                                        class="form-control @error('search_query') is-invalid @enderror"
                                        name="search_query" value="draft">
                                    <button class="form-control @if(request()->get('search_query') == 'draft') active_btn  @endif" type="submit" name="submit">Draft</button>
                                    @error('search_query')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </form>
                        </div>
                        <div class="col-12">
                            <form action="" method="get" name="searchFrm">
                                <label for="search_query" class="text-black">{{ __('frontend.search.search') }}</label>
                                <input id="keyword_search" type="text"
                                    class="form-control @error('keyword_search') is-invalid @enderror" name="keyword_search"
                                    value="{{ request()->get('keyword_search') ? request()->get('keyword_search') : '' }}"
                                    placeholder="Search">
                                <div class="row form-group">
                                    <div class="col-12">
                                        <button type="submit"
                                            class="btn btn-primary btn-block mt-3">{{ __('backend.shared.update') }}</button>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <a class="btn btn-outline-primary btn-block"
                                            href="{{ route('admin.events.index') }}">
                                            {{ __('theme_directory_hub.filter-link-reset-all') }}
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModal" aria-hidden="true" data>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.events.destroy',1) }}" method="POST" name="deleteEventFrm" id="deleteEventFrm">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('backend.shared.delete-confirm') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('Do you want to delete this Event?') }}
                        <input type="hidden" name="event_id" id="event_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- searchable selector -->
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.admin.partials.bootstrap-select-locale')
    
    <script>
        $(document).ready(function () {
            $('.deleteEventBtn').on('click',function(e){
                // console.log("jdjkdkjsh")
                e.preventDefault();
                var event_id = $(this).val();
                $('#event_id').val(event_id);
                $('#deleteEventModal').modal('show')

            })
            
        });
    </script>


@endsection

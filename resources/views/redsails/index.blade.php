<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#toggle-sidenav').on('click', function () {
                $('.sidenav-holder').toggle();
                $('.content-holder').toggleClass('col-lg-10').toggleClass('col-lg-12');
            });
        });
    </script>
</head>

<body>
<div id="app">
    <div class="container-fluid" style="text-align: left; color: #000;">
        <div class="row no-gutters">
            <div class="sidenav-holder col-12 col-lg-2">
                @include('inc.admin-sidenav')
            </div>
            <div class="content-holder col-12 col-lg-10">
                @include('inc.admin-nav')
                <div id="manage-users">
                    @include('partials.alerts')
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="text-right mr-3 mr-md-5 mb-4">
                                <button type="button" class="btn btn-success mr-md-2" data-toggle="modal"
                                        data-target="#addFestivalModal">Add New Festival
                                </button>
                            </div>
                            <div class="card">
                                <div class="card-header">Red Sails Festival Management</div>
                                <div class="m-3">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <!-- <th scope="col" class="col-2">Festival ID</th> -->
                                            <th scope="col" class="col-4">Festival Year</th>
                                            <th scope="col" class="col-5">Festival Start Date</th>
                                            <th scope="col" class="col-5">Festival End Date</th>
                                            <th scope="col" class="col-5">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($festivals as $f)
                                            <tr>
                                                <!-- <th scope="row">{{$f->id}}</th> -->
                                                <td class="col-4">{{$f->year}}</td>
                                                <td class="col-5">{{ \Carbon\Carbon::parse($f->start_date)->format('D jS M Y')}}</td>
                                                <td class="col-5">{{ \Carbon\Carbon::parse($f->end_date)->format('D jS M Y')}}</td>
                                                <td>
                                                    <a href="/events/red-sails/programme/selectEdit/{{$f->id}}">
                                                        <button type="button" class="btn btn-dark "><img
                                                                src="/img/baseline_create_white_18dp.png"
                                                                data-toggle="tooltip" data-placement="bottom"
                                                                title="Edit Festival Programme"></button>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                            data-target="#deleteFestivalModal{{$f->id}}"><img
                                                            src="/img/baseline_delete_white_18dp.png"
                                                            data-toggle="tooltip" data-placement="bottom"
                                                            title="Delete Event"></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal fade " id="addFestivalModal" data-backdrop="static"
                                 data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Add New Festival</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form class="ml-3 mr-3 mt-2 mb-3" action="/events/red-sails/new" method="POST">
                                            @csrf
                                            <div class="modal-body text-left">
                                                <div class="form-group">
                                                    <label for="festivalYear">Festival Year <span
                                                            class="asterisk">*</span></label>
                                                    <select id="festivalYear"
                                                            class="form-control @error('festivalYear') is-invalid @enderror"
                                                            id="exampleFormControlSelect1" name="festivalYear" required>
                                                        <option value="" selected disabled>Select...</option>
                                                        <?php
                                                        for($i = date('Y'); $i<date('Y') + 5; $i++) {
                                                      echo "<option value='$i'>$i</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    @error('festivalYear')
                                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col">
                                                        <label for="startDate">Start Date <span
                                                                class="asterisk">*</span></label>
                                                        <input id="startDate" type="date"
                                                               class="form-control @error('startDate') is-invalid @enderror"
                                                               placeholder="Festival Start Date..." name="startDate"
                                                               required>
                                                        @error('startDate')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col">
                                                        <label for="endDate">End Date <span
                                                                class="asterisk">*</span></label>
                                                        <input id="endDate" type="date"
                                                               class="form-control @error('endDate') is-invalid @enderror"
                                                               placeholder="Festival End Date..." name="endDate"
                                                               required>
                                                        @error('endDate')
                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">Add New Festival</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($festivals as $f)
                            <div class="modal fade" id="deleteFestivalModal{{$f->id}}" tabindex="-1"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Delete Festival Year {{$f->year}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you wish to delete the festival for {{$f->year}}? <br>This
                                            action cannot be reversed
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <form action="/events/red-sails/delete/{{$f->id}}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>

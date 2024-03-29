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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
        $(document).ready(function() {

        
        $("#editprofile").click(function() {
            $('#title').removeAttr('disabled');
            $('#firstname').removeAttr('disabled');
            $('#middlename').removeAttr('disabled');
            $('#surname').removeAttr('disabled');
            $('#address').removeAttr('disabled');
            $('#town').removeAttr('disabled');
            $('#postcode').removeAttr('disabled');
            $('#tel_no').removeAttr('disabled');
            $('#mob_no').removeAttr('disabled');
            $('#email').removeAttr('disabled');
            $('.roles').removeAttr('disabled');
            $('#saveprofile').removeAttr('disabled');
            $('#cancelprofile').removeAttr('disabled');
            $('#editprofile').attr('disabled', 'disabled');
    });

    $("#cancelprofile").click(function() {
            $('#title').attr('disabled', 'disabled');
            $('#firstname').attr('disabled', 'disabled');
            $('#middlename').attr('disabled', 'disabled');
            $('#surname').attr('disabled', 'disabled');
            $('#address').attr('disabled', 'disabled');
            $('#town').attr('disabled', 'disabled');
            $('#postcode').attr('disabled', 'disabled');
            $('#tel_no').attr('disabled', 'disabled');
            $('#mob_no').attr('disabled', 'disabled');
            $('#email').attr('disabled', 'disabled');
            $('.roles').attr('disabled', 'disabled');
            $('#saveprofile').attr('disabled', 'disabled');
            $('#cancelprofile').attr('disabled', 'disabled');
            $('#editprofile').removeAttr('disabled');
    });

    $('#toggle-sidenav').on('click', function(){
      $('.sidenav-holder').toggle();
      $('.content-holder').toggleClass('col-lg-10').toggleClass('col-lg-12');
    });
    });

</script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
<div id="profile">
    @include('partials.alerts')
    <div class="container">
        <div id="user-profile">
            @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        <div class="row justify-content-center d-flex align-items-middle">
            <div class="col-12">
                <div class="card">
            <div class="card-header">User Profile - {{\Crypt::decrypt($profileInfo->firstname)}} {{\Crypt::decrypt($profileInfo->surname)}}</div>
                    <div class="card-body">
                        
                      <form action="/user/profile/update" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="title" class="col-md-2 col-form-label text-md-right">Title:</label>
    
                            <div class="col-9">
                                <select class="form-control" name="title" id="title" disabled>
                                    @foreach($titles as $title)
                                <option value="{{$title->name}}" @if($title->name == Crypt::decrypt($profileInfo->title)) selected @endif>{{$title->name}}</option>
                                @endforeach
</select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="firstname" class="col-12 col-lg-2 col-form-label text-lg-right">First Name:</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{\Crypt::decrypt($profileInfo->firstname)}}" required autofocus disabled>
                                @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="firstname" class="col-12 col-lg-2 col-form-label text-lg-right">Middle Name(s):</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" name="middlename" value="{{\Crypt::decrypt($profileInfo->middlename)}}" required autofocus disabled>
                                @error('middlename')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-12 col-lg-2 col-form-label text-lg-right">Surname:</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{\Crypt::decrypt($profileInfo->surname)}}" required autofocus disabled>
                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-12 col-lg-2 col-form-label text-lg-right">Address:</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{\Crypt::decrypt($profileInfo->address)}}" required autofocus disabled>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="town" class="col-12 col-lg-2 col-form-label text-lg-right">Town:</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="town" type="text" class="form-control @error('town') is-invalid @enderror" name="town" value="{{\Crypt::decrypt($profileInfo->town)}}" required autofocus disabled>
                                @error('town')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-12 col-lg-2 col-form-label text-lg-right">Postcode:</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="postcode" type="text" class="form-control @error('postcode') is-invalid @enderror" name="postcode" value="{{\Crypt::decrypt($profileInfo->postcode)}}" required autofocus disabled>
                                @error('postcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-12 col-lg-2 col-form-label text-lg-right">Telephone:</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="tel_no" type="text" class="form-control @error('contact_no') is-invalid @enderror" name="contact_no" value="{{\Crypt::decrypt($profileInfo->contact_no)}}" autofocus disabled>
                                @error('contact_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label for="email" class="col-12 col-lg-2 col-form-label text-lg-right">Email</label>
    
                            <div class="col-12 col-lg-9">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$userEmail}}" required disabled>
    
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                        <button id="editprofile" type="button" class="col-12 col-lg-3 btn btn-dark" style="margin-right: 30%;">Edit Profile</button>
                        <button id="cancelprofile" type="button" class="col-12 col-lg-2 btn btn-danger" style="margin-right: 3%;" disabled>Cancel</button>
                        <button id="saveprofile" type="submit" class="col-12 col-lg-2 btn btn-primary" style="margin-right: 3%;" disabled>Save</button>
                        </form>
                    </div>
                    <br>
                    <button type="submit" class="col-12 col-lg-3 btn btn-danger" data-toggle="modal" data-target="#delete{{$userID}}" style="margin-left: 1.1%;">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
      </div>
    </div>
</div>
</div>

<div class="modal fade" id="delete{{$userID}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Confirm Delete User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4>This will delete your account and you will no longer be able to log in. <br>Are you sure you wish to delete your account?<h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <form action="/user/profile/delete/{{$userID}}" method="POST">
            @csrf
          <button type="submit" class="btn btn-danger">Delete Account</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
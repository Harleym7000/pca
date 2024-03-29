@extends('layouts.app')
@section('content')
<div id="show-event">
  @include('partials.alerts')
    {{-- @foreach($events as $event)
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @foreach($images as $i)
          <div class="carousel-item @if ($loop->first) active @endif">
              <img src="/storage/event_images/{{$i->image}}" class="d-block w-100">
          </div>
          @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
      </div>
</div>
@endforeach --}}
    <div class="show-event-title">
      @foreach($events as $event)
      <h1 class="text-center">{{$event->title}}</h1>
      <h4 class="text-center">{{$event->description}}</h4>
    </div>
    <div class="show-event-info pl-2 pr-2">
      <div class="row">
          <div class="col-12 col-lg-1"></div>
          <div class="col-12 col-md-6 col-lg-5">
              <img src="/storage/event_images/{{$event->image}}" style="height: 330px; width:auto;" @if($eventHappened ?? '' === 1) class="mb-5" @endif>
          </div>
          {{-- <div class="col-12 col-md-6 col-lg-2"></div> --}}
          <div class="col-12 col-md-6 col-lg-5">
            <h3> <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('D jS M Y')}}</h3>
            <br>
            <h3> <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('g:ia')}}</h3>
            <br>
            <h3> <strong>Venue:</strong> {{$event->venue}}</h3>
            <br>
            <h3> <strong>Organiser:</strong> {{$event->managed_by}}</h3>
            <br>
            <h3 @if($eventHappened ?? '' === 1) style="display:none;" @endif @if($event->admission == '£0.00') style="color: green" @endif><strong>Admission:</strong>@if($event->admission == '£0.00') Free @else {{$event->admission}} @endif</h3>
            <br>
            <h3 @if($eventHappened ?? '' === 1) style="display:none;" @endif @if($event->spaces_left > 0) style="color: green" @else style="color: red" @endif><strong>Spaces Left:</strong> {{$event->spaces_left}}</h3>
          </div>
          <div class="col-12 col-lg-1"></div>
      </div>
      <div class="text-center">
      <button @if($eventHappened ?? '' === 1) style="display:none;" @endif class="btn btn-primary" type="button" data-toggle="modal" data-target="#event{{$event->id}}" @if($event->spaces_left == 0) disabled data-toggle="tooltip" data-placement="bottom" title="This event is full" style="cursor: not-allowed;" @endif>Register</button>
    </div>
</div>
</div>
</div>

@if($eventHappened ?? '' === 1)
@if(count($images) > 0)
<div id="event-images">
  <h1 class="text-center mt-3 mb-5">Event Images</h1>
  <div class="container mb-5">
    <div class="row gallery">
      @foreach($images as $i)

<a href="/storage/event_images/{{ $i->image_path }}" class="col-12 col-md-6 col-lg-3 mb-4">
  <img src="/storage/event_images/{{ $i->image_path }}" class="event_image" style="width: 100%; height: 300px; object-fit:cover;" alt="Event Image">
</a>
    @endforeach
    @endif
  </div>
  </div>
</div>
@endif
        <!-- Modal -->


<div class="modal fade" id="event{{$event->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$event->title}} Event Registration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          @auth
                                <form class="userEventReg" action="/events/register" method="POST">
                                  @csrf
                                  <div class="form-row">
                                    <label class="col">First Name:</label>
                                    <label class="col">Surname:</label>
                                  </div>
                                  <div class="form-row">
                                    <div class="col">
                                      <input type="text" name="forename" class="form-control" value="{{\Crypt::decrypt(Auth::user()->profile()->pluck('firstname'))}}"placeholder="First name">
                                    </div>
                                    <div class="col">
                                      <input type="text" name="surname" class="form-control" value="{{\Crypt::decrypt(Auth::user()->profile()->pluck('surname'))}}"placeholder="Last name">
                                    </div>
                                  </div>
                                  <div class="form-row">
                                    <label class="col">Email Address:</label>
                                  </div>
                                  <div class="form-row">
                                    <div class="col">
                                      <input type="text" name="email" class="form-control" value="{{Auth::user()->email}}" placeholder="Email Address">
                                    </div>
                                  </div>
                                  <div class="form-row">
                                    <label class="col">Contact Number:</label>
                                  </div>
                                  <div class="form-row">
                                    <div class="col">
                                      <input type="text" name="phone" class="form-control" value="{{\Crypt::decrypt(Auth::user()->profile()->pluck('contact_no'))}}" placeholder="Contact Number">
                                    </div>
                                  </div>
                              <div class="form-check">
                                <div class="col">
                                <input type="checkbox" class="form-check-input" value="{{auth()->user()->id}}" name="UID" id="userRegID">
                                <label class="form-check-label" for="exampleCheck1">This information is correct</label>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" name="EID" value="{{$event->id}}">Register</button>
                              </div>
                            </form>
      @endauth
          @guest
          <form action="/events/register/guest" method="POST">
            @csrf
          <div class="form-row">
            <label class="col">First Name:</label>
            <label class="col">Surname:</label>
          </div>
          <div class="form-row">
            <div class="col">
              <input type="text" name="forename" class="form-control" placeholder="First name">
            </div>
            <div class="col">
              <input type="text" name="surname" class="form-control" placeholder="Last name">
            </div>
          </div>
          <div class="form-row">
            <label class="col">Email Address:</label>
          </div>
          <div class="form-row">
            <div class="col">
              <input type="text" name="email" class="form-control" placeholder="Email Address">
            </div>
          </div>
          <div class="form-row">
            <label class="col">Contact Number:</label>
          </div>
          <div class="form-row">
            <div class="col">
              <input type="text" name="phone" class="form-control" placeholder="Contact Number">
            </div>
          </div>
      <br>
<div class="form-row">
        <div class="g-recaptcha @error('recaptcha') is-invalid @enderror" data-sitekey="6LeWLL8ZAAAAALOKCQHnNaPioxOzVeF3VTBLiCUS" name="recapctha"></div>
        @error('recaptcha')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
</div>
      <input type="hidden" name="eventID" value="{{$event->id}}">
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Register</button>
      </form>
      @endguest
      </div>
    </div>
  </div>
</div>
</div>
@endforeach
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
<script>
    lightGallery(document.querySelector('.gallery'));
    </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-dateFormat/1.0/jquery.dateFormat.min.js" integrity="sha512-YKERjYviLQ2Pog20KZaG/TXt9OO0Xm5HE1m/OkAEBaKMcIbTH1AwHB4//r58kaUDh5b1BWwOZlnIeo0vOl1SEA==" crossorigin="anonymous"></script>
                          <script>
                                                  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

                            $(document).ready(function(){
                                $(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

                              $('.eventRegUser').click(function() {
                                var eventID = $(this).val();
                                var userID = $('#userRegID').val();
                                //alert('Event ID ' + eventID + 'User ID ' + userID);
                                $.ajax({
                                  type: 'POST',
                                  url: '/events/register',
                                  headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                  data: {EID: eventID, UID: userID},
                                  dataType: 'json',
                                  success: function(data) {
                                    console.log('success');
                                  }
                                });
                              });
                            });
</script>

<script>
    $(document).ready(function(){
        $('.carousel').carousel();
    });
    </script>

        @endsection
  </body>

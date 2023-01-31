@extends('layouts.app')
@section('content')
<div id="red-sails">
        <div id="rs-images">
                </div>
                <h1 class="text-center mt-5">Red Sails Festival</h1>
                <h2 class="text-center mt-2">What's On?</h2>

<h4 class="text-center mt-5">Select a Date to view the day's events</h4>
        <div class="col-12">
        <ul class="nav nav-pills justify-content-center mt-3">
        @foreach($festivalDates as $fd)
  <li class="nav-item">
    <!-- <a class="nav-link active" href="/red-sails/{{$fd}}">{{$fd->format('D jS M Y')}}</a> -->
    <a class="{{ (request()->is('red-sails/'.$fd->format('Ymd'))) ? 'nav-item nav-link active' : 'nav-link' }}" href="/red-sails/{{$fd->format('Ymd')}}">{{$fd->format('D jS M Y')}}</a>
</li>
        @endforeach
        </ul>
        </div>
        <div class="mt-5 col-12"></div>
        @if($programmeExists != 0)
        <h2 class="text-center">Programme of Events</h2>
        @foreach($programme as $p)
        <h4 class="text-center mt-3 ml-5 mr-5">{!! htmlspecialchars_decode($p->programme) !!}</h4>
        @endforeach
        @else
        <h4 class="text-center mb-5 ml-5 mr-5">There is currently no programme of events for this date. Please check back again later</h4>
        @endif

</div>
@endsection
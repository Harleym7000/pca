@extends('layouts.app')
@section('content')
<div id="contact-us">
  @include('partials.alerts')
            <div class="card">
            <div class="card-header">Contact Us</div>
            @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        <div id="contact-form">
          <div class="col-12">
            <form id="contact" action="/contact-submit" method="POST">
              @csrf
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="firstName">First Name: <span class="asterisk">*</span></label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstName" placeholder="First Name" name="firstname" required autofocus>
                    @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  </div>
                  <div class="form-group col-md-6">
                    <label for="surname">Surname: <span class="asterisk">*</span></label>
                    <input type="text" class="form-control @error('surname') is-invalid @enderror" id="surname" placeholder="Surname" name="surname" required>
                    @error('surname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                  </div>
                </div>
                <div class="form-group">
                  <label for="emailAddress">Email Address: <span class="asterisk">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="emailAddress" placeholder="Email Address" name="email" required>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
                </div>
                <div class="form-group">
                  <label for="messageSubject">Message Subject: <span class="asterisk">*</span></label>
                  <input type="text" class="form-control @error('subject') is-invalid @enderror" id="messageSubject" placeholder="Message Subject" name="subject" required>
                  @error('subject')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
                </div>
                <div class="form-group">
                  <label for="message">Message: <span class="asterisk">*</span></label>
                  <textarea class="form-control @error('message') is-invalid @enderror" id="message" placeholder="Enter Message Here..." rows="8" name="message" required></textarea>
                  @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <div class="form-group">
                  <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="6LeWLL8ZAAAAALOKCQHnNaPioxOzVeF3VTBLiCUS" name="recapctha"></div>
                  @error('g-recaptcha-response')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
                <br>
                <div class="form-group">
                  <label>Fields marked with an <span class="asterisk">*</span> are required</label>
                </div>
                <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Send</button>
                </div>
                </div>
            </div>
</div>
              </form>
</div>
</div>
</div>
</div>
@endsection

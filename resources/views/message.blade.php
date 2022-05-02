@extends('layout')

@section('content')
<br/><br/><br/>
<div class="jumbotron"><center>
    <p class="lead">The operation results says.</p>
    <h1 class="display-4">{{ $response['message'] }}</h1>
    <hr class="my-4">
    <p>click the button to be redirected.</p>
    <br/>
    <p class="lead">
      <a class="btn btn-primary btn-lg" href="{{ $response['link'] }}" role="button">Done</a>
    </p>
</center></div>
@endsection

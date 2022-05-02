@extends('layout')

@section('content')
<br/><br/><br/>
<center>
    <h1 class="display-1">Login Here.</h1>
    <p class="lead">This is a simple intro for the RSB Hands-on.</p>
    <hr class="my-4"><br/>

    <form method="post" action="login">
        @csrf
        <div class="form-group">
            <p>Email</p>
            <input type="email" name="email" class="form-control" placeholder="Enter Email..." required/>
        </div>
        <br/>
        <div class="form-group">
            <p>Password</p>
            <input type="password" name="password" class="form-control" placeholder="Enter Password..." required/>
        </div>
        <br/><br/>
        <button type="submit" class="btn btn-primary btn-lg">LOGIN NOW</button>
    </form>
</center>
@endsection

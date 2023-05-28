@extends('layouts.app')

@section('content')

    <div class="form-signin mt-5">
        <form action="{{route('login')}}" method="post">
            @csrf
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

            <div class="form-floating">
                <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>

{{--            <div class="checkbox mb-3">--}}
{{--                <label>--}}
{{--                    <input type="checkbox" value="remember-me"> Remember me--}}
{{--                </label>--}}
{{--            </div>--}}
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </div>
@endsection

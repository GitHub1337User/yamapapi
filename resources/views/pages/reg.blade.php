@extends('layouts.app')

@section('content')

    <div class="form-signin mt-5">
        <form action="{{route('register')}}" method="post">
            @csrf
            <h1 class="h3 mb-3 fw-normal">Please create an account</h1>

            <div class="form-floating">
                <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-floating">
                <input name="password_confirmation" type="password" class="form-control" id="floatingRepeat" placeholder="Repeat password" required>
                <label for="floatingRepeat">Repeat password</label>
            </div>


            <button class="w-100 btn btn-lg btn-primary" type="submit">Create account</button>

        </form>
    </div>
@endsection

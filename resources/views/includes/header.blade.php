<header>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{route('main')}}">YaMap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 @auth
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{route('map')}}">{{Auth::user()->email}}</a>
                        </li>
                    @endauth

                </ul>
                <div class="d-flex">

                    @auth
                        <a   class="btn btn-outline-danger" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" hidden>
                            @csrf
                        </form>

                    @else
                        <a class="btn btn-outline-success" href="{{route('regIndex')}}">Create an account</a>
                        <a class="btn btn-outline-warning" href="{{route('loginIndex')}}">Sign-In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

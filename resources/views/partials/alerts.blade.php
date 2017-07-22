@if(Session::has('success'))
    <div class="container">
        <div class="alert alert-success" role="alert">

            {{ Session::get('success') }}

        </div>
    </div>
@endif

@if ($errors->all())

    <div class="container">
        <div class="alert alert-danger" role="alert">

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!} </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
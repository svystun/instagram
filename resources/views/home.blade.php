@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Search bar</div>
                <div class="panel-body">
                    <form method="GET" action="{{ route('home') }}">
                        <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                            <div class="center-block" style="width: 500px">
                                <input type="text" class="form-control" name="search"
                                       value="{{ old('search', request()->get('search')) }}"
                                       placeholder="Search user..." required autofocus>

                                @if ($errors->has('search'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('search') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(count($items))
        <div class="row">
            <div class="gallery col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1>Search results for &laquo;{{ ucfirst($user->username) }}&raquo;</h1>
            </div>
            @foreach($items as $item)
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6">

                <div class="thumb">
                    <img src="{{ $item->images->low_resolution->url }}" alt="" title="">
                    <div class="caption">
                        <h5>
                            @if(isset($item->location->name))
                                {{ $item->location->name }}
                            @else
                                No location
                            @endif
                        </h5>
                        <p>
                            <button type="button" class="label label-danger" @click="reverseMessage(`{{ $item->location->name }}`)">
                                SAVE
                            </button>
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Search &amp; filter bar</div>
                    <div class="panel-body">
                        <form method="GET" action="{{ route('gallery') }}">

                            <div class="form-group{{ $errors->has('search') ? ' has-error' : '' }}">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="search"
                                           value="{{ old('search', request()->get('search')) }}"
                                           placeholder="Search by caption..." required autofocus>

                                    @if ($errors->has('search'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('search') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-2 col-md-offset-5">
                                    {!! Form::select('select', ['S' => '-- Select filter --', 'L' => 'Large', 'XL' => 'Extra Large'],  'S', ['class' => 'form-control' ]) !!}
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
                    <h1>My Gallery</h1>
                </div>
                @foreach($items as $item)
                    <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6" id="{{ $item->id }}">

                        <div class="thumb" id="{{ $item->id }}">
                            <img src="{{ $item->insta_url }}" class="image-url" alt="" title="">
                            <div class="caption">
                                <h5 class="location-name">
                                    @if(isset($item->insta_name))
                                        {{ $item->insta_name }}
                                    @else
                                        No location
                                    @endif
                                </h5>
                                <p>
                                    <button v-if="!loading" type="button" class="label label-danger" @click="deletePost(`{{ $item->id }}`)">
                                        DELETE
                                    </button>
                                    <span v-if="loading"><img src="{{ asset('images/loading.gif') }}"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
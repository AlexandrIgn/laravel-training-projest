@extends('layouts.app')

@section('content')

    @if ($advert->isDraft())
        <div class="alert alert-danger">
            It is a draft.
        </div>
        @if ($advert->reject_reason)
            <div class="alert alert-danger">
                {{ $advert->reject_reason }}
            </div>
        @endif
    @endif

    @can ('manage-adverts')
        <div class="d-flex flex-row mb-3">
            <a href="{{ route('admin.adverts.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
            <a href="{{ route('admin.adverts.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>

            @if ($advert->isOnModeration())
                <form method="POST" action="{{ route('admin.adverts.adverts.moderate', $advert) }}" class="mr-1">
                    @csrf
                    <button class="btn btn-success">Moderate</button>
                </form>
            @endif

            @if ($advert->isOnModeration() || $advert->isActive())
                <a href="{{ route('admin.adverts.adverts.reject', $advert) }}" class="btn btn-danger mr-1">Reject</a>
            @endif

            <form method="POST" action="{{ route('admin.adverts.adverts.destroy', $advert) }}" class="mr-1">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        </div>
    @endcan

    @can ('manage-own-advert', $advert)
            <div class="d-flex flex-row mb-3">
                <a href="{{ route('cabinet.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
                <a href="{{ route('cabinet.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>

                @if ($advert->isDraft())
                    <form method="POST" action="{{ route('cabinet.adverts.send', $advert) }}" class="mr-1">
                        @csrf
                        <button class="btn btn-success">Publish</button>
                    </form>
                @endif
                @if ($advert->isActive())
                    <form method="POST" action="{{ route('cabinet.adverts.close', $advert) }}" class="mr-1">
                        @csrf
                        <button class="btn btn-success">Close</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="mr-1">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
    @endcan

    <div class="row">
        <div class="col-md-9">

            <p class="float-right" style="font-size: 36px;">{{ $advert->price }}</p>
            <h1 style="margin-bottom: 10px">{{ $advert->title  }}</h1>
            <p>
                @if ($advert->expires_at)
                    Date: {{ $advert->published_at }} &nbsp;
                @endif
                @if ($advert->expires_at)
                    Expires: {{ $advert->expires_at }}
                @endif
            </p>

            <p>{!! nl2br(e($advert->content)) !!}</p>

            <table class="table table-bordered">
                <tbody>
                @foreach ($advert->category->allAttributes() as $attribute)
                    <tr>
                        <th>{{ $attribute->name }}</th>
                        <td>{{ $advert->getValue($attribute->id) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p>Address: {{ $advert->address }}</p>

            <div style="margin: 20px 0; border: 1px solid #ddd">
                <div id="map" style="width: 100%; height: 250px"></div>
            </div>

            <p style="margin-bottom: 20px">Seller: {{ $advert->user->name }}</p>

            <hr/>

@endsection

@section('scripts')
    <script src="//api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU" type="text/javascript"></script>

    <script type='text/javascript'>
        ymaps.ready(init);
        function init(){
            var geocoder = new ymaps.geocode(
                '{{ $advert->address }}',
                { results: 1 }
            );
            geocoder.then(
                function (res) {
                    var coord = res.geoObjects.get(0).geometry.getCoordinates();
                    var map = new ymaps.Map('map', {
                        center: coord,
                        zoom: 7,
                        behaviors: ['default', 'scrollZoom'],
                        controls: ['mapTools']
                    });
                    map.geoObjects.add(res.geoObjects.get(0));
                    map.zoomRange.get(coord).then(function(range){
                        map.setCenter(coord, range[1] - 1)
                    });
                    map.controls.add('mapTools')
                        .add('zoomControl')
                        .add('typeSelector');
                }
            );
        }
    </script>
@endsection
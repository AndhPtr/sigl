@extends('layouts.app', [
'class' => '',
'elementActive' => 'stores',
'pageTitle' => 'Edit Store'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Edit Store</h5>
                    <p class="card-category">Update the store details and adjust its location on the map.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('stores.update', $store->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Store Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $store->address) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="lat">Latitude</label>
                            <input type="text" class="form-control" id="lat" name="lat" value="{{ old('lat', $store->lat) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="lng">Longitude</label>
                            <input type="text" class="form-control" id="lng" name="lng" value="{{ old('lng', $store->lng) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="map">Map</label>
                            <div id="map" style="height: 400px; border: 1px solid #ddd;"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Store</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function initMap() {
        // Set initial map center to the store's coordinates
        var storeLocation = { 
            lat: parseFloat("{{ old('lat', $store->lat) }}"), 
            lng: parseFloat("{{ old('lng', $store->lng) }}" )
        };

        // Initialize the map
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: storeLocation
        });

        // Create a marker at the store's location
        var marker = new google.maps.Marker({
            position: storeLocation,
            map: map,
            draggable: true
        });

        // Update form inputs when the marker is dragged
        marker.addListener('dragend', function(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('lng').value = event.latLng.lng();
        });

        // Allow user to click on the map to set marker position
        map.addListener('click', function(event) {
            var clickedLatLng = event.latLng;

            // Move the marker to the clicked location
            marker.setPosition(clickedLatLng);

            // Update the form inputs
            document.getElementById('lat').value = clickedLatLng.lat();
            document.getElementById('lng').value = clickedLatLng.lng();
        });

        // Update marker position when lat or lng inputs are changed manually
        document.getElementById('lat').addEventListener('input', updateMarkerPosition);
        document.getElementById('lng').addEventListener('input', updateMarkerPosition);

        function updateMarkerPosition() {
            var lat = parseFloat(document.getElementById('lat').value);
            var lng = parseFloat(document.getElementById('lng').value);

            if (isNaN(lat) || isNaN(lng)) return;

            var newLatLng = { lat: lat, lng: lng };

            // Center the map and move the marker
            map.setCenter(newLatLng);
            marker.setPosition(newLatLng);
        }
    }
</script>
@endpush

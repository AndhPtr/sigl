@extends('layouts.app', [
'class' => '',
'elementActive' => 'stores',
'pageTitle' => 'Add New Store'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Add New Store</h5>
                    <p class="card-category">Fill in the store details and set its location on the map.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('stores.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Store Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="lat">Latitude</label>
                            <input type="text" class="form-control" id="lat" name="lat" required>
                        </div>
                        <div class="form-group">
                            <label for="lng">Longitude</label>
                            <input type="text" class="form-control" id="lng" name="lng" required>
                        </div>
                        <div class="form-group">
                            <label for="map">Map</label>
                            <div id="map" style="height: 400px; border: 1px solid #ddd;"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Store</button>
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
        // Default map center
        var defaultLocation = { lat: -0.033770, lng: 109.336154 };

        // Initialize the map
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: defaultLocation
        });

        // Create a marker variable to be reused
        var marker;

        // Add click event listener on the map
        map.addListener('click', function(event) {
            // Get lat and lng from the event
            var clickedLatLng = event.latLng;

            // Update form inputs
            document.getElementById('lat').value = clickedLatLng.lat();
            document.getElementById('lng').value = clickedLatLng.lng();

            // Place or move the marker
            if (!marker) {
                marker = new google.maps.Marker({
                    position: clickedLatLng,
                    map: map
                });
            } else {
                marker.setPosition(clickedLatLng);
            }
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
            if (!marker) {
                marker = new google.maps.Marker({
                    position: newLatLng,
                    map: map
                });
            } else {
                marker.setPosition(newLatLng);
            }
        }
    }
</script>
@endpush

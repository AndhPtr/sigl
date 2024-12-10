@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'transactions',
    'pageTitle' => 'Edit Transaction',
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Transaction</h5>
                        <p class="card-category">Modify the details of the selected transaction.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transactions.update', $transactions->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="barcode">Barcode</label>
                                <input type="text" class="form-control" id="barcode" name="barcode"
                                    value="{{ $transactions->product->barcode }}" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $transactions->product->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="store_name">Store Name</label>
                                <input type="text" class="form-control" id="store_name" name="store_name"
                                    value="{{ $transactions->store->name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Store Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ $transactions->store->address }}" required>
                            </div>

                            <div class="form-group">
                                <label for="purchase_date">Purchase Date</label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                                    value="{{ $transactions->purchase_date }}" required>
                            </div>

                            <div class="form-group">
                                <label for="lat">Latitude</label>
                                <input type="text" class="form-control" id="lat" name="lat"
                                    value="{{ $transactions->lat }}" required>
                            </div>

                            <div class="form-group">
                                <label for="lng">Longitude</label>
                                <input type="text" class="form-control" id="lng" name="lng"
                                    value="{{ $transactions->lng }}" required>
                            </div>

                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" name="price"
                                    value="{{ $transactions->price }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Transaction</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const latitudeInput = document.getElementById("lat");
        const longitudeInput = document.getElementById("lng");
        const storeNameInput = document.getElementById("store_name");
        const addressInput = document.getElementById("address");
        const storeLatInput = document.getElementById("store_lat");
        const storeLngInput = document.getElementById("store_lng");
        let userCreatedMarker = null; // Marker created by user clicks on the map
        let map;
        let userLocationMarker;
        let markers = []; // Store markers for registered stores
        const registeredStores = @json($stores); // Preloaded registered store data
        const transactionStoreId = @json($transactions->stores_id); // Store ID associated with the transaction

        function initMap() {
            // Initialize map with a default position
            map = new google.maps.Map(document.getElementById("store-map"), {
                zoom: 13,
                center: {
                    lat: parseFloat(storeLatInput.value) || 0,
                    lng: parseFloat(storeLngInput.value) || 0,
                },
            });

            // Highlight the store associated with the transaction, if it's a registered store
            registeredStores.forEach((store) => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(store.lat),
                        lng: parseFloat(store.lng),
                    },
                    map: map,
                    title: store.name,
                });

                // Check if the store matches the transaction's store and highlight it
                if (store.id == transactionStoreId) {
                    marker.setIcon({
                        url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png", // Highlighted marker
                        scaledSize: new google.maps.Size(40, 40),
                    });
                    map.setCenter(marker.getPosition()); // Center map to the highlighted marker
                }

                // Add click listener to populate form fields
                marker.addListener("click", () => {
                    storeNameInput.value = store.name;
                    addressInput.value = store.address;
                    storeLatInput.value = store.lat;
                    storeLngInput.value = store.lng;

                    // Optionally highlight the clicked marker
                    markers.forEach((m) => m.setIcon(null)); // Reset other markers
                    marker.setIcon({
                        url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png", // Highlighted marker
                        scaledSize: new google.maps.Size(40, 40),
                    });

                    // Remove any user-created marker
                    if (userCreatedMarker) {
                        userCreatedMarker.setMap(null);
                        userCreatedMarker = null;
                    }
                });

                markers.push(marker);
            });

            // Add marker for user-created location (custom store location)
            if (storeLatInput.value && storeLngInput.value && !transactionStoreId) {
                userCreatedMarker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(storeLatInput.value),
                        lng: parseFloat(storeLngInput.value),
                    },
                    map: map,
                    title: "Custom Location",
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                        scaledSize: new google.maps.Size(40, 40),
                    },
                });
                map.setCenter(userCreatedMarker.getPosition()); // Center map to the user-created marker
            }

            // Allow user to click on the map to create a new marker
            map.addListener("click", (event) => {
                const clickedLat = event.latLng.lat();
                const clickedLng = event.latLng.lng();

                // Clear store fields for a custom location
                storeNameInput.value = "";
                addressInput.value = "";
                storeLatInput.value = clickedLat;
                storeLngInput.value = clickedLng;

                // Reset marker icons
                markers.forEach((m) => m.setIcon(null));

                // Remove any existing user-created marker
                if (userCreatedMarker) {
                    userCreatedMarker.setMap(null);
                }

                // Create a new marker at the clicked location
                userCreatedMarker = new google.maps.Marker({
                    position: {
                        lat: clickedLat,
                        lng: clickedLng,
                    },
                    map: map,
                    title: "Selected Location",
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                        scaledSize: new google.maps.Size(40, 40),
                    },
                });
            });
        }

        // Load the Google Maps script asynchronously
        document.addEventListener("DOMContentLoaded", function() {
            const script = document.createElement("script");
            script.src =
                `https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGBjlEnlrlO2KdsQMFL70E_Ppo3GmFPs&callback=initMap`;
            script.async = true;
            document.body.appendChild(script);
        });
    </script>
@endpush

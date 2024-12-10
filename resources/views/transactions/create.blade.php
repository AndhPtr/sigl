@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'transactions',
    'pageTitle' => 'Add New Transactions',
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add New Product</h5>
                        <p class="card-category">Fill in the product details with the barcode attached to it.</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transactions.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="barcode">Barcode</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="barcode" name="barcode" required>
                                </div>
                            </div>

                            <!-- Scan Button Below Barcode Input -->
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary" id="scan-barcode">Scan Barcode</button>
                                <button type="button" id="stop-scanner" class="btn btn-danger">Stop Scanner</button>
                            </div>

                            <!-- Scanner and Stop Button -->
                            <div id="scanner" style="display: none; margin-top: 20px;">
                                <div id="barcode-scanner" style="width: 100%;"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>

                                {{-- hidden product id input --}}
                                <input type="hidden" name="products_id" id="products_id">
                            </div>
                            <div class="form-group">
                                <label for="store-map">Select Store Location</label>
                                <div id="store-map" style="width: 100%; height: 400px;"></div>
                            </div>
                            <div class="form-group">
                                <label for="store_name">Store Name</label>
                                <input type="text" class="form-control" id="store_name" name="store_name" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Store Address</label>
                                <input type="text" class="form-control" id="address" name="address" required>
                            </div>
                            <div class="form-group">
                                <label for="store_lat">Store Latitude</label>
                                <input type="text" class="form-control" id="store_lat" name="store_lat" readonly
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="store_lng">Store Longitude</label>
                                <input type="text" class="form-control" id="store_lng" name="store_lng" readonly
                                    required>
                            </div>

                            {{-- hidden store id input --}}
                            <input type="hidden" name="stores_id" id="stores_id">

                            <div class="form-group">
                                <label for="purchase_date">Purchase Date</label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date" required>
                            </div>
                            <div class="form-group">
                                <label for="lat">Latitude</label>
                                <input type="text" class="form-control" id="lat" name="lat" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="lng">Longitude</label>
                                <input type="text" class="form-control" id="lng" name="lng" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control" id="price" name="price" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Transaction</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <script>
        const scanButton = document.getElementById("scan-barcode");
        const stopButton = document.getElementById("stop-scanner");
        const scannerDiv = document.getElementById("scanner");
        const barcodeInput = document.getElementById("barcode");
        const productNameInput = document.getElementById("name");
        const latitudeInput = document.getElementById("lat");
        const longitudeInput = document.getElementById("lng");
        const ProductIdInput = document.getElementById("products_id");
        let html5QrCode;

        // Preloaded product data
        const products = @json($products);

        // Start the barcode scanner
        scanButton.addEventListener("click", function() {
            scannerDiv.style.display = "block";
            html5QrCode = new Html5Qrcode("barcode-scanner");

            html5QrCode.start({
                    facingMode: "environment"
                }, // Use the back camera
                {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                (decodedText) => {
                    barcodeInput.value = decodedText;
                    searchProductLocally(decodedText);
                    stopScanner();
                },
                (errorMessage) => console.warn(`Scan error: ${errorMessage}`)
            ).catch((err) => console.error("Failed to start scanner:", err));
        });

        // Stop the barcode scanner
        stopButton.addEventListener("click", function() {
            stopScanner();
        });

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    scannerDiv.style.display = "none";
                }).catch((err) => console.error("Failed to stop scanner:", err));
            }
        }

        // Search product based on barcode
        function searchProductLocally(barcode) {
            const product = products.find(p => p.barcode === barcode);
            if (product) {
                productNameInput.value = product.name;
                ProductIdInput.value = product.id; // Assign product ID to the hidden input
            } else {
                productNameInput.value = "";
                ProductIdInput.value = ""; // Clear product ID if not found
                alert("Product not found. Please fill in the details manually.");
            }
        }

        // Get user's current location
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latitudeInput.value = position.coords.latitude;
                        longitudeInput.value = position.coords.longitude;
                    },
                    (error) => {
                        console.error("Error getting location:", error.message);
                        alert(
                            "Unable to fetch your location. Please enable location services and refresh the page."
                        );
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Trigger location fetching on page load
        document.addEventListener("DOMContentLoaded", function() {
            getCurrentLocation();
        });

        const storeNameInput = document.getElementById("store_name");
        const suggestionsBox = document.getElementById("store-suggestions");

        // Fetch store suggestions on user input
        storeNameInput.addEventListener("input", function() {
            const query = storeNameInput.value;

            if (query.length > 1) {
                fetch(`/api/store-suggestions?query=${encodeURIComponent(query)}`)
                    .then((response) => response.json())
                    .then((data) => {
                        // Clear previous suggestions
                        suggestionsBox.innerHTML = "";

                        if (data.length > 0) {
                            suggestionsBox.style.display = "block";

                            data.forEach((store) => {
                                const suggestionItem = document.createElement("li");
                                suggestionItem.className = "list-group-item list-group-item-action";
                                suggestionItem.textContent = store.name;

                                // On click, fill input with selected store name
                                suggestionItem.addEventListener("click", function() {
                                    storeNameInput.value = store.name;
                                    suggestionsBox.style.display = "none";
                                });

                                suggestionsBox.appendChild(suggestionItem);
                            });
                        } else {
                            suggestionsBox.style.display = "none";
                        }
                    })
                    .catch((error) => console.error("Error fetching store suggestions:", error));
            } else {
                suggestionsBox.style.display = "none";
            }
        });

        // Search store dynamically and handle stores_id
        storeNameInput.addEventListener("blur", function() {
            const storeName = storeNameInput.value;

            if (storeName) {
                fetch(`/api/store-search?name=${encodeURIComponent(storeName)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            // If store exists, populate the hidden stores_id field
                            document.getElementById('stores_id').value = data.store.id;
                        } else {
                            // Clear stores_id if no match
                            document.getElementById('stores_id').value = "";
                        }
                    })
                    .catch(error => console.error("Error checking store:", error));
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener("click", function(e) {
            if (!suggestionsBox.contains(e.target) && e.target !== storeNameInput) {
                suggestionsBox.style.display = "none";
            }
        });

        let map;
        let userLocationMarker;
        let markers = []; // Store markers for registered stores
        let userCreatedMarker = null; // Marker created by user clicks on the map
        const registeredStores = @json($stores); // Preloaded registered store data

        function initMap() {
            // Initialize map with a default position
            map = new google.maps.Map(document.getElementById("store-map"), {
                zoom: 13,
                center: {
                    lat: 0,
                    lng: 0
                }, // Set to a default location initially
            });

            // Try to get the user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;

                        // Center the map to the user's current location
                        map.setCenter(new google.maps.LatLng(userLat, userLng));

                        // Place a marker at the user's location
                        userLocationMarker = new google.maps.Marker({
                            position: {
                                lat: userLat,
                                lng: userLng,
                            },
                            map: map,
                            title: "Your Location",
                            icon: {
                                url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png", // Blue marker for user
                                scaledSize: new google.maps.Size(40, 40), // Size of the marker
                            },
                        });
                    },
                    (error) => {
                        console.error("Error getting location:", error.message);
                        alert(
                            "Unable to fetch your location. Please enable location services and refresh the page."
                        );
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            // Load registered store markers
            registeredStores.forEach((store) => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(store.lat),
                        lng: parseFloat(store.lng),
                    },
                    map: map,
                    title: store.name,
                });

                // On marker click, fill in form fields
                marker.addListener("click", () => {
                    document.getElementById("store_name").value = store.name;
                    document.getElementById("address").value = store.address;
                    document.getElementById("store_lat").value = store.lat;
                    document.getElementById("store_lng").value = store.lng;

                    // Optionally highlight the marker
                    markers.forEach((m) => m.setIcon(null)); // Reset other markers
                    marker.setIcon({
                        url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png", // Highlighted marker
                        scaledSize: new google.maps.Size(40, 40),
                    });

                    // Remove any user-created marker when clicking a predefined marker
                    if (userCreatedMarker) {
                        userCreatedMarker.setMap(null);
                        userCreatedMarker = null;
                    }
                });

                markers.push(marker);
            });

            // Add click listener for map to create a marker
            map.addListener("click", (event) => {
                const clickedLat = event.latLng.lat();
                const clickedLng = event.latLng.lng();

                // Clear store name since it's not a registered store
                document.getElementById("store_name").value = "";
                document.getElementById("address").value = "";
                document.getElementById("store_lat").value = clickedLat;
                document.getElementById("store_lng").value = clickedLng;

                // Reset all predefined store marker icons
                markers.forEach((m) => m.setIcon(null));

                // Remove existing user-created marker, if any
                if (userCreatedMarker) {
                    userCreatedMarker.setMap(null);
                }

                // Create a new marker at the clicked location
                userCreatedMarker = new google.maps.Marker({
                    position: {
                        lat: clickedLat,
                        lng: clickedLng
                    },
                    map: map,
                    title: "Selected Location",
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png", // Red marker for user-created
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

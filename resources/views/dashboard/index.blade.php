@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard',
    'pageTitle' => 'Dashboard',
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Store Map</h5>
                        <p class="card-category">Explore stores around your location</p>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 500px;"></div>
                        <div class="mt-3">
                            <label for="radiusRange">Adjust Radius: <span id="radiusValue">0</span> meters</label>
                            <input type="range" id="radiusRange" min="0" max="5000" step="100"
                                value="0" class="form-range">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cheapest Product Container -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Cheapest Product Finder</h5>
                        <p class="card-category">Select a product to find the cheapest transaction</p>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="productSelect">Choose a Product:</label>
                            <select id="productSelect" class="form-control">
                                <option value="">Select a product...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="timeFilter">Filter by Date:</label>
                            <select id="timeFilter" class="form-control">
                                <option value="">Select a time period...</option>
                                <option value="today">Today</option>
                                <option value="lastWeek">Last Week</option>
                                <option value="lastMonth">Last Month</option>
                                <option value="last6Months">Last 6 Months</option>
                                <option value="lastYear">Last Year</option>
                            </select>
                        </div>
                        <div id="cheapestTransaction" style="display: none;">
                            <h6>Cheapest Transaction Details:</h6>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Store Name</th>
                                        <th>Address</th>
                                        <th>Coordinates</th>
                                        <th>Price</th>
                                        <th>Purchase Date</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionDetails">
                                    <!-- Rows will be dynamically populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let map;
        let userMarker = null;
        let radiusCircle = null;
        const stores = @json($stores); // Store data from the database
        const markers = [];

        function initMap() {
            // Initialize map centered at a default location
            const defaultLocation = {
                lat: -0.033770,
                lng: 109.336154
            };
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: defaultLocation
            });

            // Get user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        // Center the map on user's location
                        map.setCenter(userLocation);

                        // Add a marker for the user's location
                        userMarker = new google.maps.Marker({
                            position: userLocation,
                            map: map,
                            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png', // Blue marker for the user
                            title: "Your Location"
                        });

                        // Draw radius circle (default to 0 meters)
                        radiusCircle = new google.maps.Circle({
                            center: userLocation,
                            radius: 0,
                            map: map,
                            fillColor: "#2196F3",
                            fillOpacity: 0.3,
                            strokeColor: "#1976D2",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                        });

                        // Add all markers for stores
                        addStoreMarkers();

                        // Set up the radius slider
                        setupRadiusSlider(userLocation);
                    },
                    () => {
                        alert("Unable to retrieve your location.");
                        addStoreMarkers(); // Show all stores by default
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
                addStoreMarkers(); // Show all stores by default
            }
        }

        function addStoreMarkers() {
            stores.forEach((store) => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(store.lat),
                        lng: parseFloat(store.lng)
                    },
                    map: map,
                    title: store.name
                });

                const infowindow = new google.maps.InfoWindow({
                    content: `<div>
                            <h6>${store.name}</h6>
                            <p>${store.address}</p>
                          </div>`
                });

                marker.addListener('click', () => {
                    infowindow.open(map, marker);
                });

                markers.push(marker);
            });
        }

        function setupRadiusSlider(userLocation) {
            const radiusRange = document.getElementById('radiusRange');
            const radiusValue = document.getElementById('radiusValue');

            radiusRange.addEventListener('input', (event) => {
                const radius = parseInt(event.target.value, 10);
                radiusValue.innerText = radius;

                // Update the circle radius
                radiusCircle.setRadius(radius);

                // Filter markers based on the radius
                filterMarkers(userLocation, radius);
            });
        }

        function filterMarkers(userLocation, radius) {
            const userLatLng = new google.maps.LatLng(userLocation.lat, userLocation.lng);

            markers.forEach((marker) => {
                const markerLatLng = new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng());
                const distance = google.maps.geometry.spherical.computeDistanceBetween(userLatLng, markerLatLng);

                if (distance <= radius || radius === 0) {
                    marker.setMap(map); // Show marker if within radius or if radius is 0
                } else {
                    marker.setMap(null); // Hide marker
                }
            });
        }
        // Function to handle the product selection and fetch the cheapest transaction
        const transactions = @json($transactions);

        document.getElementById('productSelect').addEventListener('change', function() {
            const productId = this.value;
            const timeFilter = document.getElementById('timeFilter').value;

            if (productId) {
                let productTransactions = transactions.filter(t => t.products_id == productId);

                // Filter based on the selected time period
                if (timeFilter) {
                    const filteredTransactions = productTransactions.filter(t => {
                        const transactionDate = new Date(t.purchase_date);
                        const currentDate = new Date();

                        switch (timeFilter) {
                            case 'today':
                                return transactionDate.toDateString() === currentDate.toDateString();
                            case 'lastWeek':
                                const oneWeekAgo = new Date();
                                oneWeekAgo.setDate(currentDate.getDate() - 7);
                                return transactionDate >= oneWeekAgo;
                            case 'lastMonth':
                                const oneMonthAgo = new Date();
                                oneMonthAgo.setMonth(currentDate.getMonth() - 1);
                                return transactionDate >= oneMonthAgo;
                            case 'last6Months':
                                const sixMonthsAgo = new Date();
                                sixMonthsAgo.setMonth(currentDate.getMonth() - 6);
                                return transactionDate >= sixMonthsAgo;
                            case 'lastYear':
                                const oneYearAgo = new Date();
                                oneYearAgo.setFullYear(currentDate.getFullYear() - 1);
                                return transactionDate >= oneYearAgo;
                            default:
                                return true; // No filtering
                        }
                    });
                    productTransactions = filteredTransactions;
                }

                if (productTransactions.length > 0) {
                    // Sort transactions by price (ascending)
                    const sortedTransactions = productTransactions.sort((a, b) => a.price - b.price);

                    // Populate the table with sorted transactions
                    const transactionDetails = document.getElementById('transactionDetails');
                    transactionDetails.innerHTML = ''; // Clear previous rows

                    sortedTransactions.forEach(transaction => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                    <td>${transaction.store_name}</td>
                    <td>${transaction.store_address}</td>
                    <td>${transaction.store_lat}, ${transaction.store_lng}</td>
                    <td>Rp. ${transaction.price}</td>
                    <td>${transaction.purchase_date}</td>
                `;
                        transactionDetails.appendChild(row);
                    });

                    // Display the transaction table
                    document.getElementById('cheapestTransaction').style.display = 'block';
                } else {
                    alert('No transaction found for the selected product and filter.');
                    document.getElementById('cheapestTransaction').style.display = 'none';
                }
            } else {
                document.getElementById('cheapestTransaction').style.display = 'none';
            }
        });

        // Listen for changes in the time filter
        document.getElementById('timeFilter').addEventListener('change', function() {
            const productId = document.getElementById('productSelect').value;
            const timeFilter = this.value;

            if (productId) {
                // Trigger product selection change to filter by time
                const event = new Event('change');
                document.getElementById('productSelect').dispatchEvent(event);
            }
        });
    </script>

    <!-- Add the Google Maps JavaScript API -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgGBjlEnlrlO2KdsQMFL70E_Ppo3GmFPs&libraries=geometry&callback=initMap"
        async defer></script>
@endpush

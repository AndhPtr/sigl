@extends('layouts.app', [
'class' => '',
'elementActive' => 'stores',
'pageTitle' => 'Store List'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Store List</h5>
                    <p class="card-category">Store Markers on Map</p>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Store Table</h4>
                    @can('create-all')
                    <a href="{{ route('stores.create') }}" class="btn btn-primary">Add New Store</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>Name</th>
                                <th>Address</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                @can('edit-all' && 'delete-all')
                                <th class="text-right">Actions</th>
                                @endcan
                            </thead>
                            <tbody>
                                @foreach ($stores as $store)
                                <tr>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->address }}</td>
                                    <td>{{ $store->lat }}</td>
                                    <td>{{ $store->lng }}</td>
                                    @can('edit-all' && 'delete-all')
                                    <td class="text-right">
                                        <!-- Edit button -->
                                        <a href="{{ route('stores.edit', $store->id) }}" class="btn btn-info btn-sm">Edit</a>

                                        <!-- Delete form -->
                                        <form action="{{ route('stores.destroy', $store->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
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
    function initMap() {
        // Set map center
        var center = { lat: -0.033770, lng: 109.336154 };
        
        // Initialize map
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: center
        });

        // Store data from the Blade template
        var stores = @json($stores);

        // Add markers for each store
        stores.forEach(function(store) {
            var marker = new google.maps.Marker({
                position: { lat: parseFloat(store.lat), lng: parseFloat(store.lng) },
                map: map,
                title: store.name
            });

            // Add an info window for the marker
            var infowindow = new google.maps.InfoWindow({
                content: `<div>
                            <h6>${store.name}</h6>
                            <p>${store.address}</p>
                            <p><strong>Lat:</strong> ${store.lat} | <strong>Lng:</strong> ${store.lng}</p>
                          </div>`
            });

            // Open the info window on marker click
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
        });
    }
</script>
@endpush

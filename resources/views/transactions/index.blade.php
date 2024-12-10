@extends('layouts.app', [
'class' => '',
'elementActive' => 'transactions',
'pageTitle' => 'Transaction List'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transactions Table</h4>
                    <a href="{{ route('transactions.create') }}" class="btn btn-primary">Add New Transaction</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>Product Barcode</th>
                                <th>Product Name</th>
                                <th>Store Name</th>
                                <th>Store Address</th>
                                <th>Purchase Date</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Price</th>
                                @can('edit-all' && 'delete-all')
                                <th class="text-right">User ID</th>
                                <th class="text-right">Username</th>
                                <th class="text-right">Actions</th>
                                @endcan
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->product->barcode }}</td>
                                    <td>{{ $transaction->product->name }}</td>
                                    <td>{{ $transaction->store->name }}</td>
                                    <td>{{ $transaction->store->address }}</td>
                                    <td>{{ $transaction->purchase_date }}</td>
                                    <td>{{ $transaction->store->lat }}</td>
                                    <td>{{ $transaction->store->lng }}</td>
                                    <td>{{ $transaction->price }}</td>
                                    @can('edit-all' && 'delete-all')
                                    <td>{{ $transaction->user->id }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td class="text-right">
                                        <!-- Edit button -->
                                        <a href="{{ route('transactions.edit', $transaction->id) }}" class="btn btn-info btn-sm">Edit</a>

                                        <!-- Delete form -->
                                        <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" style="display:inline-block;">
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
</script>
@endpush

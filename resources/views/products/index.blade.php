@extends('layouts.app', [
'class' => '',
'elementActive' => 'products',
'pageTitle' => 'product List'
])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Products Table</h4>
                    @can('create-all')
                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>Barcode</th>
                                <th>Product Name</th>
                                @can('edit-all' && 'delete-all')
                                <th class="text-right">Actions</th>
                                @endcan
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->barcode }}</td>
                                    <td>{{ $product->name }}</td>
                                    @can('edit-all' && 'delete-all')
                                    <td class="text-right">
                                        <!-- Edit button -->
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info btn-sm">Edit</a>

                                        <!-- Delete form -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
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

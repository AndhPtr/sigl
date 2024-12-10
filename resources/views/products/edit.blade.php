@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'products',
    'pageTitle' => 'Add New Product',
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
                        <form action="{{ route('products.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Product Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="barcode">Barcode</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" required>
                                </div>
                            </div>

                            <!-- Scan Button Below Barcode Input -->
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary" id="scan-barcode">Scan Barcode</button>
                            </div>

                            <!-- Scanner and Stop Button -->
                            <div id="scanner" style="display: none; margin-top: 20px;">
                                <div id="barcode-scanner" style="width: 100%;"></div>
                                <div class="d-flex justify-content-between mt-3">
                                    <button type="button" id="stop-scanner" class="btn btn-danger">Stop Scanner</button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Product</button>
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
        let html5QrCode;

        scanButton.addEventListener("click", function() {
            console.log("Starting barcode scanner...");
            scannerDiv.style.display = "block";

            // Initialize the barcode scanner
            html5QrCode = new Html5Qrcode("barcode-scanner");

            html5QrCode.start({
                    facingMode: "environment"
                }, // Use the back camera
                {
                    fps: 10, // Frames per second
                    qrbox: {
                        width: 250,
                        height: 250
                    } // Scanner viewport
                },
                (decodedText, decodedResult) => {
                    // Handle decoded result
                    console.log("Barcode Scanned:", decodedText);
                    barcodeInput.value = decodedText;
                    stopScanner(); // Stop scanner after successful scan
                },
                (errorMessage) => {
                    // Handle scanning errors
                    console.warn(`Scan error: ${errorMessage}`);
                }
            ).catch((err) => {
                console.error("Failed to start the scanner:", err);
            });
        });

        stopButton.addEventListener("click", function() {
            stopScanner(); // Stop the scanner when the stop button is clicked
        });

        function stopScanner() {
            if (html5QrCode) {
                // Stop and clear the scanner
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    scannerDiv.style.display = "none"; // Hide the scanner
                    console.log("Scanner stopped.");
                }).catch((err) => {
                    console.error("Failed to stop the scanner:", err);
                });
            }
        }
    </script>
@endpush
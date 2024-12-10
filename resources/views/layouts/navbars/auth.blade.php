<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="#" class="simple-text logo-normal">
            {{ __('SPK Pembelanjaan') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'stores' || $elementActive == 'products' || $elementActive == 'transactions' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#laravelExamples">
                    <i class="nc-icon"><img src="{{ asset('paper/img/laravel.svg') }}"></i>
                    <p>
                        {{ __('Map Management') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laravelExamples">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'stores' ? 'active' : '' }}">
                            <a href="{{ route('stores.index') }}">
                                <i class="fas fa-store"></i>
                                <p>{{ __('Store List') }}</p>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'products' ? 'active' : '' }}">
                            <a href="{{ route('products.index') }}">
                                <i class="fas fa-box"></i>
                                <p>{{ __('Product List') }}</p>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'transactions' ? 'active' : '' }}">
                            <a href="{{ route('transactions.index') }}">
                                <i class="fas fa-receipt"></i>
                                <p>{{ __('Transaction List') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="{{ $elementActive == 'user' || $elementActive == 'profile' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#laravelExamples2">
                    <i class="nc-icon"><img src="{{ asset('paper/img/laravel.svg') }}"></i>
                    <p>
                        {{ __('User Management') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laravelExamples2">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i> <!-- Font Awesome Users Icon -->
                                <p>{{ __('User List') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
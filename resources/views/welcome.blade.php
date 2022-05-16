@if(Auth::user()->role == "kasir")
    @include('kasir.dashboard')
@endif

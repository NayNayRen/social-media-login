{{-- FLASH MESSAGE ON A SET TIMEOUT TO AUTO HIDE --}}
@if(session()->has('flash-message'))
    <div class="flash-message">
        <p>{{ session('flash-message') }}</p>
    </div>
@endif

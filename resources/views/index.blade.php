@include('includes._header')
<main>
    <p>This is a test of the Socialite package in Laravel.</p>
    <p>Socialite allows for a user to log in using a social
        media outlet.</p>
    <p>Register and be redirected to continue and log in.</p>
    <p>If you've already registered, then log in using the link below.</p>
    <h2>Register</h2>
    <form action={{ route('user.register') }} method="post" class="form">
        @csrf
        <div class="form-group">
            <label for="name">Name</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span class="error-message">{{ $message }}</span><br>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label><br>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <span class="error-message">{{ $message }}</span><br>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label><br>
            <input type="password" name="password">
            @error('password')
                <span class="error-message">{{ $message }}</span><br>
            @enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label><br>
            <input type="password" name="password_confirmation">
        </div>
        @auth
            <input type="submit" class='button hide' value="Submit">
        @else
            <input type="submit" class='button' value="Submit">
            <a href={{ route('user.showLogin') }}>Log In <i class="fa fa-sign-in"
                    aria-hidden="true"></i></a>
        @endauth
    </form>
    @auth
        <form action={{ route('logout') }} method="POST">
            @csrf
            <button type="submit" class="button" aria-label="Log Out">Log Out <i class="fa fa-sign-out"
                    aria-hidden="true"></i></button>
        </form>
        <h4>Logged In As: {{ ucfirst(auth()->user()->name) }}</h4>
    @endauth
    {{-- USER LOG IN OR OUT MESSAGE --}}
    @include('includes._flash_message')
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    setTimeout(() => {
        if ($(window).width() > 400) {
            $('.flash-message').css('bottom', '100px');
        }
        if ($(window).width() <= 400) {
            $('.flash-message').css('bottom', '0');
        }
        // after displaying for 7000ms(7s) message hides itself
        setTimeout(() => {
            $('.flash-message').css('bottom', '-100%');
        }, 5000);
    }, 250);

</script>
@include('includes._footer')

<h1>Login</h1>
@include('offline-check')
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    <br>
    <label>Password:</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Login</button>
	
	 <p>Belum punya akun? <a href="{{ route('register') }}">Registrasi</a></p>
</form>
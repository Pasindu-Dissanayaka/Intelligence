{{-- blogs.blade.php --}}
@extends('layouts.authentication')

@section('title')
Login
@endsection

@section('content')
<div class="card-body">
    <form id="loginForm" role="form" class="text-start">
        @csrf
        <div class="input-group input-group-outline my-3">
            <label class="form-label">Email</label>
            <input id="email" type="email" class="form-control">
        </div>
        <div class="input-group input-group-outline mb-3">
            <label class="form-label">Password</label>
            <input id="password" type="password" class="form-control">
        </div>
        <div class="form-check form-switch d-flex align-items-center mb-3">
            <input class="form-check-input" type="checkbox" id="rememberMe" checked>
            <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
        </div>
        <div class="text-center">
            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Login</button>
        </div>
        <p class="mt-4 text-sm text-center">
            Don't have an account?
            <a href="/register" class="text-primary text-gradient font-weight-bold">Register</a>
        </p>
    </form>
</div>
@endsection

@section('custom_js')
<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {
            email: formData.get('email'),
            password: formData.get('password')
        };

        try {
            const res = await fetch('/login/validate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'include',
                body: JSON.stringify(data)
            });

            const json = await res.json();

            if (res.ok) {
                alert('Login successful!');
                window.location.href = '/dashboard';
            } else {
                alert(json.error || 'Login failed');
            }
        } catch (err) {
            console.error(err);
            alert('Something went wrong');
        }
    });
</script>
@endsection
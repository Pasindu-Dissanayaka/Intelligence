@extends('layouts.authentication')

@section('title')
Login
@endsection

@section('content')
<div class="card-body">
    <form id="signupForm" role="form">
        @csrf
        <div class="input-group input-group-dynamic mb-3">
            <input type="username" id="username" name="username" placeholder="Username" class="form-control" required aria-required="This field is compulsory">
        </div>
        <div id="usernameError" class="error" style="color:red;"></div>
        <div class="input-group input-group-dynamic mb-3">
            <input type="email" id="email" name="email" placeholder="Email" class="form-control" required aria-required="This field is compulsory">
        </div>
        <div id="emailError" class="error" style="color:red;"></div>
        <div class="input-group input-group-dynamic mb-3">
            <input type="password" id="password" name="password" placeholder="Password" class="form-control" required aria-required="This field is compulsory">
        </div>
        <div id="passwordError" class="error" style="color:red;"></div>
        <div class="form-check text-start ps-0">
            <input class="form-check-input bg-dark border-dark" type="checkbox" value="" id="flexCheckDefault" checked>
            <label class="form-check-label" for="flexCheckDefault">
                I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
            </label>
        </div>
        <div class="text-center">
            <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
            <div id="generalError" style="color:red; margin-top: 10px;"></div>
        </div>
        <p class="text-sm mt-3 mb-0">Already have an account? <a href="/login" class="text-dark font-weight-bolder">Sign in</a></p>
    </form>
</div>
@endsection

@section('custom_js')
<script>
    document.getElementById('signupForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // remove old errors
        document.getElementById('usernameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('generalError').textContent = '';

        const formData = new FormData(this);
        const data = {
            username: formData.get('username'),
            email: formData.get('email'),
            password: formData.get('password')
        };

        try {
            const res = await fetch('/register/validate', {
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
                alert('Registration successful! Please login to continue');
                window.location.href = '/login';
            } else if (json.error && typeof json.error === 'object') {
                // Display field-specific errors
                if (json.error.email) {
                    document.getElementById('emailError').textContent = json.error.email;
                }
                if (json.error.password) {
                    document.getElementById('passwordError').textContent = json.error.password;
                }
                if (!json.error.email && !json.error.password) {
                    document.getElementById('generalError').textContent = 'Registration failed. Please check your inputs.';
                }
            } else {
                document.getElementById('generalError').textContent = json.error || 'Registration failed.';
            }
        } catch (err) {
            console.error(err);
            document.getElementById('generalError').textContent = 'Unable to connect. Please try again.';
        }
    });
</script>
@endsection
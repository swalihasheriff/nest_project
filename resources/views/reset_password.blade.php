<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Nest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Same CSS as login & forgot password -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-box">

            <!-- Left branding (same everywhere) -->
            <div class="login-brand">
                <h2>Nest</h2>
                <p>Warehouse Management</p>
            </div>

            <!-- Right form -->
            <div class="login-form">
                <h4 class="mb-4">Reset Password</h4>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="mb-3 form-group">
                        <label>New Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                        >

                        @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 form-group">
                        <label>Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Reset Password
                    </button>
                </form> 
            </div>

        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Nest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Same CSS as login -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-box">

            <!-- Left branding (same as login) -->
            <div class="login-brand">
                <h2>Nest</h2>
                <p>Warehouse Management</p>
            </div>

            <!-- Right form -->
            <div class="login-form">
                <h4 class="mb-4">Forgot Password</h4>

               

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3 form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}">

                        @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Send Reset Link
                    </button>
                </form><br>

                 @if (isset($error))
                    <div class="alert alert-danger text-center mb-3">
                        {{ $error }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success mt-3 text-center">
                        {{ session('status') }}
                    </div>
                @endif
            </div>

        </div>
    </div>

</body>

</html>
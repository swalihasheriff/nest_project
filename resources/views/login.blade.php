<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | Nest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="login-body">

    <div class="login-container">
        <div class="login-box">

            <!-- Left branding -->
            <div class="login-brand">
                <h2>Nest</h2>
                <p>Warehouse Management</p>
            </div>

            <!-- Right form -->
            <div class="login-form">
                <h4 class="mb-4">Admin Login</h4>

                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3 form-group">
                        <label>Email</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>

                    <div class="mb-3 form-group">
                        <label>Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>

                    <div id="serverError" class="alert alert-danger d-none"></div>

                    <button type="submit" class="btn btn-primary w-100" id="sign_in_btn">
                        SIGN IN
                    </button>

                    <div class="mb-3 text-end">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Forgot Password?
                        </a>
                    </div>

            </div>
            </form>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        const loginUrl = "{{ route('login') }}";
    </script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
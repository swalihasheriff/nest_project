<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | Nest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-sm" style="max-width: 420px; width: 100%;">
        <div class="card-body text-center p-4">

            <h4 class="mb-2">Reset Password</h4>
            <p class="text-muted small mb-4">
                Click the link below to reset your password.
            </p>

            <a href="{{ $resetLink }}" class="btn btn-primary w-100 mb-3">
                Reset Password
            </a>

            <p class="text-warning small mb-4">
                ⚠ This link will expire in <strong>5 minutes</strong>.
            </p>

        </div>
    </div>

</body>

</html>
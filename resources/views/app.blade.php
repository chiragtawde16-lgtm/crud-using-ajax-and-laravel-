<!DOCTYPE html>
<html>
<head>
    <title>My App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-light bg-light px-3">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('images/logo.png') }}" width="40" height="40" class="me-2">
        <span>My Company Name</span>
    </a>
</nav>

<!-- PAGE CONTENT -->
<div class="container mt-4">
    @yield('content')
</div>

</body>
</html>
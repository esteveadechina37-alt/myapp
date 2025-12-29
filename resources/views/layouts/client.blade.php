<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Trial+</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/dist/html5-qrcode.min.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar .nav-link {
            color: white !important;
            margin: 0 15px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .navbar .nav-link:hover {
            color: #fff !important;
            text-shadow: 0 0 10px rgba(255,255,255,0.5);
        }

        .navbar .btn-connexion {
            background: white;
            color: #d32f2f;
            font-weight: 600;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
        }

        .navbar .btn-connexion:hover {
            background: #f0f0f0;
        }

        .navbar .navbar-toggler {
            border: 1px solid white;
        }

        .navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.55%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        main {
            min-height: 70vh;
            padding: 40px 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #b71c1c 0%, #1565c0 100%);
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 12px;
        }

        .card-header {
            background: linear-gradient(135deg, #d32f2f 0%, #1976d2 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }

        @yield('extra-styles')
    </style>
</head>
<body>
    @include('includes.navbar')

    <main>
        @yield('content')
    </main>

    @include('includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.2.0/dist/html5-qrcode.min.js"></script>
    @yield('scripts')
</body>
</html>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display+SC&display=swap" rel="stylesheet">

    <title>SwiftMart</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="text-center">

            <h1 class="fw-bold mb-5" style="font-family: 'Playfair Display SC', sans-serif;">
                HALAMAN UTAMA
            </h1>

            <h2 class="fw-bold" style="font-family: 'Times New Roman', Times, serif;">
                SWIFTMART
            </h2>

            <p class="fs-5 mb-5">
                Sistem Kasir Mandiri
            </p>

            <div class="d-grid gap-5 mx-auto" style="width: 390px;">

                <a href="{{ route('checkout.index') }}"
                   class="btn btn-outline-dark btn-lg fw-bold py-3">
                    MULAI BELANJA
                </a>

                <a href="{{ route('login') }}"
                   class="btn btn-outline-dark btn-lg fw-bold py-3">
                    LOGIN ADMIN
                </a>

            </div>

        </div>
    </div>

</body>
</html>
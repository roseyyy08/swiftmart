<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SwiftMart - Self Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700,800" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,800" rel="stylesheet">

    <style>
        :root {
            --bg-1: #0b1512; --bg-2: #060f0c;
            --card-bg: #131f19; --card-border: rgba(255,255,255,.07);
            --gold: #cda45a; --gold-light: #e8cf94;
            --teal: #1f7a6c; --teal-light: #34a893;
            --text: #e7f1ee; --text-muted: #a9c2b6; --text-faint: #5f7469;
            --danger: #d97757; --success: #34c281;
        }
        body {
            font-family: 'Nunito', sans-serif;
            background: radial-gradient(circle at top left, var(--bg-1), var(--bg-2)) !important;
            color: var(--text);
            min-height: 100vh;
        }
        .brand-logo { font-family: 'Playfair Display', serif; font-weight: 800; color: var(--text); }
        .brand-logo .accent { color: var(--gold); }

        .card { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; color: var(--text); }
        .card-title { color: var(--text); }
        body .text-muted { color: var(--text-muted) !important; }
        .text-success { color: var(--success) !important; }

        .form-control {
            background: var(--bg-2) !important; border: 1px solid var(--card-border) !important; color: var(--text) !important;
        }
        .form-control::placeholder { color: var(--text-faint); }
        .form-control:focus {
            border-color: var(--teal-light) !important; box-shadow: 0 0 0 .2rem rgba(31,122,108,.2) !important;
            background: var(--bg-2) !important; color: var(--text) !important;
        }

        .btn-dark { background: var(--teal) !important; border-color: var(--teal) !important; }
        .btn-dark:hover { background: var(--teal-light) !important; border-color: var(--teal-light) !important; }
        .btn-success { background: var(--teal) !important; border-color: var(--teal) !important; }
        .btn-success:hover { background: var(--teal-light) !important; border-color: var(--teal-light) !important; }
        .btn-primary { background: var(--teal) !important; border-color: var(--teal) !important; }
        .btn-primary:hover { background: var(--teal-light) !important; border-color: var(--teal-light) !important; }
        .btn-outline-primary { color: var(--gold-light) !important; border-color: var(--gold) !important; }
        .btn-outline-primary:hover { background: rgba(205,164,90,.12) !important; color: var(--gold-light) !important; }
        .btn-outline-dark { color: var(--text-muted) !important; border-color: var(--card-border) !important; }
        .btn-check:checked + .btn-outline-dark { background: var(--teal) !important; border-color: var(--teal) !important; color: #fff !important; }
        .btn-outline-secondary { color: var(--text-muted) !important; border-color: var(--card-border) !important; }
        .btn-secondary { background: transparent !important; border-color: var(--card-border) !important; color: var(--text-muted) !important; }

        .modal-content { background: var(--card-bg) !important; border: 1px solid var(--card-border) !important; color: var(--text); }
        .modal-header, .modal-footer { border-color: var(--card-border) !important; }

        .alert-success { background: rgba(52,194,129,.15) !important; border-color: rgba(52,194,129,.3)
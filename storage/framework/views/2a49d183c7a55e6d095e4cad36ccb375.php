<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'SwiftMart'); ?></title>

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
            --danger: #d97757;
        }
        body {
            font-family: 'Nunito', sans-serif;
            background: radial-gradient(circle at top left, var(--bg-1), var(--bg-2));
            color: var(--text);
            min-height: 100vh;
        }
        a { text-decoration: none; }
        .brand-logo { font-family: 'Playfair Display', serif; font-weight: 800; font-size: 2.2rem; color: var(--text); }
        .brand-logo .accent { color: var(--gold); }
        .brand-sub { color: var(--text-faint); font-size: .8rem; letter-spacing: .04em; }
        .page-eyebrow { color: var(--gold); font-size: .95rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; }

        .guest-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 14px; padding: 32px; }

        .btn-swift-primary {
            background: var(--teal); color: #fff; border: none;
            border-radius: 30px; padding: 12px 22px; font-weight: 700;
        }
        .btn-swift-primary:hover { background: var(--teal-light); color: #fff; }
        .btn-swift-outline {
            background: transparent; color: var(--text); border: 1px solid var(--card-border);
            border-radius: 30px; padding: 12px 22px; font-weight: 700;
        }
        .btn-swift-outline:hover { border-color: var(--gold); color: var(--gold-light); }

        .form-control {
            background: var(--bg-2) !important; border: 1px solid var(--card-border) !important;
            color: var(--text) !important; border-radius: 8px;
        }
        .form-control::placeholder { color: var(--text-faint); }
        .form-control:focus {
            border-color: var(--teal-light) !important;
            box-shadow: 0 0 0 .2rem rgba(31,122,108,.2) !important;
        }
        label { color: var(--text-muted); font-weight: 600; font-size: .85rem; }
        .form-check-label { color: var(--text-muted); }
        body .text-muted { color: var(--text-muted) !important; }
        .invalid-feedback { color: var(--danger) !important; }
        .is-invalid { border-color: var(--danger) !important; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="text-center" style="width: 100%; max-width: 420px; padding: 20px;">
        <div class="mb-4">
            <div class="brand-logo">Swift<span class="accent">Mart</span></div>
            <div class="brand-sub">Self Payment System</div>
        </div>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\laragon\www\swiftmart\resources\views/layouts/guest.blade.php ENDPATH**/ ?>
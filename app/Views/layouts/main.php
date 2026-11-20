<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'POS System') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= base_url('products') ?>">POS System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('products') ?>">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('customers') ?>">Customers</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('users') ?>">Staff</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('sales/create') ?>">Record Sale</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('sales') ?>">Sales History</a></li>
                </ul>
                <?php if (session()->get('isLoggedIn')): ?>
                    <span class="navbar-text text-white me-3">
                        Welcome, <?= esc(session()->get('full_name')) ?>
                    </span>
                    <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
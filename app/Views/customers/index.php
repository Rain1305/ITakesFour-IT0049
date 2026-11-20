<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Customers</h2>
    <a href="<?= base_url('customers/create') ?>" class="btn btn-primary">+ Add Customer</a>
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Created At</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No customers found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= esc($customer['id']) ?></td>
                            <td><?= esc($customer['full_name']) ?></td>
                            <td><?= esc($customer['email']) ?></td>
                            <td><?= esc($customer['phone'] ?: '—') ?></td>
                            <td><?= esc($customer['created_at']) ?></td>
                            <td>
                                <a href="<?= base_url('customers/edit/' . $customer['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?= base_url('customers/delete/' . $customer['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this customer?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
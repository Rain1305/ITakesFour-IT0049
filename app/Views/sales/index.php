<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Sales History</h2>
    <a href="<?= base_url('sales/create') ?>" class="btn btn-success">+ Record Sale</a>
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Staff Member</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sales)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No sales recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?= esc($sale['id']) ?></td>
                            <td><?= esc($sale['product_name']) ?></td>
                            <td><?= esc($sale['customer_name'] ?: 'Walk-in Customer') ?></td>
                            <td><?= esc($sale['staff_name']) ?></td>
                            <td><?= esc($sale['quantity']) ?></td>
                            <td class="fw-bold">₱<?= number_format($sale['total_price'], 2) ?></td>
                            <td><?= esc($sale['created_at']) ?></td>
                            <td>
                                <form action="<?= base_url('sales/delete/' . $sale['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Void and delete this sale? Stock will be restored.');">
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
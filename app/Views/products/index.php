<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Products</h2>
    <a href="<?= base_url('products/create') ?>" class="btn btn-primary">+ Add Product</a>
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="80">Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Created At</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="text-center">
                                <?php if (! empty($product['image'])): ?>
                                    <img src="<?= base_url('uploads/products/' . esc($product['image'])) ?>"
                                         alt="<?= esc($product['name']) ?>"
                                         class="rounded"
                                         style="width: 55px; height: 55px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($product['name']) ?></td>
                            <td>₱<?= number_format($product['price'], 2) ?></td>
                            <td>
                                <span class="badge <?= $product['stock_quantity'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                    <?= esc($product['stock_quantity']) ?>
                                </span>
                            </td>
                            <td><?= esc($product['created_at']) ?></td>
                            <td>
                                <a href="<?= base_url('products/edit/' . $product['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?= base_url('products/delete/' . $product['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this product?');">
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
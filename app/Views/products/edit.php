<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Edit Product</div>
            <div class="card-body">
                <form action="<?= base_url('products/update/' . $product['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?= old('name', $product['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?= old('price', $product['price']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" value="<?= old('stock_quantity', $product['stock_quantity']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>
                        <?php if (! empty($product['image'])): ?>
                            <img src="<?= base_url('uploads/products/' . esc($product['image'])) ?>"
                                 alt="<?= esc($product['name']) ?>"
                                 class="rounded mb-2"
                                 style="width: 90px; height: 90px; object-fit: cover;">
                        <?php else: ?>
                            <p class="text-muted small">No image uploaded.</p>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control mt-1" accept="image/*">
                        <small class="text-muted">Leave blank to keep existing image.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('products') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
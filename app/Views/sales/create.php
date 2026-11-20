<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span>Record New Sale</span>
                <a href="<?= base_url('sales') ?>" class="btn btn-outline-light btn-sm">View Sales History</a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('sales/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Product <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-select" required>
                            <option value="" data-price="0" data-stock="0">-- Select a Product --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= esc($product['id']) ?>"
                                        data-price="<?= esc($product['price']) ?>"
                                        data-stock="<?= esc($product['stock_quantity']) ?>"
                                        <?= old('product_id') == $product['id'] ? 'selected' : '' ?>>
                                    <?= esc($product['name']) ?> — ₱<?= number_format($product['price'], 2) ?> (Stock: <?= esc($product['stock_quantity']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Customer (Optional)</label>
                        <select name="customer_id" class="form-select">
                            <option value="">-- Walk-in Customer --</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= esc($customer['id']) ?>" <?= old('customer_id') == $customer['id'] ? 'selected' : '' ?>>
                                    <?= esc($customer['full_name']) ?> (<?= esc($customer['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="<?= old('quantity', 1) ?>" required>
                        <small id="stockHint" class="text-muted"></small>
                    </div>

                    <div class="alert alert-secondary d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Estimated Total:</span>
                        <span id="estimatedTotal" class="fs-5 fw-bold text-dark">₱0.00</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('products') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">Complete Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const totalDisplay  = document.getElementById('estimatedTotal');
    const stockHint     = document.getElementById('stockHint');

    function updateSummary() {
        const selected = productSelect.options[productSelect.selectedIndex];
        const price    = parseFloat(selected.getAttribute('data-price')) || 0;
        const stock    = parseInt(selected.getAttribute('data-stock')) || 0;
        const qty      = parseInt(quantityInput.value) || 0;

        totalDisplay.textContent = '₱' + (price * qty).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        if (selected.value) {
            stockHint.textContent = `Available in stock: ${stock} unit(s)`;
        } else {
            stockHint.textContent = '';
        }
    }

    productSelect.addEventListener('change', updateSummary);
    quantityInput.addEventListener('input', updateSummary);
    updateSummary();
</script>
<?= $this->endSection() ?>
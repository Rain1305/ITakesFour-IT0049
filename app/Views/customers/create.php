<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Add New Customer</div>
            <div class="card-body">
                <form action="<?= base_url('customers/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone (Optional)</label>
                        <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('customers') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
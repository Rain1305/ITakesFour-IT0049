<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Edit Staff Member</div>
            <div class="card-body">
                <form action="<?= base_url('users/update/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username', $user['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name', $user['full_name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Leave blank to keep the current password.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Avatar</label><br>
                        <?php if (! empty($user['avatar'])): ?>
                            <img src="<?= base_url('uploads/avatars/' . esc($user['avatar'])) ?>"
                                 alt="<?= esc($user['full_name']) ?>"
                                 class="rounded-circle border mb-2"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else: ?>
                            <p class="text-muted small">No avatar uploaded.</p>
                        <?php endif; ?>
                        <input type="file" name="avatar" class="form-control mt-1" accept="image/*">
                        <small class="text-muted">Leave blank to keep existing avatar.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('users') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-warning">Update Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
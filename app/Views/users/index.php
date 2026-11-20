<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Staff Management</h2>
    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">+ Add Staff</a>
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th width="80">Avatar</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Created At</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No staff members found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-center">
                                <?php if (! empty($user['avatar'])): ?>
                                    <img src="<?= base_url('uploads/avatars/' . esc($user['avatar'])) ?>"
                                         alt="<?= esc($user['full_name']) ?>"
                                         class="rounded-circle border"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center fw-bold"
                                         style="width: 50px; height: 50px;">
                                        <?= strtoupper(substr(esc($user['full_name']), 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['full_name']) ?></td>
                            <td><?= esc($user['created_at']) ?></td>
                            <td>
                                <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?= base_url('users/delete/' . $user['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this staff account?');">
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
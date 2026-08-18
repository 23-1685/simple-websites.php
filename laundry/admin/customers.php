<?php
require "../includes/admin_header.php";
require "../db.php";

$customers = mysqli_query($conn, "
    SELECT * 
    FROM users 
    WHERE role='customer' 
    ORDER BY fullname
");
?>

<div class="row">
    <div class="col-12">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Registered Customers</h3>
                    <p class="text-muted mb-0">List of all customers registered in the system</p>
                </div>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-people-fill" style="font-size: 1.5rem;"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">ID</th>
                            <th class="py-3">Full Name</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Phone Number</th>
                            <th class="py-3">Registered Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($customers) > 0): ?>
                            <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                                <tr>
                                    <td><strong>#<?= $c['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($c['fullname']) ?></td>
                                    <td>
                                        <a href="mailto:<?= htmlspecialchars($c['email']) ?>" class="text-decoration-none text-dark">
                                            <i class="bi bi-envelope me-1 text-muted"></i> <?= htmlspecialchars($c['email']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="tel:<?= htmlspecialchars($c['phone']) ?>" class="text-decoration-none text-dark">
                                            <i class="bi bi-telephone me-1 text-muted"></i> <?= htmlspecialchars($c['phone']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar-event me-1 text-muted"></i> 
                                        <?= date('M d, Y h:i A', strtotime($c['created_at'])) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-0 fw-semibold">No customers registered yet</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require "../includes/admin_footer.php";
?>
<?php
require "../includes/admin_header.php";
require "../db.php";

$error = "";
$success = "";

// Handle Service Insertion
if (isset($_POST['add_service'])) {
    $name = trim($_POST['service_name']);
    $price = floatval($_POST['price']);
    $unit = trim($_POST['unit']);

    if (empty($name) || $price <= 0 || empty($unit)) {
        $error = "All fields are required and price must be greater than zero.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO services (service_name, price, unit) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sds", $name, $price, $unit);
        if (mysqli_stmt_execute($stmt)) {
            $success = "New service added successfully.";
        } else {
            $error = "Failed to add service. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Handle Service Deletion
if (isset($_GET['delete_id'])) {
    $del_id = intval($_GET['delete_id']);
    
    // Check if service is currently referenced in orders
    $check = mysqli_prepare($conn, "SELECT id FROM orders WHERE service_id = ?");
    mysqli_stmt_bind_param($check, "i", $del_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    
    if (mysqli_stmt_num_rows($check) > 0) {
        $error = "Cannot delete service. It is currently linked to active orders.";
    } else {
        $stmt_del = mysqli_prepare($conn, "DELETE FROM services WHERE id = ?");
        mysqli_stmt_bind_param($stmt_del, "i", $del_id);
        if (mysqli_stmt_execute($stmt_del)) {
            $success = "Service deleted successfully.";
        } else {
            $error = "Failed to delete service.";
        }
        mysqli_stmt_close($stmt_del);
    }
    mysqli_stmt_close($check);
}

// Fetch all services
$services = mysqli_query($conn, "SELECT * FROM services ORDER BY service_name ASC");
?>

<div class="row g-4">
    <!-- Add Service Form Card -->
    <div class="col-lg-4">
        <div class="card card-custom p-4">
            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle me-1"></i> Add Service</h4>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="services.php">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">SERVICE NAME</label>
                    <input type="text" name="service_name" class="form-control" placeholder="e.g. Dry Cleaning (Blanket)" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">PRICE (KSh)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 500.00" required>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">UNIT OF MEASURE</label>
                    <select name="unit" class="form-select form-control" required>
                        <option value="Kg">Kg (Weight)</option>
                        <option value="Piece">Piece (Item Count)</option>
                        <option value="Pair">Pair</option>
                    </select>
                </div>
                <button type="submit" name="add_service" class="btn btn-success w-100 py-2 fw-semibold" style="border-radius: 8px;">
                    Save Service
                </button>
            </form>
        </div>
    </div>

    <!-- Services List Card -->
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Laundry Services</h3>
                    <p class="text-muted mb-0">Active services offered to laundry customers</p>
                </div>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-tags-fill" style="font-size: 1.5rem;"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Service Name</th>
                            <th class="py-3">Price</th>
                            <th class="py-3">Unit</th>
                            <th class="py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($services) > 0): ?>
                            <?php while ($s = mysqli_fetch_assoc($services)): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?= htmlspecialchars($s['service_name']) ?></td>
                                    <td>KSh <?= number_format($s['price'], 2) ?></td>
                                    <td><span class="badge bg-secondary px-2 py-1"><?= htmlspecialchars($s['unit']) ?></span></td>
                                    <td class="text-end">
                                        <a href="services.php?delete_id=<?= $s['id'] ?>" class="btn btn-outline-danger btn-sm px-2" style="border-radius: 6px;" onclick="return confirm('Are you sure you want to delete service \'<?= htmlspecialchars($s['service_name']) ?>\'?');">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-tag" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-0 fw-semibold">No services configured</p>
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
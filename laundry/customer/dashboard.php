<?php
require "../includes/customer_header.php";
require "../db.php";

$user = $_SESSION['fullname'];
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom p-4 bg-white text-dark">
            <h2 class="fw-bold mb-2">Welcome back, <?= htmlspecialchars($user) ?>! 👋</h2>
            <p class="text-muted mb-0">What would you like to do today? Select an option below to manage your laundry services.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- New Order Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 text-center p-3 transition-hover">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-success-subtle text-success rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-plus-circle-fill" style="font-size: 2.2rem;"></i>
                    </div>
                    <h5 class="fw-bold card-title">New Order</h5>
                    <p class="card-text text-muted small">Place a new laundry request, choose your service, and select dates.</p>
                </div>
                <a href="new_order.php" class="btn btn-success w-100 mt-3 py-2 fw-semibold" style="border-radius: 10px;">
                    Open Form
                </a>
            </div>
        </div>
    </div>

    <!-- My Orders Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 text-center p-3 transition-hover">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-primary-subtle text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-journal-text" style="font-size: 2.2rem;"></i>
                    </div>
                    <h5 class="fw-bold card-title">My Orders</h5>
                    <p class="card-text text-muted small">View your active and completed orders, check details and pricing.</p>
                </div>
                <a href="my_orders.php" class="btn btn-primary w-100 mt-3 py-2 fw-semibold" style="border-radius: 10px;">
                    View Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Track Order Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 text-center p-3 transition-hover">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-warning-subtle text-warning rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 2.2rem;"></i>
                    </div>
                    <h5 class="fw-bold card-title">Track Order</h5>
                    <p class="card-text text-muted small">Monitor real-time progress of your laundry orders using your order number.</p>
                </div>
                <a href="track_order.php" class="btn btn-warning w-100 mt-3 py-2 fw-semibold text-dark" style="border-radius: 10px;">
                    Track Status
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 text-center p-3 transition-hover">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <div class="bg-secondary-subtle text-secondary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-person-fill" style="font-size: 2.2rem;"></i>
                    </div>
                    <h5 class="fw-bold card-title">Profile Settings</h5>
                    <p class="card-text text-muted small">Update your personal contact details, email address, and account password.</p>
                </div>
                <a href="profile.php" class="btn btn-secondary w-100 mt-3 py-2 fw-semibold" style="border-radius: 10px;">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .transition-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08) !important;
    }
</style>

<?php
require "../includes/customer_footer.php";
?>
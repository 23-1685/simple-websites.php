<?php
// =============================================
// Export logic – must run before any HTML output
// =============================================
if (isset($_GET['export'])) {
    // Include config for database connection
    include 'includes/config.php';

    // ---- PROTECTION ----
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
    // --------------------

    $export = $_GET['export'];

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $export . '_report_' . date('Y-m-d') . '.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add UTF-8 BOM for Excel compatibility
    fputs($output, "\xEF\xBB\xBF");

    switch ($export) {
        case 'books':
            // Column headers
            fputcsv($output, ['ID', 'Title', 'Author', 'ISBN', 'Category', 'Total Copies', 'Available Copies', 'Description']);

            $result = $conn->query("
                SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                ORDER BY b.id
            ");
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, [
                    $row['id'],
                    $row['title'],
                    $row['author'],
                    $row['isbn'],
                    $row['category_name'] ?? 'N/A',
                    $row['total_copies'],
                    $row['available_copies'],
                    $row['description']
                ]);
            }
            break;

        case 'students':
            fputcsv($output, ['ID', 'Student ID', 'Full Name', 'Email', 'Phone', 'Department', 'Registered At']);

            $result = $conn->query("SELECT * FROM students ORDER BY id");
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, [
                    $row['id'],
                    $row['student_id'],
                    $row['full_name'],
                    $row['email'],
                    $row['phone'],
                    $row['department'],
                    $row['registered_at']
                ]);
            }
            break;

        case 'loans':
            fputcsv($output, ['Loan ID', 'Book', 'Student', 'Issue Date', 'Due Date', 'Return Date', 'Status', 'Fine']);

            $result = $conn->query("
                SELECT l.*, b.title as book_title, s.full_name as student_name
                FROM loans l
                JOIN books b ON l.book_id = b.id
                JOIN students s ON l.student_id = s.id
                ORDER BY l.created_at DESC
            ");
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, [
                    $row['id'],
                    $row['book_title'],
                    $row['student_name'],
                    $row['issue_date'],
                    $row['due_date'],
                    $row['return_date'] ?? 'Not returned',
                    $row['status'],
                    number_format($row['fine'], 2)
                ]);
            }
            break;

        default:
            // Invalid export – redirect back
            header('Location: report.php');
            exit;
    }

    fclose($output);
    exit; // stop further output
}

// =============================================
// If no export parameter, show the report page
// =============================================
$page_title = 'Reports';
$page_subtitle = 'Download data as CSV files';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';
?>

<div class="section-header">
    <h2><i class="fas fa-file-download"></i> Export Reports</h2>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <!-- Books Report -->
    <div class="stat-card" style="cursor:pointer;" onclick="window.location.href='report.php?export=books'">
        <div class="stat-icon blue"><i class="fas fa-book"></i></div>
        <div class="stat-info">
            <h3>Books</h3>
            <p>Download book list</p>
        </div>
    </div>

    <!-- Students Report -->
    <div class="stat-card" style="cursor:pointer;" onclick="window.location.href='report.php?export=students'">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3>Students</h3>
            <p>Download student list</p>
        </div>
    </div>

    <!-- Loans Report -->
    <div class="stat-card" style="cursor:pointer;" onclick="window.location.href='report.php?export=loans'">
        <div class="stat-icon orange"><i class="fas fa-hand-holding-heart"></i></div>
        <div class="stat-info">
            <h3>Loans</h3>
            <p>Download loan history</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
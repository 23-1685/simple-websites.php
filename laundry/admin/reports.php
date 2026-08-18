<?php

require "../includes/admin_auth.php";

require "../db.php";

$result=mysqli_query($conn,"
SELECT
orders.order_number,
users.fullname,
services.service_name,
orders.total_amount,
orders.status

FROM orders

JOIN users
ON users.id=orders.user_id

JOIN services
ON services.id=orders.service_id
");
header("Content-Type:text/csv");
header("Content-Disposition:attachment; filename=laundry_report.csv");

$output=fopen("php://output","w");

fputcsv($output,
["Order","Customer","Service","Amount","Status"]);

while($row=mysqli_fetch_assoc($result)){
    fputcsv($output,$row);
}

fclose($output);
exit();
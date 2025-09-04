<?php
require 'db_config.php';

$thisWeekStart = date('Y-m-d', strtotime('monday this week'));

// Get this week's abandoned count (corrected action value)
$sqlThisWeekAbandon = "
    SELECT COUNT(*) AS count
    FROM cart_tracking
    WHERE action = 'abandoned'
    AND DATE(timestamp) >= '$thisWeekStart'
";
$resultAbandon = mysqli_query($conn, $sqlThisWeekAbandon);
$thisWeekAbandon = ($resultAbandon && mysqli_num_rows($resultAbandon) > 0)
    ? intval(mysqli_fetch_assoc($resultAbandon)['count'])
    : 0;

// Get this week's recovered count (corrected action value)
$sqlThisWeekRecovered = "
    SELECT COUNT(*) AS count
    FROM cart_tracking
    WHERE action = 'recovered'
    AND DATE(timestamp) >= '$thisWeekStart'
";
$resultRecovered = mysqli_query($conn, $sqlThisWeekRecovered);
$thisWeekRecovered = ($resultRecovered && mysqli_num_rows($resultRecovered) > 0)
    ? intval(mysqli_fetch_assoc($resultRecovered)['count'])
    : 0;

// Output result as JSON
echo json_encode([$thisWeekAbandon, $thisWeekRecovered]);

mysqli_close($conn);
?>

<?php
require_once 'connect.php';

$pagination = $perPage = $output = null;

// User input
$page = isset($_GET['page']) ? preg_replace("#[^0-9]#","",$_GET['page']) : 1;

// Here I grab the value of the select element.
if(isset($_GET['perpage'])) {
    $perPage = (int)$_GET['perpage'];
} else {
    $perPage = 10;
}

// Positioning: We need to work out whether $page is greater than 1
$offset = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// Query
$cities = $conn->prepare("
    SELECT SQL_CALC_FOUND_ROWS ID, Name
    FROM city
    LIMIT {$offset}, {$perPage}
");

$cities->execute();

$cities = $cities->fetchAll(PDO::FETCH_ASSOC);

foreach($cities as $city) {
    $output .= $city['ID'] . ' ' . $city['Name'] . ' ' . '<br>';
}

// Pages: Notice how we directly do a fetch on the query object....
$total = $conn->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);


if($pages != 1) {
    if($page <= $pages) {
        $next = $page + 1;
        $pagination .='<a href="index.php?page='.$next.'&perpage='.$perPage.'">Next</a> ';
    }

    // I said $page != 1 because I don't want to go back farther than page 1.
    if($page <= $pages && $page != 1) {
        $prev = $page - 1;
        $pagination .='<a href="index.php?page='.$prev.'&perpage='.$perPage.'">Previous</a>';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities</title>
</head>
<body>


    <div class="pagination">
        <?php echo $output; ?>
        <?php echo $pagination; ?>
    </div>

    <form action="index.php" method="get">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <select name="perpage">
            <option value="10">Choose items per page</option>
            <option value="20">20 items per page</option>
            <option value="40">40 items per page</option>
            <option value="80">80 items per page</option>
            <option value="100">100 items per page</option>
        </select>
        <input type="submit" value="Change">
    </form>
</body>
</html>

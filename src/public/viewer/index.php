<?php

// viewer.php

// Define dummy data for images
$images = [
    []];
if ($argc >= 2) {
    foreach ($argv as $arg ) {
        if($arg==="index.php") continue;
        if (file_exists($arg) ) {

            if (is_dir($arg)) {

                $tmpImg = scandir($arg);
                $tmpImg = array_flip($tmpImg);

                $tmpImg=array_map(
                    function ($item) use($arg)
                {
                 #   error_log("$arg$item");
                    return $arg . $item;
                }, array_keys($tmpImg));

                $tmpImages = array_filter($tmpImg, function ($item) use($arg){
                    if (in_array(pathinfo($item, PATHINFO_EXTENSION)
                        , ["jpg", "png", "jpeg"])) {
                        return true;
                    } else return false;
                });

            } else {
                $tmpImages[]=$arg;
            }
            foreach($tmpImages as $key => $val){
                   $image = [

                            "title" => pathinfo($val, PATHINFO_BASENAME),
                            "url" => "/viewer/image.php?path=" . urlencode($val),
                            "description" => "$val",
                            "dimensions" => "1920x1080",
                            "tags" => [ ],
                        ];
                $images[]=$image;
            }
        }else {
            die("inputp ath:" .$arg);
        }
    }
}
// Pagination Logic
function get_images($page=0)
{
    global $images;
    $perPage = 3; // Number of images per page
    $totalImages = count($images);
    if($page===0)$page = random_int(0,$totalImages/$perPage);

    $start = ($page - 1) * $perPage;
    $end = min($start + $perPage, $totalImages);

    return array_slice($images, $start, $end - $start);
}

// Determine the requested action (next or previous)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$action = isset($_GET['action']) ? $_GET['action'] : 'next';

if ($action === 'next') {
    $page++;
} elseif ($action === 'previous' && $page > 1) {
    $page--;
}

// Get images for the current page
$response = get_images($page);

// Add pagination info to the response
$result = [
    "currentPage" => $page,
    "totalPages" => ceil(count($images) / 3),
    "images" => $response
];

// Set header to return JSON
header('Content-Type: application/json');
echo json_encode($result);

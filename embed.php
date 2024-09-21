<?php
// embed.php
include('includes/config.php');
include('includes/header.php');
include('includes/sidebar.php');

// Ambil file_code dari URL
$file_code = isset($_GET['file_code']) ? $_GET['file_code'] : '';

// Mengambil informasi video dari Doodstream berdasarkan file_code
function fetch_video_info($file_code) {
    $url = $GLOBALS['file_info'] . '&file_code=' . $file_code;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

function fetch_related_videos($fld_id) {
    $url = $GLOBALS['list_videos'] . '&fld_id=' . $fld_id;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

$video_info = fetch_video_info($file_code);
$video = $video_info['result'][0];

// Periksa apakah 'fld_id' ada di dalam data video
if (isset($video['fld_id']) && !empty($video['fld_id'])) {
    $fld_id = $video['fld_id'];
    $related_videos_data = fetch_related_videos($fld_id);
    $related_videos = $related_videos_data['result']['files'];
} else {
    // Jika fld_id tidak ada, berikan pesan atau perlakuan khusus
    $related_videos = []; // Kosongkan related videos
    $fld_id = null; // Berikan nilai default
}

$embed_url = 'https://dood.to/e/' . $file_code;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $video['title']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="main-content">

        <!-- Video embed -->
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="<?= $embed_url; ?>" allowfullscreen></iframe>
        </div>

        <!-- Card info tambahan video -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title"><?= $video['title']; ?></h5>
            </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Views:</strong> <?= $video['views']; ?></li>
                    <li class="list-group-item"><strong>Duration:</strong> <?= gmdate("H:i:s", $video['length']); ?></li>
                    <li class="list-group-item"><strong>Size:</strong> <?= round($video['size'] / (1024 * 1024), 2); ?> MB</li>
                    <li class="list-group-item"><strong>Uploaded:</strong> <?= $video['uploaded']; ?></li>
                </ul>
            <div class="card-body">
                <a href="https://dood.to/d/<?= $file_code; ?>" class="btn btn-primary">Download</a>
            </div>
        </div>

        <!-- Related Videos -->
        <!-- Related Videos -->
        <h4 class="mt-5">Related Videos</h4>
        <div class="row">
            <?php if (!empty($related_videos)): ?>
                <?php foreach ($related_videos as $related_video): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card video-card">
                            <img src="<?= $related_video['single_img']; ?>" class="card-img-top" alt="<?= $related_video['title']; ?>">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="embed.php?file_code=<?= $related_video['file_code']; ?>"><?= $related_video['title']; ?></a>
                                </h5>
                                <p class="card-text"><?= $related_video['views']; ?>Views</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No related videos found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>



<?php include('includes/footer.php'); ?>

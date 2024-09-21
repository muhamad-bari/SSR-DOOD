<?php
// content.php
// include('config.php');

// Ambil ID folder jika ada
$fld_id = isset($_GET['fld_id']) ? $_GET['fld_id'] : '';

// Ambil term pencarian jika ada
$search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Mengambil daftar video
function fetch_videos($fld_id = '', $search_term = '', $page = 1) {
    $url = $GLOBALS['list_videos'] . '&page=' . $page;
    
    if ($fld_id) {
        $url .= '&fld_id=' . $fld_id;
    }
    
    if ($search_term) {
        $url = $GLOBALS['search'] . '&search_term=' . urlencode($search_term);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Mengambil nama folder berdasarkan fld_id
function fetch_folder_name($fld_id) {
    if ($fld_id) {
        $folders = fetch_folders(); // Mengambil folder dari sidebar.php
        foreach ($folders as $folder) {
            if ($folder['fld_id'] == $fld_id) {
                return $folder['name'];
            }
        }
    }
    return '';
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$videos_data = fetch_videos($fld_id, $search_term, $page);
$videos = $videos_data['result']['files'];
$total_pages = $videos_data['result']['total_pages'];
$folder_name = fetch_folder_name($fld_id);
?>

<!-- Main content (Video list) -->
<div class="container">
    <!-- Jika ada folder yang dipilih, tampilkan nama folder di tengah -->
    <?php if ($folder_name): ?>
        <h2 class="text-center mb-4"><?= $folder_name; ?></h2>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($videos as $video): ?>
            <div class="col-md-4 mb-4">
                <div class="card video-card">
                    <img src="<?= $video['single_img']; ?>" class="card-img-top" alt="<?= $video['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><a href="embed.php?file_code=<?= $video['file_code']; ?>"><?= $video['title']; ?></a></h5>
                        <p class="card-text"><?= $video['views']; ?> Views</p>
                        <p class="card-text"><small class="text-muted">Uploaded: <?= $video['uploaded']; ?></small></p>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="index.php?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

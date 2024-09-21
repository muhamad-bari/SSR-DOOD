<?php
// sidebar.php
// include('config.php');

if (!function_exists('fetch_folders')) {
    // Mengambil daftar folder dari Doodstream
    function fetch_folders() {
        $url = $GLOBALS['list_folders'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true)['result']['folders'];
    }
}
$folders = fetch_folders();
?>

<!-- Sidebar (Folder List) -->
<aside class="bg-light p-3" style="width: 250px;">
    <h5>Folders</h5>
    <ul class="list-group">
        <?php foreach ($folders as $folder): ?>
            <li class="list-group-item">
                <a href="index.php?fld_id=<?= $folder['fld_id']; ?>"><?= $folder['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>

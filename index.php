<?php
// Konfigurasi API
$api_url = "https://data.jatimprov.go.id/api/3/action/package_search?rows=5";
$mock_file = "mock_data.json";

// Fungsi untuk mengambil data dari API
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout 5 detik
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if(curl_errno($ch) || $http_code !== 200) {
        curl_close($ch);
        return false;
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

// Coba fetch dari API resmi
$is_mock = false;
$data_source = "API Resmi Open Data Jatim";
$data = fetchData($api_url);

// Fallback ke Mock Data jika API gagal (sering terjadi karena CORS/Auth/403)
if (!$data || !isset($data['success'])) {
    $is_mock = true;
    $data_source = "Local Mock API (Fallback)";
    $json_mock = file_get_contents($mock_file);
    $data = json_decode($json_mock, true);
}

// Ambil array data (disesuaikan dengan struktur mock_data kita)
$items = isset($data['data']) ? $data['data'] : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Open Data Jatim Explorer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Open Data Jawa Timur</h1>
        <p>Eksplorasi data statistik dan informasi publik Provinsi Jawa Timur</p>
        
        <?php if($is_mock): ?>
            <div class="status-badge status-mock">
                <span style="margin-right: 5px;">⚠️</span> Status: Menggunakan Data Simulasi (API Resmi Offline/403)
            </div>
        <?php else: ?>
            <div class="status-badge status-live">
                <span style="margin-right: 5px;">✅</span> Status: Terhubung ke API Resmi
            </div>
        <?php endif; ?>
    </header>

    <main>
        <?php if(empty($items)): ?>
            <div class="error-container">
                <h2>Tidak Ada Data</h2>
                <p>Maaf, data tidak dapat dimuat saat ini. Silakan coba beberapa saat lagi.</p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach($items as $item): ?>
                    <div class="card">
                        <div class="card-header">
                            <span class="kategori"><?= htmlspecialchars($item['kategori']) ?></span>
                            <span class="tahun"><?= htmlspecialchars($item['tahun']) ?></span>
                        </div>
                        <h2><?= htmlspecialchars($item['judul']) ?></h2>
                        <p><?= htmlspecialchars($item['deskripsi']) ?></p>
                        
                        <div class="data-value">
                            <div class="value">
                                <?= htmlspecialchars($item['nilai']) ?>
                                <span class="satuan"><?= htmlspecialchars($item['satuan']) ?></span>
                            </div>
                            <div class="sumber">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                Sumber: <?= htmlspecialchars($item['sumber']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Aplikasi Open Data Jatim. Dibuat untuk tujuan demonstrasi.</p>
    </footer>
</body>
</html>

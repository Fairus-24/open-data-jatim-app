<?php
// Menggunakan URL Raw GitHub sebagai simulasi Endpoint API Publik (karena API jatimprov sering 403)
$api_url = "https://raw.githubusercontent.com/Fairus-24/open-data-jatim-app/main/mock_data.json";

function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if($http_code !== 200 || !$response) return false;
    return json_decode($response, true);
}

// Proses fetch data nyata melalui cURL
$api_response = fetchData($api_url);

if ($api_response && isset($api_response['data'])) {
    $data = $api_response['data'];
} else {
    // Fallback darurat jika tidak ada internet
    $json_mock = file_get_contents("mock_data.json");
    $data = json_decode($json_mock, true)['data'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPEN DATA PORTAL</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </a>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                </a>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                </a>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </a>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </a>
                <a href="#" class="nav-item">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="topbar">
                <div class="logo-text">
                    <span class="highlight">OPEN DATA</span> PORTAL
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" placeholder="Search Datasets...">
                    </div>
                    <div class="notification">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span class="badge"></span>
                    </div>
                    <div class="user-profile">
                        <div class="avatar">
                            <img src="https://i.pravatar.cc/150?img=33" alt="User">
                        </div>
                        <div class="user-info">
                            <div class="name">Mark S. <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                            <div class="role">Admin</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-title">
                <h1>Key Jatim Statistics</h1>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                
                <!-- Card 1: Population -->
                <div class="card card-glow-blue">
                    <div class="card-header">
                        <h2><?= $data['population']['title'] ?></h2>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="card-value-row">
                        <div class="main-value">
                            <span class="number"><?= explode(' ', $data['population']['value'])[0] ?></span>
                            <span class="text"><?= $data['population']['subtitle'] ?></span>
                        </div>
                        <div class="growth positive"><?= $data['population']['growth'] ?></div>
                    </div>
                    <div class="card-label"><?= $data['population']['label'] ?></div>
                    <div class="chart-container">
                        <canvas id="populationChart"></canvas>
                    </div>
                </div>

                <!-- Card 2: Education -->
                <div class="card card-glow-cyan">
                    <div class="card-header">
                        <h2><?= $data['education']['title'] ?></h2>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    </div>
                    <div class="card-value-row">
                        <div class="main-value">
                            <span class="number"><?= explode(' ', $data['education']['value'])[0] ?></span>
                            <span class="text"><?= $data['education']['subtitle'] ?></span>
                        </div>
                        <div class="growth positive"><?= $data['education']['growth'] ?></div>
                    </div>
                    <div class="card-label"><?= $data['education']['label'] ?></div>
                    <div class="chart-container row-chart">
                        <div class="doughnut-wrapper">
                            <canvas id="educationChart"></canvas>
                        </div>
                        <div class="chart-legend" id="eduLegend"></div>
                    </div>
                </div>

                <!-- Card 3: Economy -->
                <div class="card card-glow-purple">
                    <div class="card-header">
                        <h2><?= $data['economy']['title'] ?></h2>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    </div>
                    <div class="card-value-row">
                        <div class="main-value">
                            <span class="number"><?= explode(' ', $data['economy']['value'])[0] . ' ' . explode(' ', $data['economy']['value'])[1] ?></span>
                            <span class="text"><?= $data['economy']['subtitle'] ?></span>
                        </div>
                        <div class="growth positive"><?= $data['economy']['growth'] ?></div>
                    </div>
                    <div class="card-label"><?= $data['economy']['label'] ?></div>
                    <div class="chart-container">
                        <canvas id="economyChart"></canvas>
                    </div>
                </div>

                <!-- Card 4: Map/Demographics -->
                <div class="card card-glow-blue span-2">
                    <div class="card-header">
                        <h2><?= $data['demographics']['title'] ?></h2>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                    </div>
                    <div class="card-label"><?= $data['demographics']['subtitle'] ?></div>
                    <div class="map-container">
                        <!-- Simulated map using background -->
                        <div class="world-map">
                            <div class="glow-dot" style="top: 40%; left: 30%; width: 20px; height: 20px;"></div>
                            <div class="glow-dot" style="top: 55%; left: 70%; width: 40px; height: 40px; background: #8b5cf6; box-shadow: 0 0 20px #8b5cf6;"></div>
                            <div class="glow-dot" style="top: 60%; left: 65%; width: 15px; height: 15px;"></div>
                            <div class="glow-dot" style="top: 45%; left: 50%; width: 25px; height: 25px; background: #06b6d4; box-shadow: 0 0 20px #06b6d4;"></div>
                        </div>
                    </div>
                    <div class="chart-container small-chart">
                        <canvas id="demographicsChart"></canvas>
                    </div>
                </div>

                <!-- Card 5: Datasets Table -->
                <div class="card card-glow-blue span-2">
                    <div class="card-header">
                        <h2>RECENT OPEN DATASETS</h2>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Dataset Name</th>
                                    <th>Source</th>
                                    <th>Date Updated</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['datasets'] as $ds): ?>
                                <tr>
                                    <td class="ds-name"><?= $ds['name'] ?></td>
                                    <td><?= $ds['source'] ?></td>
                                    <td><?= $ds['date'] ?></td>
                                    <td><span class="status-badge"><?= $ds['status'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = 'Inter';

        // 1. Population Chart
        new Chart(document.getElementById('populationChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($data['population']['chart_labels']) ?>,
                datasets: [{
                    data: <?= json_encode($data['population']['chart_data']) ?>,
                    backgroundColor: function(context) {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                        gradient.addColorStop(0, '#60a5fa');
                        gradient.addColorStop(1, '#8b5cf6');
                        return gradient;
                    },
                    borderRadius: 4,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, border: { dash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Education Chart
        const eduData = <?= json_encode($data['education']['chart_data']) ?>;
        const eduLabels = <?= json_encode($data['education']['chart_labels']) ?>;
        const eduColors = ['#60a5fa', '#8b5cf6', '#06b6d4', '#3b82f6'];
        
        new Chart(document.getElementById('educationChart'), {
            type: 'doughnut',
            data: {
                labels: eduLabels,
                datasets: [{
                    data: eduData,
                    backgroundColor: eduColors,
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Custom Legend for Education
        const legendHtml = eduLabels.map((label, i) => `
            <div class="legend-item">
                <span class="legend-color" style="background:${eduColors[i]}"></span>
                <span>${label}</span>
            </div>
        `).join('');
        document.getElementById('eduLegend').innerHTML = legendHtml;

        // 3. Economy Chart
        new Chart(document.getElementById('economyChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($data['economy']['chart_labels']) ?>,
                datasets: [{
                    data: <?= json_encode($data['economy']['chart_data']) ?>,
                    borderColor: '#06b6d4',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 0,
                    fill: true,
                    backgroundColor: function(context) {
                        const ctx = context.chart.ctx;
                        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                        gradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)');
                        gradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');
                        return gradient;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 4. Demographics Chart
        new Chart(document.getElementById('demographicsChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($data['demographics']['chart_labels']) ?>,
                datasets: [{
                    data: <?= json_encode($data['demographics']['chart_data']) ?>,
                    backgroundColor: '#06b6d4',
                    borderRadius: 2,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>

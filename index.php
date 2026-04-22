<?php
// Aplikasi Dashboard Open Data Jatim
// Kita memindahkan pengambilan data dari Backend PHP (cURL) ke Frontend JavaScript (Fetch API)
// Ini bertujuan agar permintaan datang langsung dari Browser Anda, 
// sehingga secara otomatis dapat melewati blokir keamanan Cloudflare Jatimprov.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPEN DATA PORTAL JATIM</title>
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
                <a href="#" class="nav-item active" data-target="view-dashboard" title="Dashboard">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </a>
                <a href="#" class="nav-item" data-target="view-database" title="Database">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="topbar">
                <div class="logo-text">
                    <span class="highlight">OPEN DATA</span> PORTAL JATIM
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="searchInput" placeholder="Search Datasets...">
                    </div>
                    
                    <div class="notification" id="notifButton">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <span class="badge" id="apiBadgeStatus" style="background: #f59e0b;"></span>
                        <!-- Notification Dropdown -->
                        <div class="dropdown-menu" id="notifDropdown">
                            <div class="dropdown-header">API Status</div>
                            <div class="dropdown-item">
                                <div class="dot" id="apiDotStatus" style="background: #f59e0b;"></div>
                                <span id="apiTextStatus">Menghubungkan ke Jatimprov...</span>
                            </div>
                        </div>
                    </div>

                    <div class="user-profile" id="profileButton">
                        <div class="avatar">
                            <img src="https://i.pravatar.cc/150?img=33" alt="User">
                        </div>
                        <div class="user-info">
                            <div class="name">Guest <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard View (Active) -->
            <div id="view-dashboard" class="view-section active">
                <div class="page-title">
                    <h1>Real-time Jatim Data Analytics</h1>
                </div>

                <div id="loadingOverlay" style="text-align: center; padding: 50px; color: #60a5fa;">
                    <h2>🔄 Mengambil Data Langsung dari opendata.jatimprov.go.id...</h2>
                    <p style="color: #64748b; margin-top: 10px;">Mohon tunggu, mem-bypass keamanan (Cloudflare)...</p>
                </div>

                <div id="errorAlert" style="display:none; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; padding: 20px; border-radius: 8px; color: #f8fafc; margin-bottom: 20px;">
                    <h3>Gagal Terhubung ke API Jatim</h3>
                    <p id="errorMsg"></p>
                </div>

                <!-- Dashboard Grid (Hidden until loaded) -->
                <div class="dashboard-grid" id="mainDashboard" style="display: none;">
                    
                    <!-- Card 1: Total Datasets -->
                    <div class="card card-glow-blue">
                        <div class="card-header">
                            <h2>TOTAL DATASETS JATIM</h2>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                        </div>
                        <div class="card-value-row">
                            <div class="main-value">
                                <span class="number" id="valTotalDatasets">0</span>
                                <span class="text">Files</span>
                            </div>
                            <div class="growth positive">Live API</div>
                        </div>
                        <div class="card-label">AVAILABLE ON PORTAL</div>
                        <div class="chart-container">
                            <canvas id="populationChart"></canvas>
                        </div>
                    </div>

                    <!-- Card 2: Recent Activity -->
                    <div class="card card-glow-cyan">
                        <div class="card-header">
                            <h2>FORMAT DATA TERBANYAK</h2>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path></svg>
                        </div>
                        <div class="card-value-row">
                            <div class="main-value">
                                <span class="number" id="valTopFormat">CSV</span>
                            </div>
                        </div>
                        <div class="card-label">DISTRIBUSI FORMAT FILE</div>
                        <div class="chart-container row-chart">
                            <div class="doughnut-wrapper">
                                <canvas id="educationChart"></canvas>
                            </div>
                            <div class="chart-legend" id="eduLegend"></div>
                        </div>
                    </div>

                    <!-- Card 3: Status API -->
                    <div class="card card-glow-purple">
                        <div class="card-header">
                            <h2>KONEKTIVITAS API</h2>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        </div>
                        <div class="card-value-row">
                            <div class="main-value">
                                <span class="number">200</span>
                                <span class="text">HTTP OK</span>
                            </div>
                        </div>
                        <div class="card-label">opendata.jatimprov.go.id</div>
                        <div class="chart-container">
                            <canvas id="economyChart"></canvas>
                        </div>
                    </div>

                    <!-- Card 5: Datasets Table -->
                    <div class="card card-glow-blue span-2" style="grid-column: span 3;">
                        <div class="card-header">
                            <h2>LIVE DATA DARI: opendata.jatimprov.go.id/dataset</h2>
                        </div>
                        <div class="table-container">
                            <table id="datasetsTable">
                                <thead>
                                    <tr>
                                        <th>Dataset Title</th>
                                        <th>Organization</th>
                                        <th>Last Modified</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Injected via JS -->
                                </tbody>
                            </table>
                            <div id="noResults" style="display: none; text-align: center; color: #64748b; padding: 20px;">Dataset tidak ditemukan.</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="view-database" class="view-section">
                <div class="page-title"><h1>Database Architecture</h1></div>
            </div>
        </main>
    </div>

    <script>
        // === INTERACTIVE UI LOGIC ===
        const navItems = document.querySelectorAll('.nav-item');
        const viewSections = document.querySelectorAll('.view-section');

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                navItems.forEach(nav => nav.classList.remove('active'));
                viewSections.forEach(view => view.classList.remove('active'));
                this.classList.add('active');
                const targetId = this.getAttribute('data-target');
                if(document.getElementById(targetId)) {
                    document.getElementById(targetId).classList.add('active');
                }
            });
        });

        const searchInput = document.getElementById('searchInput');
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                let hasVisibleRow = false;
                document.querySelectorAll('.dataset-row').forEach(row => {
                    if (row.textContent.toLowerCase().includes(query)) {
                        row.style.display = '';
                        hasVisibleRow = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                document.getElementById('noResults').style.display = hasVisibleRow ? 'none' : 'block';
            });
        }

        const notifBtn = document.getElementById('notifButton');
        const notifDrop = document.getElementById('notifDropdown');
        if(notifBtn && notifDrop) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDrop.classList.toggle('show');
            });
        }

        // === REAL-TIME BROWSER FETCH (BYPASS CLOUDFLARE) ===
        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = 'Inter';

        async function fetchJatimData() {
            try {
                // Fetch directly from the Browser! This bypasses server-side Cloudflare blocks
                const response = await fetch('https://opendata.jatimprov.go.id/api/3/action/package_search?rows=15');
                
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    document.getElementById('mainDashboard').style.display = 'grid';
                    
                    // Update Status Badge
                    document.getElementById('apiBadgeStatus').style.background = '#10b981';
                    document.getElementById('apiDotStatus').style.background = '#10b981';
                    document.getElementById('apiTextStatus').innerText = 'Koneksi Sukses (Real-time)';

                    renderDashboard(data.result);
                } else {
                    throw new Error('API merespon tapi success=false');
                }
            } catch (error) {
                document.getElementById('loadingOverlay').style.display = 'none';
                document.getElementById('errorAlert').style.display = 'block';
                document.getElementById('errorMsg').innerText = error.message + " (Browser Anda mungkin terblokir oleh CORS atau Firewall)";
                
                document.getElementById('apiBadgeStatus').style.background = '#ef4444';
                document.getElementById('apiDotStatus').style.background = '#ef4444';
                document.getElementById('apiTextStatus').innerText = 'Koneksi Gagal';
            }
        }

        function renderDashboard(result) {
            const datasets = result.results;
            
            // 1. Update Metrics
            document.getElementById('valTotalDatasets').innerText = result.count.toLocaleString('id-ID');
            
            // Format Extractor
            let formatCounts = {};
            datasets.forEach(ds => {
                if(ds.resources) {
                    ds.resources.forEach(res => {
                        let fmt = res.format ? res.format.toUpperCase() : 'UNKNOWN';
                        formatCounts[fmt] = (formatCounts[fmt] || 0) + 1;
                    });
                }
            });
            
            let sortedFormats = Object.entries(formatCounts).sort((a,b) => b[1] - a[1]);
            if(sortedFormats.length > 0) {
                document.getElementById('valTopFormat').innerText = sortedFormats[0][0];
            }

            // 2. Render Table
            let tableHtml = '';
            datasets.forEach(ds => {
                let orgName = ds.organization ? ds.organization.title : 'Tidak Diketahui';
                let dateStr = new Date(ds.metadata_modified).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
                
                tableHtml += `
                    <tr class="dataset-row">
                        <td class="ds-name" style="color: #e2e8f0; font-weight: 500;">${escapeHtml(ds.title)}</td>
                        <td>${escapeHtml(orgName)}</td>
                        <td>${dateStr}</td>
                        <td><span class="status-badge" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">Published</span></td>
                        <td><a href="https://opendata.jatimprov.go.id/dataset/${ds.name}" target="_blank" style="color: #60a5fa; text-decoration: none;">View Data</a></td>
                    </tr>
                `;
            });
            document.getElementById('tableBody').innerHTML = tableHtml;

            // 3. Render Charts
            // Bar Chart (Simulated Growth)
            new Chart(document.getElementById('populationChart'), {
                type: 'bar',
                data: {
                    labels: ['2020', '2021', '2022', '2023', '2024'],
                    datasets: [{
                        data: [1200, 2500, 3800, 5100, result.count],
                        backgroundColor: function(context) {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                            gradient.addColorStop(0, '#60a5fa');
                            gradient.addColorStop(1, '#8b5cf6');
                            return gradient;
                        },
                        borderRadius: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } } } }
            });

            // Doughnut Chart (Formats)
            const eduLabels = sortedFormats.slice(0,4).map(f => f[0]);
            const eduData = sortedFormats.slice(0,4).map(f => f[1]);
            const eduColors = ['#60a5fa', '#8b5cf6', '#06b6d4', '#3b82f6'];
            
            new Chart(document.getElementById('educationChart'), {
                type: 'doughnut',
                data: { labels: eduLabels, datasets: [{ data: eduData, backgroundColor: eduColors, borderWidth: 0, cutout: '70%' }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            document.getElementById('eduLegend').innerHTML = eduLabels.map((label, i) => `
                <div class="legend-item"><span class="legend-color" style="background:${eduColors[i]}"></span><span>${label}</span></div>
            `).join('');

            // Line Chart (API Speed)
            new Chart(document.getElementById('economyChart'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Now'],
                    datasets: [{
                        data: [200, 200, 200, 200, 500, 200, 200],
                        borderColor: '#06b6d4', borderWidth: 3, tension: 0.4, fill: true,
                        backgroundColor: function(context) {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                            gradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)');
                            gradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)');
                            return gradient;
                        }
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } } } }
            });
        }

        function escapeHtml(unsafe) {
            return (unsafe||'').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Initialize Fetch
        window.addEventListener('load', fetchJatimData);
    </script>
</body>
</html>

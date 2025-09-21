<?php 
include 'koneksi.php';

// Ambil ID sekolah dari URL
$id_sekolah = $_GET['id'] ?? 0;

// Query data sekolah
$query_sekolah = "SELECT s.*, l.latitude, l.longitude 
                 FROM sekolah s
                 LEFT JOIN lokasi l ON s.id_lokasi = l.id_lokasi
                 WHERE s.id_sekolah = ?";
$stmt = $conn->prepare($query_sekolah);
$stmt->bind_param("i", $id_sekolah);
$stmt->execute();
$sekolah = $stmt->get_result()->fetch_assoc();  

// Query fasilitas
$query_fasilitas = "SELECT * FROM fasilitas_sekolah WHERE id_sekolah = ?";
$stmt_fas = $conn->prepare($query_fasilitas);
$stmt_fas->bind_param("i", $id_sekolah);
$stmt_fas->execute();
$fasilitas = $stmt_fas->get_result()->fetch_assoc();

if (!$sekolah) {
    die("Data sekolah tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($sekolah['nama_sekolah']) ?></title>
    <link rel="stylesheet" href="dist/css/detail_sekolah.css">

    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    
    <!-- Leaflet CSS (untuk peta) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 400px; margin-top: 20px; border-radius: 10px; }
        .detail-container { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        @media (max-width: 768px) { .detail-container { grid-template-columns: 1fr; } }
    </style>
</head>
<body id="detail-sekolah">
    <div class="container">
        <a href="daftar_sekolah.php" class="back-button">← Back</a>
        
        <h1 class="school-title"><?= htmlspecialchars($sekolah['nama_sekolah']) ?></h1>
        
        <div class="detail-container">
            <div class="school-info">
                <div class="info-box">
                    <h3>Informasi Sekolah</h3>
                    <p><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($sekolah['alamat'])) ?></p>
                    <p><strong>Akreditasi:</strong> <?= htmlspecialchars($sekolah['akreditasi']) ?></p>
                    <p><strong>Jumlah Siswa Laki-laki:</strong> <?= number_format($sekolah['jumlah_siswal']) ?></p>
                    <p><strong>Jumlah Siswa Perempuan:</strong> <?= number_format($sekolah['jumlah_siswap']) ?></p>
                    <p><strong>Kurikulum:</strong> <?= htmlspecialchars($sekolah['kurikulum']) ?></p>
                </div>
                
                <div class="info-box">
                    <h3>Fasilitas</h3>
                    <p><strong>Ruang Kelas:</strong> <?= $fasilitas['jumlah_ruang_kelas'] ?? 0 ?></p>
                    <p><strong>Laboratorium:</strong> <?= $fasilitas['jumlah_laboratorium'] ?? 0 ?></p>
                    <p><strong>Perpustakaan:</strong> <?= $fasilitas['jumlah_perpustakaan'] ?? 0 ?></p>
                </div>
            </div>
            
            <!-- map -->
            <div class="map-container">
                <h3>Lokasi Sekolah</h3>
                <div id="map"></div>
                <?php if ($sekolah['latitude'] && $sekolah['longitude']): ?>
                    <p class="map-coords">
                        Koordinat: <?= $sekolah['latitude'] ?>, <?= $sekolah['longitude'] ?>
                    </p>
                <?php else: ?>
                    <p class="map-missing">Lokasi tidak tersedia</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        <?php if ($sekolah['latitude'] && $sekolah['longitude']): ?>
            const map = L.map('map').setView([<?= $sekolah['latitude'] ?>, <?= $sekolah['longitude'] ?>], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            
            L.marker([<?= $sekolah['latitude'] ?>, <?= $sekolah['longitude'] ?>])
                .addTo(map)
                .bindPopup("<b><?= addslashes($sekolah['nama_sekolah']) ?></b>")
                .openPopup();
        <?php endif; ?>
    </script>
</body>
</html>
<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Sekolah</title>

    <!-- Fav Icon -->
    <link rel="shortcut icon" href="/assets/icon/favicon.ico" type="image/x-icon" />

    <!-- CSS -->
    <link rel="stylesheet" href="dist/css/daftar_sekolah.css" />

 <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Font Awesome CDN -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />

    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    
</head>
<body id="daftar-sekolah">
    <div class="container">
    <a href="index.html" class="back-button">← Back</a>
        <h2 class="title" style="text-align:center; margin-bottom:40px;">Daftar Sekolah Menengah di Kota Bogor</h2>

        <!-- Filter Sekolah -->
        <div class="filter-container">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="sma">SMA Negeri</button>
            <button class="filter-btn" data-filter="smk">SMK Negeri</button>
        </div>
        <div class="sekolah-box">
            <?php
            $query = "SELECT * FROM sekolah";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Deteksi jenis sekolah berdasarkan nama
                    $jenis = (strpos($row['nama_sekolah'], 'SMK') !== false || 
                             strpos($row['nama_sekolah'], 'SMK') !== false) ? 'smk' : 'sma';
                    
                    // sekolah
                    echo "<div class='sekolah' data-aos='fade-up' data-jenis='$jenis'>";
                    echo "<div class='desc'>";
                    echo "<h3><a href='detail_sekolah.php?id=" . $row['id_sekolah'] . "'>" . htmlspecialchars($row['nama_sekolah']) . "</a></h3>";
                    echo "<p><i class='fas fa-map-marker-alt'></i> " . htmlspecialchars($row['alamat']) . "</p>";
                    echo "<span class='jenis-badge'>" . ($jenis === 'smk' ? 'SMK' : 'SMA') . "</span>";
                    echo "</div></div>";
                }
            } else {
                echo "<p style='text-align:center;'>Tidak ada data sekolah.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <!-- AOS script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();

         // Fungsi klik filter sekolah
         document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update tombol aktif
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.getAttribute('data-filter');
        const sekolahItems = document.querySelectorAll('.sekolah');
        
        sekolahItems.forEach(item => {
            const itemJenis = item.getAttribute('data-jenis');
            
            if (filter === 'all') {
                item.style.display = 'block';
            } else if (filter === 'sma') {
                item.style.display = itemJenis === 'sma' ? 'block' : 'none';
            } else if (filter === 'smk') { 
                item.style.display = itemJenis === 'smk' ? 'block' : 'none';
            }
        });
        AOS.refresh(); // 

    });
});
    </script>
</body>
</html>

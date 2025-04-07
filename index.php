<?php
// Panggil data dari santri.php
require 'edit/simpan.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas Santri</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }
        
        .id-card {
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .id-card:hover {
            transform: translateY(-5px);
        }
        
        .header-bg {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        
        .photo-placeholder {
            border: 3px solid white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .status-active {
            background-color: #10b981;
        }
        
        .status-inactive {
            background-color: #ef4444;
        }
        
        .qr-code {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
        }
        
        .signature-line {
            border-top: 1px solid #94a3b8;
            position: relative;
        }
        
        .signature-line::after {
            content: "Tanda Tangan";
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 10px;
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .signature-img {
            height: 40px;
            object-fit: contain;
        }
        
        .date-field {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }
        
        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        
        /* Popup styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .popup-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        
        .popup-overlay.active .popup-content {
            transform: translateY(0);
        }
        
        .popup-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
            color: #64748b;
            transition: color 0.2s;
        }
        
        .popup-close:hover {
            color: #334155;
        }
        
        .image-container {
            margin: 20px 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .image-container img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .download-image-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .download-image-btn:hover {
            background-color: #2563eb;
        }
        
        .download-image-btn i {
            margin-right: 8px;
        }
        
        .view-image-btn {
            background-color: #4f46e5;
            color: white;
            border: none;
            transition: all 0.2s;
        }
        
        .view-image-btn:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
        }
        
        /* Chart Section Styles */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .chart-period-selector {
            display: flex;
            gap: 10px;
        }
        
        .period-btn {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
            color: #64748b;
        }
        
        .period-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid;
        }
        
        .stat-card.today {
            border-left-color: #10b981;
        }
        
        .stat-card.week {
            border-left-color: #3b82f6;
        }
        
        .stat-card.month {
            border-left-color: #8b5cf6;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #64748b;
        }
        
        .chart-wrapper {
            height: 250px;
            position: relative;
        }
        
        /* Certificate Section Styles */
        .certificate-section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
        }
        
        .view-all-btn {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: white;
            color: #3b82f6;
            display: flex;
            align-items: center;
        }
        
        .view-all-btn:hover {
            background: #f8fafc;
        }
        
        .certificate-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .certificate-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            position: relative;
        }
        
        .certificate-card:hover {
            transform: translateY(-5px);
        }
        
        .certificate-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        
        .certificate-info {
            padding: 12px;
            background: white;
        }
        
        .certificate-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #1e293b;
        }
        
        .certificate-date {
            font-size: 12px;
            color: #64748b;
        }
        
        .certificate-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            color: #3b82f6;
        }
        
        /* Certificate Popup */
        .certificate-popup {
            max-width: 800px;
            width: 90%;
        }
        
        .certificate-popup-img {
            max-height: 70vh;
            object-fit: contain;
        }
        
        /* Edit Form Styles */
        .edit-form-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #334155;
        }
        
        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        
        .form-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            background-color: white;
        }
        
        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        
        .form-submit-btn {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 100%;
        }
        
        .form-submit-btn:hover {
            background-color: #2563eb;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Hafalan Result Styles */
        .hafalan-result {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .hafalan-result-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .hafalan-result-title i {
            margin-right: 10px;
            color: #3b82f6;
        }

        .hafalan-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .hafalan-item {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #3b82f6;
        }

        .hafalan-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
        }

        .hafalan-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .hafalan-rating {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .rating-excellent {
            background-color: #10b981;
            color: white;
        }

        .rating-very-good {
            background-color: #3b82f6;
            color: white;
        }

        .rating-good {
            background-color: #f59e0b;
            color: white;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <?php
        // Initialize variables with default values
        $nama_lengkap = "$nama_lengkap";
        $status = "$status";
        $kelas_mutqin = "$kelas_mutqin";
        $tanggal_lahir = "$tanggal_lahir";
        $alamat = "$alamat";
        $nomor_id = "$nomor_id";
        $kelas = "$kelas";
        $berlaku_hingga = "$berlaku_hingga";
        $foto_profil = "$foto_profil";
        $qr_code = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://5tb.my.id/data/data.php";
        $tanda_tangan = "$tanda_tangan";
        $telepon = "(+62) 8710000164";
        $website = "www.rqnwlombok.sch.id";
        $tanggal_dibuat = "06 April 2025";
        $umur = "17 tahun";
        $sertifikat = "$sertifikat";
        
        // Hafalan data
        $jumlah_hafalan = "3 Juz";
        $nilai_hafalan = "Sangat Baik";
        $persentase_hafalan = "90%";
        $nilai_tartil = "Baik";
        $persentase_tartil = "80%";
        $kemampuan = "Memuaskan";
        $persentase_kemampuan = "95%";
        
        // Certificate data
        $certificates = array(
            array(
                "image" => "$sertifikat",
                "title" => "Sertifikat Tahfidz Juz 30",
                "date" => "15 Januari 2024",
                "badge" => "Tahfidz"
            ),
            array(
                "image" => "https://5tb.my.id/data/img/sertifikat.webp",
                "title" => "Khatam Al-Quran",
                "date" => "30 Maret 2024",
                "badge" => "Khatam"
            ),
            array(
                "image" => "https://5tb.my.id/data/img/sertifikat.webp",
                "title" => "Sertifikat MHQ Regional",
                "date" => "12 Mei 2024",
                "badge" => "MHQ"
            ),
            array(
                "image" => "https://5tb.my.id/data/img/sertifikat.webp",
                "title" => "Pesantren Kilat Ramadhan",
                "date" => "1 April 2024",
                "badge" => "Ramadhan"
            )
        );
        ?>
        
        <div class="id-card bg-white">
            <!-- Card Header -->
            <div class="header-bg py-4 px-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-xl font-bold">IDENTITAS SANTRI</h1>
                        <p class="text-sm opacity-80">RQNW Lombok</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs">NO. Identitas: <?php echo htmlspecialchars($nomor_id); ?></p>
                        <p class="text-xs">Berlaku hingga: <?php echo htmlspecialchars($berlaku_hingga); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="p-6">
                <div class="flex gap-6 mb-6">
                    <!-- Photo -->
                    <div class="photo-placeholder w-24 h-32 rounded-lg overflow-hidden">
                        <img src="<?php echo htmlspecialchars($foto_profil); ?>" 
                             alt="Profile Photo" class="profile-photo">
                    </div>
                    
                    <!-- Personal Info -->
                    <div class="flex-1">
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($nama_lengkap); ?></h2>
                            <p class="text-gray-600">Santri Kelas <?php echo htmlspecialchars($kelas); ?></p>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-building mr-2 text-blue-500 w-5"></i>
                                <span><?php echo htmlspecialchars($kelas_mutqin); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500 w-5"></i>
                                <span><?php echo htmlspecialchars($alamat); ?></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-birthday-cake mr-2 text-blue-500 w-5"></i>
                                <span><?php echo htmlspecialchars($tanggal_lahir); ?> (<?php echo htmlspecialchars($umur); ?>)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status and QR Code -->
                <div class="flex justify-between items-center mt-6">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-600 mr-2">Status:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold text-white <?php echo $status === 'Aktif' ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </span>
                    </div>
                    
                    <div class="qr-code w-20 h-20 rounded overflow-hidden">
                        <img src="<?php echo htmlspecialchars($qr_code); ?>" 
                             alt="QR Code" class="w-full h-full object-cover">
                    </div>
                </div>
                
                <!-- Signature Area -->
                <div class="mt-8 pt-4 signature-line relative">
                    <div class="flex justify-between">
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Dibuat</p>
                            <p class="text-xs font-medium"><?php echo htmlspecialchars($tanggal_dibuat); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-gray-500 mb-1">Pemegang Kartu</p>
                            <img src="<?php echo htmlspecialchars($tanda_tangan); ?>" 
                                 alt="Tanda Tangan" class="signature-img mx-auto">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-3 text-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-phone-alt mr-1"></i> <?php echo htmlspecialchars($telepon); ?> | 
                    <i class="fas fa-globe mr-1 ml-2"></i> <?php echo htmlspecialchars($website); ?>
                </p>
            </div>
        </div>
        
        <!-- Display only - no edit form -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium mb-4">Informasi Kartu Identitas</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="mt-1 block w-full rounded-md bg-gray-100 p-2"><?php echo htmlspecialchars($nama_lengkap); ?></div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <div class="mt-1 block w-full rounded-md bg-gray-100 p-2"><?php echo htmlspecialchars($alamat); ?></div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <div class="mt-1 block w-full rounded-md bg-gray-100 p-2"><?php echo htmlspecialchars($tanggal_lahir); ?></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1 block w-full rounded-md bg-gray-100 p-2"><?php echo htmlspecialchars($status); ?></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor ID</label>
                        <div class="mt-1 block w-full rounded-md bg-gray-100 p-2"><?php echo htmlspecialchars($nomor_id); ?></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kelas</label>
                        <div class="mt-1 block w-full rounded-md bg-gray-100 p-2"><?php echo htmlspecialchars($kelas); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- View Image Button -->
            <button id="viewImageBtn" class="w-full mt-6 view-image-btn text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center">
                <i class="fas fa-image mr-2"></i> Lihat Gambar
            </button>

            <!-- Hasil Hafalan Section -->
            <div class="hafalan-result">
                <div class="hafalan-result-title">
                    <i class="fas fa-quran"></i>
                    Hasil Hafalan
                </div>
                
                <div class="hafalan-grid">
                    <div class="hafalan-item">
                        <div class="hafalan-label">Jumlah Hafalan</div>
                        <div class="hafalan-value"><?php echo htmlspecialchars($jumlah_hafalan); ?></div>
                    </div>
                    
                    <div class="hafalan-item">
                        <div class="hafalan-label">Nilai Hafalan</div>
                        <div>
                            <span class="hafalan-value"><?php echo htmlspecialchars($nilai_hafalan); ?></span>
                            <span class="hafalan-rating rating-very-good ml-2"><?php echo htmlspecialchars($persentase_hafalan); ?></span>
                        </div>
                    </div>
                    
                    <div class="hafalan-item">
                        <div class="hafalan-label">Tartil</div>
                        <div>
                            <span class="hafalan-value"><?php echo htmlspecialchars($nilai_tartil); ?></span>
                            <span class="hafalan-rating rating-good ml-2"><?php echo htmlspecialchars($persentase_tartil); ?></span>
                        </div>
                    </div>
                    
                    <div class="hafalan-item">
                        <div class="hafalan-label">Kemampuan</div>
                        <div>
                            <span class="hafalan-value"><?php echo htmlspecialchars($kemampuan); ?></span>
                            <span class="hafalan-rating rating-excellent ml-2"><?php echo htmlspecialchars($persentase_kemampuan); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- Certificate Section -->
        <div class="certificate-section">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-certificate text-blue-500 mr-2"></i>
                    Sertifikat
                </div>
                <button class="view-all-btn">
                    Lihat Semua <i class="fas fa-chevron-right ml-1"></i>
                </button>
            </div>
            
            <div class="certificate-grid">
                <?php foreach ($certificates as $certificate): ?>
                <div class="certificate-card" onclick="openCertificatePopup('<?php echo htmlspecialchars($certificate['image']); ?>', '<?php echo htmlspecialchars($certificate['title']); ?>')">
                    <img src="<?php echo htmlspecialchars($certificate['image']); ?>" alt="<?php echo htmlspecialchars($certificate['title']); ?>" class="certificate-img">
                    <div class="certificate-info">
                        <div class="certificate-title"><?php echo htmlspecialchars($certificate['title']); ?></div>
                        <div class="certificate-date"><?php echo htmlspecialchars($certificate['date']); ?></div>
                    </div>
                    <div class="certificate-badge"><?php echo htmlspecialchars($certificate['badge']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Image Popup Overlay -->
    <div id="imagePopupOverlay" class="popup-overlay">
        <div class="popup-content relative">
            <span id="imagePopupClose" class="popup-close">&times;</span>
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Kartu Identitas Santri</h3>
            
            <div class="image-container">
                <img src="<?php echo htmlspecialchars($foto_profil); ?>" alt="Kartu Identitas Santri">
            </div>
            
            <button id="downloadImageBtn" class="download-image-btn">
                <i class="fas fa-download"></i> Download Gambar
            </button>
            
            <p class="text-xs text-gray-500 mt-4 text-center">
                Gambar kartu identitas dalam format JPG
            </p>
        </div>
    </div>
    
    <!-- Certificate Popup Overlay -->
    <div id="certificatePopupOverlay" class="popup-overlay">
        <div class="popup-content certificate-popup relative">
            <span id="certificatePopupClose" class="popup-close">&times;</span>
            <h3 id="certificatePopupTitle" class="text-xl font-bold text-gray-800 mb-4 text-center"></h3>
            
            <div class="image-container">
                <img id="certificatePopupImg" src="" alt="Sertifikat" class="certificate-popup-img">
            </div>
            
            <button id="downloadCertificateBtn" class="download-image-btn mt-4">
                <i class="fas fa-download"></i> Download Sertifikat
            </button>
            
            <p class="text-xs text-gray-500 mt-2 text-center">
                Format JPG - Resolusi Tinggi
            </p>
        </div>
    </div>
    
    <script>
        // Show image popup when view image button is clicked
        document.getElementById('viewImageBtn').addEventListener('click', function() {
            document.getElementById('imagePopupOverlay').classList.add('active');
        });
        
        // Close popup when close button is clicked
        document.getElementById('imagePopupClose').addEventListener('click', function() {
            document.getElementById('imagePopupOverlay').classList.remove('active');
        });
        
        // Close popup when clicking outside
        document.getElementById('imagePopupOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
        
        // Download image functionality
        document.getElementById('downloadImageBtn').addEventListener('click', function() {
            // Create a temporary anchor element
            const link = document.createElement('a');
            link.href = '<?php echo htmlspecialchars($foto_profil); ?>';
            link.download = 'kartu-identitas-santri.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Close the popup after download starts
            document.getElementById('imagePopupOverlay').classList.remove('active');
        });
        
        // Certificate popup functions
        function openCertificatePopup(imgSrc, title) {
            document.getElementById('certificatePopupImg').src = imgSrc;
            document.getElementById('certificatePopupTitle').textContent = title;
            document.getElementById('certificatePopupOverlay').classList.add('active');
            
            // Set download button to download this certificate
            document.getElementById('downloadCertificateBtn').onclick = function() {
                const link = document.createElement('a');
                link.href = imgSrc;
                link.download = title.toLowerCase().replace(/ /g, '-') + '.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            };
        }
        
        // Close certificate popup
        document.getElementById('certificatePopupClose').addEventListener('click', function() {
            document.getElementById('certificatePopupOverlay').classList.remove('active');
        });
        
        // Close certificate popup when clicking outside
        document.getElementById('certificatePopupOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
            }
        });
        
        // Initialize Hafalan Chart
        const ctx = document.getElementById('hafalanChart').getContext('2d');
        const hafalanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'],
                datasets: [{
                    label: 'Jumlah Ayat',
                    data: [5, 7, 3, 6, 4, 8, 2],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rg59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(59, 130, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(59, 130, 246, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: {
                            family: 'Poppins',
                            size: 12
                        },
                        bodyFont: {
                            family: 'Poppins',
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 6
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e2e8f0',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: 'Poppins',
                                size: 10
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                family: 'Poppins',
                                size: 10
                            }
                        }
                    }
                }
            }
        });
        
        // Period selector functionality
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                periodButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                // Here you would typically update the charQt data based on the selected period
                // For demo purposes, we'll just log the selected period
                console.log('Selected period:', button.textContent);
            });
        });
    </script>
</body>
</html>

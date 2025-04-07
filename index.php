<?php

session_start();

// Redirect ke halaman login jika belum login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Ambil data lama dari simpan.php
if (file_exists("simpan.php")) {
    require "simpan.php";
} else {
    $nama_lengkap = "";
}

// Simpan data baru jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = $_POST['nama_lengkap'];

    // Buat file PHP berisi variabel
    $isi_file = "<?php\n";
    $isi_file .= '$nama_lengkap = "' . addslashes($nama_lengkap) . "\";\n";
    $isi_file .= "?>";

    // Simpan ke simpan.php
    file_put_contents("simpan.php", $isi_file);
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Data Santri</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .form-card {
      background: linear-gradient(135deg, #f9f9ff 0%, #ffffff 100%);
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    .form-section {
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 1rem;
      margin-bottom: 1.5rem;
    }
    .form-label {
      color: #4a5568;
      font-weight: 500;
      margin-bottom: 0.5rem;
      display: block;
    }
    .form-input {
      transition: all 0.3s ease;
      border: 1px solid #cbd5e0;
    }
    .form-input:focus {
      border-color: #4299e1;
      box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    }
    .status-badge {
      position: relative;
      top: -10px;
    }
    .prefix-input {
      display: flex;
      align-items: center;
    }
    .prefix {
      background-color: #edf2f7;
      padding: 0.75rem;
      border: 1px solid #cbd5e0;
      border-right: none;
      border-radius: 0.5rem 0 0 0.5rem;
      font-weight: 500;
    }
    .prefix-input input {
      border-radius: 0 0.5rem 0.5rem 0 !important;
    }
    .status-option {
      display: flex;
      align-items: center;
      padding: 0.5rem;
    }
    .status-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      margin-right: 8px;
    }
    .aktif { background-color: #10B981; }
    .non-aktif { background-color: #EF4444; }
    .alumni { background-color: #3B82F6; }
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
      transform: translateX(120%);
      transition: transform 0.3s ease;
    }
    .notification.show {
      transform: translateX(0);
    }
    @media (max-width: 640px) {
      .header-content {
        flex-direction: column;
        text-align: center;
      }
      .header-info {
        margin-top: 1rem;
      }
      .grid-cols-1 {
        grid-template-columns: 1fr !important;
      }
      .form-card {
        padding: 1.5rem;
      }
      .prefix-input {
        flex-direction: column;
      }
      .prefix {
        border-radius: 0.5rem 0.5rem 0 0;
        border-right: 1px solid #cbd5e0;
        border-bottom: none;
        width: 100%;
        text-align: center;
      }
      .prefix-input input {
        border-radius: 0 0 0.5rem 0.5rem !important;
        width: 100%;
      }
    }
  </style>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Main Content Container -->
  <div class="container mx-auto px-4 py-4 md:py-8 max-w-4xl">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg rounded-xl mb-6 md:mb-8">
      <div class="p-4 md:p-6">
        <div class="header-content flex flex-col md:flex-row justify-between items-center">
          <div class="flex items-center space-x-4">
            <i class="fas fa-mosque text-2xl md:text-3xl"></i>
            <div>
              <h1 class="text-xl md:text-2xl font-bold">Data Santri Updater</h1>
              <p class="text-blue-100 text-sm md:text-base">Sistem Informasi Santri v1.0</p>
            </div>
          </div>
          <div class="header-info flex items-center space-x-4 mt-2 md:mt-0">
            <div class="text-right hidden md:block">
              <p class="font-medium"><?php echo date('d F Y'); ?></p>
            </div>
            
          </div>
        </div>
      </div>
    </header>

    <!-- Notification Popup -->
    <div id="notification" class="notification hidden">
      <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-lg max-w-sm">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <div class="flex-shrink-0 text-green-500">
              <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div class="ml-3">
              <p class="text-sm text-green-700 font-medium">
                <span class="font-bold">Simpan Perubahan Berhasil!</span> Data santri telah diperbarui.
              </p>
            </div>
          </div>
          <button onclick="hideNotification()" class="ml-4 text-green-500 hover:text-green-700">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Form Card -->
    <div class="form-card p-4 md:p-6 lg:p-8">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-xl md:text-2xl font-bold text-gray-800">Edit Data Santri</h1>
          <p class="text-gray-600 text-sm md:text-base">Perbarui informasi santri dengan lengkap</p>
        </div>
      </div>

      <form method="post" action="" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Kolom Pertama -->
          <div>
            <div class="form-section">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($nama_lengkap); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>

            <div class="form-section">
              <label class="form-label">Nomor ID</label>
              <div class="prefix-input">
                <span class="prefix">RQ-</span>
                <input type="number" name="nomor_id" value="<?php echo htmlspecialchars(str_replace('RQ-', '', $nomor_id)); ?>" 
                       class="form-input w-full p-2 md:p-3 rounded-lg" placeholder="xxxx">
              </div>
            </div>

            <div class="form-section">
              <label class="form-label">Kelas</label>
              <select name="kelas" class="form-input w-full p-2 md:p-3 rounded-lg">
                <option value="1 SMP" <?php echo ($kelas == '1 SMP') ? 'selected' : ''; ?>>1 SMP</option>
                <option value="2 SMP" <?php echo ($kelas == '2 SMP') ? 'selected' : ''; ?>>2 SMP</option>
                <option value="3 SMP" <?php echo ($kelas == '3 SMP') ? 'selected' : ''; ?>>3 SMP</option>
                <option value="1 SMA" <?php echo ($kelas == '1 SMA') ? 'selected' : ''; ?>>1 SMA</option>
                <option value="2 SMA" <?php echo ($kelas == '2 SMA') ? 'selected' : ''; ?>>2 SMA</option>
                <option value="3 SMA" <?php echo ($kelas == '3 SMA') ? 'selected' : ''; ?>>3 SMA</option>
              </select>
            </div>

            <div class="form-section">
              <label class="form-label">Program Santri</label>
              <select name="kelas_mutqin" class="form-input w-full p-2 md:p-3 rounded-lg">
                <option value="Kelas Mutqin" <?php echo ($kelas_mutqin == 'Kelas Mutqin') ? 'selected' : ''; ?>>Kelas Mutqin</option>
                <option value="Kelas Tahfiz" <?php echo ($kelas_mutqin == 'Kelas Tahfiz') ? 'selected' : ''; ?>>Kelas Tahfiz</option>
                <option value="Kelas Tasri" <?php echo ($kelas_mutqin == 'Kelas Tasri') ? 'selected' : ''; ?>>Kelas Tasri</option>
              </select>
            </div>

            <div class="form-section">
              <label class="form-label">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>

            <div class="form-section">
              <label class="form-label">Berlaku Hingga</label>
              <input type="date" name="berlaku_hingga" value="<?php echo htmlspecialchars($berlaku_hingga); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>
          </div>

          <!-- Kolom Kedua -->
          <div>
            <div class="form-section">
              <label class="form-label">Foto Profil (URL)</label>
              <input type="text" name="foto_profil" value="<?php echo htmlspecialchars($foto_profil); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>

            <div class="form-section">
              <label class="form-label">Tanda Tangan (URL)</label>
              <input type="text" name="tanda_tangan" value="<?php echo htmlspecialchars($tanda_tangan); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>

            <div class="form-section">
              <label class="form-label">Kartu Tanda Santri (URL)</label>
              <input type="text" name="kartu_tanda_santri" value="<?php echo htmlspecialchars($kartu_tanda_santri); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>

            <div class="form-section">
              <label class="form-label">Sertifikat (URL)</label>
              <input type="text" name="sertifikat" value="<?php echo htmlspecialchars($sertifikat); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg">
            </div>

            <div class="form-section">
              <label class="form-label">Nomor HP</label>
              <input type="tel" name="nomor_hp" value="<?php echo htmlspecialchars($nomor_hp); ?>" 
                     class="form-input w-full p-2 md:p-3 rounded-lg" placeholder="08xxxxxxxxxx">
            </div>

            <div class="form-section">
              <label class="form-label">Status</label>
              <select name="status" class="form-input w-full p-2 md:p-3 rounded-lg">
                <option value="Aktif" <?php echo ($status == 'Aktif') ? 'selected' : ''; ?>>
                  <div class="status-option">
                    <span class="status-dot aktif"></span>
                    Aktif
                  </div>
                </option>
                <option value="Non-Aktif" <?php echo ($status == 'Non-Aktif') ? 'selected' : ''; ?>>
                  <div class="status-option">
                    <span class="status-dot non-aktif"></span>
                    Non-Aktif
                  </div>
                </option>
                <option value="Alumni" <?php echo ($status == 'Alumni') ? 'selected' : ''; ?>>
                  <div class="status-option">
                    <span class="status-dot alumni"></span>
                    Alumni
                  </div>
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="form-section">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-input w-full p-2 md:p-3 rounded-lg h-24"><?php echo htmlspecialchars($alamat); ?></textarea>
        </div>

        <div class="flex justify-end pt-4">
          <button type="submit" 
                  class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 md:py-3 md:px-6 rounded-lg transition duration-300 shadow-md text-sm md:text-base"
                  onclick="showNotification()">
            <i class="fas fa-save mr-2"></i> Simpan Perubahan
          </button>
        </div>
      </form>

      <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Ambil data dari form
          $nama_lengkap = $_POST['nama_lengkap'];
          $status = $_POST['status'];
          $kelas_mutqin = $_POST['kelas_mutqin'];
          $tanggal_lahir = $_POST['tanggal_lahir'];
          $alamat = $_POST['alamat'];
          $nomor_id = 'RQ-' . $_POST['nomor_id'];
          $kelas = $_POST['kelas'];
          $berlaku_hingga = $_POST['berlaku_hingga'];
          $foto_profil = $_POST['foto_profil'];
          $tanda_tangan = $_POST['tanda_tangan'];
          $kartu_tanda_santri = $_POST['kartu_tanda_santri'];
          $sertifikat = $_POST['sertifikat'];
          $nomor_hp = $_POST['nomor_hp'];
          
          // Buat isi PHP baru
          $isi_file = "<?php\n";
          $isi_file .= '$nama_lengkap = "' . addslashes($nama_lengkap) . "\";\n";
          $isi_file .= '$status = "' . addslashes($status) . "\";\n";
          $isi_file .= '$kelas_mutqin = "' . addslashes($kelas_mutqin) . "\";\n";
          $isi_file .= '$tanggal_lahir = "' . addslashes($tanggal_lahir) . "\";\n";
          $isi_file .= '$alamat = "' . addslashes($alamat) . "\";\n";
          $isi_file .= '$nomor_id = "' . addslashes($nomor_id) . "\";\n";
          $isi_file .= '$nomor_hp = "' . addslashes($nomor_hp) . "\";\n";
          $isi_file .= '$foto_profil = "' . addslashes($foto_profil) . "\";\n";
          $isi_file .= '$tanda_tangan = "' . addslashes($tanda_tangan) . "\";\n";
          $isi_file .= '$kartu_tanda_santri = "' . addslashes($kartu_tanda_santri) . "\";\n";
          $isi_file .= '$sertifikat = "' . addslashes($sertifikat) . "\";\n";
          $isi_file .= '$kelas = "' . addslashes($kelas) . "\";\n";
          $isi_file .= '$berlaku_hingga = "' . addslashes($berlaku_hingga) . "\";\n";
          $isi_file .= "?>";

          // Simpan ke simpan.php
          file_put_contents('simpan.php', $isi_file);
      }
      ?>
    </div>
  </div>

  <script>
    // Notification functions
    function showNotification() {
      const notification = document.getElementById('notification');
      notification.classList.remove('hidden');
      notification.classList.add('show');
      
      // Auto hide after 5 seconds
      setTimeout(() => {
        hideNotification();
      }, 5000);
    }
    
    function hideNotification() {
      const notification = document.getElementById('notification');
      notification.classList.remove('show');
      setTimeout(() => {
        notification.classList.add('hidden');
      }, 300);
    }

    // Custom styling for status dropdown options
    document.addEventListener('DOMContentLoaded', function() {
      const statusSelect = document.querySelector('select[name="status"]');
      
      // Create custom options
      const options = [
        { value: 'Aktif', text: 'Aktif', color: 'bg-green-500' },
        { value: 'Non-Aktif', text: 'Non-Aktif', color: 'bg-red-500' },
        { value: 'Alumni', text: 'Alumni', color: 'bg-blue-500' }
      ];
      
      // Clear existing options
      statusSelect.innerHTML = '';
      
      // Add new options with custom styling
      options.forEach(option => {
        const opt = document.createElement('option');
        opt.value = option.value;
        
        const div = document.createElement('div');
        div.className = 'flex items-center';
        
        const dot = document.createElement('span');
        dot.className = `inline-block w-2 h-2 rounded-full mr-2 ${option.color}`;
        
        const text = document.createTextNode(option.text);
        
        div.appendChild(dot);
        div.appendChild(text);
        
        opt.appendChild(div);
        statusSelect.appendChild(opt);
        
        // Set selected if matches current status
        if (option.value === '<?php echo $status; ?>') {
          opt.selected = true;
        }
      });
    });
  </script>
</body>
</html>
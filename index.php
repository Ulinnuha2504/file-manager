
<?php
$targetDir = __DIR__ . '/';
$message = '';
$error = '';

// ======================================================
// 1. FITUR HAPUS FILE
// ======================================================
if (isset($_GET['delete'])) {
    $fileToDelete = basename($_GET['delete']);
    $filePath = $targetDir . $fileToDelete;

    // Keamanan: cegah hapus index.php dan .htaccess
    if (
        $fileToDelete !== 'index.php' &&
        $fileToDelete !== '.htaccess' &&
        file_exists($filePath) &&
        is_file($filePath)
    ) {
        if (unlink($filePath)) {
            header("Location: index.php?msg=deleted");
            exit;
        } else {
            $error = "Gagal menghapus file.";
        }
    }
}

// ======================================================
// 2. FITUR UPLOAD FILE
// ======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    $file = $_FILES['file'];

    if ($file['error'] === UPLOAD_ERR_OK) {

        $fileName = basename($file['name']);

        // Mencegah overwrite file sistem
        if (
            $fileName === 'index.php' ||
            $fileName === '.htaccess'
        ) {
            $error = "Nama file ini tidak diperbolehkan!";
        } else {

            $targetPath = $targetDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                header("Location: index.php?msg=uploaded");
                exit;
            } else {
                $error = "Gagal menyimpan file ke server.";
            }
        }

    } else {
        $error = "Terjadi kesalahan saat mengunggah file.";
    }
}

// ======================================================
// 3. PESAN NOTIFIKASI
// ======================================================
if (isset($_GET['msg'])) {

    if ($_GET['msg'] === 'uploaded') {
        $message = "File berhasil diunggah!";
    }

    if ($_GET['msg'] === 'deleted') {
        $message = "File berhasil dihapus!";
    }
}

// ======================================================
// 4. AMBIL DAFTAR FILE
// ======================================================
$files = array_diff(
    scandir($targetDir),
    array('.', '..', 'index.php', '.htaccess')
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Lab File Manager</title>

<style>

/* =====================================================
   GLOBAL
===================================================== */

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #0f172a;
    color: #f8fafc;
    min-height: 100vh;
    padding: 40px 20px;
    display: flex;
    justify-content: center;
}

.container {
    width: 100%;
    max-width: 800px;
}

.card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    animation: cardIn .5s ease;
}

h1 {
    font-size: 1.5rem;
    color: #38bdf8;
    margin-bottom: 8px;
}

h2 {
    font-size: 1.2rem;
}

p.sub {
    color: #94a3b8;
    font-size: .9rem;
    margin-bottom: 20px;
}


/* =====================================================
   UPLOAD FORM
===================================================== */

.upload-form {
    display: flex;
    gap: 10px;
}

input[type="file"] {
    flex: 1;
    background: #1e293b;
    border: 1px solid #334155;
    color: #fff;
    padding: 10px;
    border-radius: 6px;
}

button.btn-upload {
    background: #38bdf8;
    color: #0f172a;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    transition: all .2s ease;
}

button.btn-upload:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(56,189,248,.3);
}

button.btn-upload:disabled {
    opacity: .7;
    cursor: not-allowed;
}


/* =====================================================
   TABLE
===================================================== */

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

th,
td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #334155;
}

th {
    color: #94a3b8;
    font-size: .85rem;
    text-transform: uppercase;
}

td {
    font-size: .95rem;
}


/* =====================================================
   BUTTON DOWNLOAD
===================================================== */

.btn-download {
    background: #22c55e;
    color: #fff;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: .85rem;
    margin-right: 6px;
    display: inline-block;
    transition: all .2s ease;
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(34,197,94,.3);
}


/* =====================================================
   BUTTON DELETE
===================================================== */

.btn-delete {
    background: #ef4444;
    color: #fff;
    padding: 6px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: .85rem;
    display: inline-block;
    transition: all .2s ease;
}

.btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(239,68,68,.3);
}

.btn-delete.loading {
    pointer-events: none;
    opacity: .7;
}


/* =====================================================
   ALERT
===================================================== */

.alert-success,
.alert-danger {
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 15px;
    animation: alertIn .5s ease;
}

.alert-success {
    background: #065f46;
    color: #34d399;
}

.alert-danger {
    background: #881337;
    color: #fda4af;
}


/* =====================================================
   EMPTY
===================================================== */

.empty {
    text-align: center;
    color: #64748b;
    padding: 20px 0;
}


/* =====================================================
   OVERLAY LOADING
===================================================== */

.loading-overlay {
    position: fixed;
    inset: 0;

    background: rgba(15,23,42,.85);

    backdrop-filter: blur(5px);

    display: none;

    align-items: center;
    justify-content: center;

    z-index: 9999;
}

.loading-box {
    text-align: center;
    padding: 30px;
}

.loading-spinner {

    width: 55px;
    height: 55px;

    border: 5px solid rgba(255,255,255,.15);
    border-top-color: #38bdf8;

    border-radius: 50%;

    animation: spin .8s linear infinite;

    margin: 0 auto 18px;
}

.loading-text {
    color: #fff;
    font-size: 1rem;
}

.loading-sub {
    color: #94a3b8;
    font-size: .85rem;
    margin-top: 5px;
}


/* =====================================================
   SUCCESS ANIMATION
===================================================== */

.success-animation {
    position: fixed;

    top: 50%;
    left: 50%;

    transform: translate(-50%, -50%) scale(.7);

    background: #065f46;

    padding: 25px 35px;

    border-radius: 12px;

    text-align: center;

    z-index: 10000;

    opacity: 0;

    pointer-events: none;
}

.success-animation.show {
    animation: successPop .8s ease forwards;
}

.success-icon {
    font-size: 45px;
    margin-bottom: 10px;
}

.success-text {
    color: #34d399;
    font-weight: bold;
}


/* =====================================================
   ANIMATIONS
===================================================== */

@keyframes spin {

    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }

}

@keyframes cardIn {

    from {
        opacity: 0;
        transform: translateY(15px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}

@keyframes alertIn {

    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}

@keyframes successPop {

    0% {
        opacity: 0;
        transform: translate(-50%, -50%) scale(.7);
    }

    60% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.05);
    }

    100% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 600px) {

    .upload-form {
        flex-direction: column;
    }

    button.btn-upload {
        width: 100%;
    }

    th:nth-child(2),
    td:nth-child(2) {
        display: none;
    }

    .btn-download,
    .btn-delete {
        margin-bottom: 5px;
    }

}

</style>

</head>


<body>


<!-- =====================================================
     LOADING OVERLAY
===================================================== -->

<div class="loading-overlay" id="loadingOverlay">

    <div class="loading-box">

        <div class="loading-spinner"></div>

        <div class="loading-text" id="loadingText">
            Memproses...
        </div>

        <div class="loading-sub">
            Mohon tunggu sebentar
        </div>

    </div>

</div>


<!-- =====================================================
     SUCCESS POPUP
===================================================== -->

<div class="success-animation" id="successAnimation">

    <div class="success-icon">
        ✓
    </div>

    <div class="success-text">
        Berhasil!
    </div>

</div>


<div class="container">


    <!-- =================================================
         UPLOAD CARD
    ================================================== -->

    <div class="card">

        <h1>⚡ Lab File Manager</h1>

        <p class="sub">
            Upload dan unduh file langsung tanpa $flashdisk$
        </p>


        <?php if ($message): ?>

            <div class="alert-success">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>


        <?php if ($error): ?>

            <div class="alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <form
            action=""
            method="POST"
            enctype="multipart/form-data"
            class="upload-form"
            id="uploadForm"
        >

            <input
                type="file"
                name="file"
                id="fileInput"
                required
            >

            <button
                type="submit"
                class="btn-upload"
                id="uploadButton"
            >
                ⬆ Upload
            </button>

        </form>

    </div>


    <!-- =================================================
         FILE LIST
    ================================================== -->

    <div class="card">

        <h2>
            Daftar File Ter-upload
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        Nama File
                    </th>

                    <th>
                        Ukuran
                    </th>

                    <th style="text-align:right;">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php if (empty($files)): ?>

                <tr>

                    <td
                        colspan="3"
                        class="empty"
                    >
                        Belum ada file yang diunggah.
                    </td>

                </tr>


            <?php else: ?>


                <?php foreach ($files as $f):

                    $filePath = $targetDir . $f;

                    $fileSize = filesize($filePath);

                    $size = round(
                        $fileSize / 1024,
                        2
                    ) . ' KB';


                    if ($fileSize > 1048576) {

                        $size = round(
                            $fileSize / 1048576,
                            2
                        ) . ' MB';

                    }

                ?>


                <tr>

                    <td>

                        <strong>
                            <?= htmlspecialchars($f) ?>
                        </strong>

                    </td>


                    <td>

                        <?= $size ?>

                    </td>


                    <td style="text-align:right;">


                        <!-- DOWNLOAD -->

                        <a
                            href="<?= htmlspecialchars($f) ?>"
                            class="btn-download"
                            download
                        >
                            ↓ Download
                        </a>


                        <!-- DELETE -->

                        <a
                            href="?delete=<?= urlencode($f) ?>"
                            class="btn-delete"
                            onclick="return confirmDelete(this)"
                        >
                            🗑 Hapus
                        </a>


                    </td>

                </tr>


                <?php endforeach; ?>


            <?php endif; ?>


            </tbody>

        </table>

    </div>


</div>


<script>

/* =====================================================
   ELEMENT
===================================================== */

const loadingOverlay =
    document.getElementById('loadingOverlay');

const loadingText =
    document.getElementById('loadingText');

const uploadForm =
    document.getElementById('uploadForm');

const uploadButton =
    document.getElementById('uploadButton');

const fileInput =
    document.getElementById('fileInput');


/* =====================================================
   ANIMASI UPLOAD
===================================================== */

uploadForm.addEventListener('submit', function(e) {

    // Pastikan file sudah dipilih
    if (!fileInput.files.length) {
        return;
    }

    // Tampilkan loading
    loadingText.textContent =
        'Mengunggah file...';

    loadingOverlay.style.display =
        'flex';

    // Disable tombol
    uploadButton.disabled = true;

    uploadButton.innerHTML =
        '⏳ Mengunggah...';

});


/* =====================================================
   ANIMASI DELETE
===================================================== */

function confirmDelete(button) {

    const confirmation =
        confirm('Apakah Anda yakin ingin menghapus file ini?');

    if (!confirmation) {
        return false;
    }


    // Tampilkan loading
    loadingText.textContent =
        'Menghapus file...';

    loadingOverlay.style.display =
        'flex';


    // Ubah tombol
    button.classList.add('loading');

    button.innerHTML =
        '⏳ Menghapus...';


    return true;

}


/* =====================================================
   AUTO HILANGKAN ALERT
===================================================== */

const alertSuccess =
    document.querySelector('.alert-success');

const alertDanger =
    document.querySelector('.alert-danger');


if (alertSuccess) {

    setTimeout(function() {

        alertSuccess.style.transition =
            'opacity .5s, transform .5s';

        alertSuccess.style.opacity =
            '0';

        alertSuccess.style.transform =
            'translateY(-10px)';

        setTimeout(function() {

            alertSuccess.remove();

        }, 500);

    }, 3000);

}


if (alertDanger) {

    setTimeout(function() {

        alertDanger.style.transition =
            'opacity .5s';

        alertDanger.style.opacity =
            '0';

        setTimeout(function() {

            alertDanger.remove();

        }, 500);

    }, 4000);

}

</script>


</body>

</html>

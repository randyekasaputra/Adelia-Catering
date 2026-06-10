<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Image Swapper</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .card { background: white; border: 2px solid transparent; padding: 10px; text-align: center; border-radius: 8px; cursor: pointer; transition: 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card:hover { transform: scale(1.02); }
        .card.selected { border-color: #e74c3c; background: #fdf5f4; }
        img { width: 100%; height: 150px; object-fit: cover; border-radius: 4px; }
        .alert { padding: 15px; background: #2ecc71; color: white; margin-bottom: 20px; border-radius: 4px; display: none; }
        .instructions { background: #3498db; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Alat Perbaikan Gambar Menu</h1>
    
    <div class="instructions">
        <strong>Cara menggunakan:</strong><br>
        Klik pada gambar pertama yang salah, lalu klik pada gambar kedua untuk menukarnya. Sistem akan otomatis menyimpannya ke database.
    </div>

    <div class="alert" id="successAlert">Gambar berhasil ditukar! Halaman akan dimuat ulang...</div>

    <div class="grid">
        <?php foreach(\App\Models\Menu::all() as $m): ?>
            <div class="card" data-id="<?= $m->id ?>" onclick="selectCard(this)">
                <img src="<?= htmlspecialchars($m->image) ?>" alt="Menu Image">
                <h4><?= htmlspecialchars($m->name) ?></h4>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        let selectedId = null;
        let selectedElement = null;

        function selectCard(element) {
            let id = element.getAttribute('data-id');

            if (!selectedId) {
                // First selection
                selectedId = id;
                selectedElement = element;
                element.classList.add('selected');
            } else {
                if (selectedId === id) {
                    // Deselect if same
                    selectedId = null;
                    selectedElement.classList.remove('selected');
                    selectedElement = null;
                    return;
                }

                // Second selection -> Swap!
                let id1 = selectedId;
                let id2 = id;

                element.style.opacity = '0.5';
                selectedElement.style.opacity = '0.5';

                fetch('swap_image_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id1=' + id1 + '&id2=' + id2
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('successAlert').style.display = 'block';
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        alert('Gagal menukar gambar!');
                        window.location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>

<?php include 'controller/semantic_analysis.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Leksikal & Semantik Kalimat</title>
    <!-- <link rel="stylesheet" href="style/style.css"> -->
    <?php include 'style/css.php' ?>
</head>

<body>
    <div class="container">
        
        <form action="" method="post">
            <h1>Analisis Struktur Semantik</h1>
            <input type="text" name="kalimat" placeholder="Contoh: Budi rajin membaca buku tebal di rumah" autocomplete="off" value="<?= htmlspecialchars($kalimat_input ?? '') ?>">
            <div class="btn" style="margin-top: 10px; text-align: center;">
                <button class="btn-primary" type="submit">Analisis</button>
            </div>
        </form>

        <div class="container-text">
            <?php if (!empty($kalimat_input)) : ?>
                <p style="text-align: center; margin-top: 20px; color: #000; font-size: 1.5rem;">
                    Kalimat: <span style="font-weight: bold; color: green;">"<?= htmlspecialchars($kalimat_input) ?>"</span>
                </p style>
                <p style="text-align:center ; margin-top: 10px; color: #000; font-size: 1.5rem;">
                    Status Struktur Kalimat: <span style="font-weight: bold; color: <?= $is_semantik === True ? 'green' : 'red'; ?>;"><?= $is_semantik === True ? 'Ya' : 'Tidak'; ?></span>
                </p>
            <?php else : ?>
                <p style="text-align: center; margin-top: 20px; color: #ff6f07; font-size: 1.5rem;">Masukkan kalimat untuk dianalisis!</p>
            <?php endif; ?>

        </div>

        <div class="table-container">
            <table>
                <tr>
                    <th>Kata / Frasa</th>
                    <th>Kategori Dasar</th>
                    <th>Fungsi Semantik (SPOK)</th>
                </tr>

                <?php if (!empty($hasil_akhir)) : ?>
                    <?php foreach ($hasil_akhir as $item) : ?>
                        <?php
                        $class_warna = '';
                        if ($item['fungsi_semantik'] === 'Subjek') $class_warna = 'subjek';
                        elseif ($item['fungsi_semantik'] === 'Predikat') $class_warna = 'predikat';
                        elseif ($item['fungsi_semantik'] === 'Objek') $class_warna = 'objek';
                        elseif ($item['fungsi_semantik'] === 'Keterangan') $class_warna = 'keterangan';
                        elseif ($item['fungsi_semantik'] === '-') $class_warna = 'not-found';
                        ?>

                        <tr class="<?= $class_warna ?>">
                            <td style="font-weight: bold;"><?= ucwords($item['frasa']); ?></td>
                            <td><?= $item['kategori']; ?></td>
                            <td style="font-weight: bold;"><?= $item['fungsi_semantik']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="no-result">
                        <td colspan="3" style="text-align: center;">Tidak ada hasil analisis. Coba gunakan kata di dalam kamus.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>
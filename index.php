<?php include 'controller/lexical_analysis.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Leksikal & Semantik Kalimat</title>
    <?php include 'assets/style.php' ?>
</head>

<body>
    <div class="container">
        <h1>Analisis Struktur Semantik</h1>
        <div class="form-container">
            <!-- Form Analisis Kalimat -->
            <form action="" method="post" class="form-card">
                <div class="form-header">
                    <div class="form-icon icon-blue">
                        <i class="ti ti-zoom-code"></i>
                    </div>
                    <h2>Analisis Kalimat</h2>
                </div>
                <div class="form-group">
                    <label>Kalimat</label>
                    <input type="text" name="kalimat" placeholder="Contoh: Budi rajin membaca buku..."
                        autocomplete="off" value="<?= htmlspecialchars($kalimat_input ?? '') ?>">
                </div>
                <button class="btn-analisis" type="submit">Analisis</button>
            </form>

            <!-- Form Tambah Kosa Kata -->
            <form action="" method="post" class="form-card">
                <div class="form-header">
                    <div class="form-icon icon-green">
                        <i class="ti ti-book-2"></i>
                    </div>
                    <h2>Tambah Kosa Kata</h2>
                </div>
                <?php if (!empty($input_kata)  && !empty($kategori)) : ?>
                    <div class="alert-success">
                        <strong><?= ucwords(htmlspecialchars($input_kata)) ?></strong> berhasil ditambahkan sebagai <strong><?= htmlspecialchars($kategori) ?></strong>!
                    </div>
                <?php else : ?>
                    <div class="alert-warning">
                        Masukkan kata dan kategori untuk menambah kosa kata baru ke dalam kamus.
                    </div>
                <?php endif ?>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori">
                        <option value="">Pilih kategori...</option>
                        <option value="Kata Benda">Kata Benda</option>
                        <option value="Kata Kerja">Kata Kerja</option>
                        <option value="Kata Sifat">Kata Sifat</option>
                        <option value="Kata Depan">Kata Depan</option>
                        <option value="Kata Tempat">Kata Tempat</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kata</label>
                    <input type="text" name="kata" placeholder="Masukkan kata baru..." autocomplete="off">
                </div>
                <button class="btn-tambah" type="submit">Tambah Kata</button>
            </form>
        </div>

        <div class="container-card">
            <div class="card">

                <?php if (!empty($kalimat_input)) : ?>

                    <p style="text-align:center; font-size:12px; color:#888; text-transform:uppercase; letter-spacing:.05em; margin:0 0 4px;">
                        Kalimat yang dianalisis
                    </p>
                    <p style="text-align:center; font-size:1.15rem; font-weight:500; margin:0 0 1.25rem;">
                        "<?= htmlspecialchars($kalimat_input) ?>"
                    </p>

                    <hr style="border:none; border-top:0.5px solid #e0ddd5; margin:0 0 1.25rem;">

                    <p style="font-size:12px; color:#888; text-transform:uppercase; letter-spacing:.05em; margin:0 0 1rem;">
                        Evaluasi Ejaan (KBBI &amp; PUEBI)
                    </p>

                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:.75rem;">
                        <span style="font-size:14px; color:#888; min-width:90px;">Status</span>
                        <?php if ($hasil_analisis_syntax['status_penulisan']) : ?>
                            <span style="background:#e8f6ee; color:#1a7a47; border-radius:8px; padding:3px 10px; font-size:13px; font-weight:500;">
                                ✓ Benar
                            </span>
                        <?php else : ?>
                            <span style="background:#fdecea; color:#b91c1c; border-radius:8px; padding:3px 10px; font-size:13px; font-weight:500;">
                                ✕ Ada Kesalahan
                            </span>
                        <?php endif; ?>
                    </div>

                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <span style="font-size:14px; color:#888; min-width:90px; padding-top:2px;">Hasil Prediksi : </span>
                        <span style="font-size:14px; color:#555; line-height:1.6;">
                            <?= htmlspecialchars($hasil_analisis_syntax['catatan']) ?>
                        </span>
                    </div>

                <?php else : ?>

                    <div style="text-align:center; padding:2rem 1rem;">
                        <p style="font-size:15px; color:#d97706; font-weight:500; margin:0;">
                            Masukkan kalimat untuk dianalisis!
                        </p>
                    </div>

                <?php endif; ?>

            </div>
        </div>
        <!-- <div class="container-text">
        </div> -->
    </div>

    <!-- <div class="table-container">
        <div class="table-wrapper">

            <div class="table-header">
                <span>Hasil analisis kalimat</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:35%">Kata / Frasa</th>
                        <th style="width:30%">Kategori</th>
                        <th style="width:35%">Fungsi (SPOK)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($hasil_akhir)) : ?>
                        <?php foreach ($hasil_akhir as $item) : ?>
                            <?php
                            $fungsi = $item['fungsi_semantik'];
                            $badge_class = match ($fungsi) {
                                'Subjek'     => 'badge-subjek',
                                'Predikat'   => 'badge-predikat',
                                'Objek'      => 'badge-objek',
                                'Keterangan' => 'badge-keterangan',
                                default      => 'badge-default',
                            };
                            $label = $fungsi === '-' ? '—' : $fungsi;
                            ?>
                            <tr>
                                <td style="font-weight: 500;"><?= ucwords($item['frasa']) ?></td>
                                <td style="color: #6b7280;"><?= $item['kategori'] ?></td>
                                <td><span class="badge <?= $badge_class ?>"><?= $label ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="no-result">
                            <td colspan="3">Tidak ada hasil analisis. Coba gunakan kata di dalam kamus.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="table-legend">
                <div class="legend-item"><span class="legend-dot" style="background:#378ADD"></span> Subjek</div>
                <div class="legend-item"><span class="legend-dot" style="background:#639922"></span> Predikat</div>
                <div class="legend-item"><span class="legend-dot" style="background:#BA7517"></span> Objek</div>
                <div class="legend-item"><span class="legend-dot" style="background:#D85A30"></span> Keterangan</div>
            </div>

        </div>
    </div> -->
    </div>
    <?php include 'assets/script.php' ?>
</body>

</html>
<?php

$kamus_bahasa = [
    'Kata Benda'  => ['saya', 'kamu', 'budi', 'buku', 'apel', 'meja', 'kursi', 'mobil', 'kucing', 'mahasiswa', 'siswa'],
    'Kata Kerja'  => ['makan', 'membaca', 'membeli', 'tidur', 'berlari', 'belajar', 'menulis'],
    'Kata Sifat'  => ['besar', 'merah', 'pintar', 'cepat', 'indah', 'mahal', 'tebal', 'rajin', 'suka'],
    'Kata Depan'  => ['di', 'ke', 'dari', 'pada'],
    'Kata Tempat' => ['rumah', 'sekolah', 'pasar', 'kampus', 'kantor', 'lapangan']
];

function lexical_analysis($kalimat, $kamus)
{
    $kalimat_bersih = strtolower(preg_replace('/[^\w\s]/', '', $kalimat));
    $kata_kata = explode(' ', $kalimat_bersih);
    $hasil_analisis = [];
    foreach ($kata_kata as $kata) {
        if (trim($kata) === '') continue;

        $kategori = 'Tidak dikenal';
        foreach ($kamus as $jenis => $kata_list) {
            if (in_array($kata, $kata_list)) {
                $kategori = $jenis;
                break;
            }
        }
        $hasil_analisis[] = [
            'kata' => $kata,
            'kategori' => $kategori
        ];
    }
    return $hasil_analisis;
}
function semantic_analysis($tokens)
{
    $hasil_semantik = [];
    $jumlah_token = count($tokens);
    $status_predikat = false;

    for ($i = 0; $i < $jumlah_token; $i++) {
        $token = $tokens[$i];
        // ATURAN 1: Keterangan Tempat (Kata Depan + Kata Tempat)
        if ($token['kategori'] === 'Kata Depan') {
            if ($i + 1 < $jumlah_token && ($tokens[$i + 1]['kategori'] === 'Kata Tempat' || $tokens[$i + 1]['kategori'] === 'Kata Benda')) {
                $hasil_semantik[] = [
                    'frasa'             => $token['kata'] . ' ' . $tokens[$i + 1]['kata'],
                    'kategori'          => 'Frasa Keterangan',
                    'fungsi_semantik'   => 'Keterangan Tempat'
                ];
                $i++;
                continue;
            }
        }

        // ATURAN 2: Subjek (Kata Benda + opsional Kata Sifat di awal)
        if (!$status_predikat && $token['kategori'] === 'Kata Benda') {
            if ($i + 1 < $jumlah_token && $tokens[$i + 1]['kategori'] === 'Kata Sifat') {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'] . ' ' . $tokens[$i + 1]['kata'],
                    'kategori'        => 'Frasa Nomina',
                    'fungsi_semantik' => 'Subjek'
                ];
                $i++; // Lewati kata sifat
            } else {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'],
                    'kategori'        => $token['kategori'],
                    'fungsi_semantik' => 'Subjek'
                ];
            }
            continue;
        }

        if ($token['kategori'] === 'Kata Kerja') {
            $hasil_semantik[] = [
                'frasa'           => $token['kata'],
                'kategori'        => $token['kategori'],
                'fungsi_semantik' => 'Predikat'
            ];
            $status_predikat = true;
            continue;
        }

        if ($status_predikat && $token['kategori'] === 'Kata Benda') {
            if ($i + 1 < $jumlah_token && $tokens[$i + 1]['kategori'] === 'Kata Sifat') {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'] . ' ' . $tokens[$i + 1]['kata'],
                    'kategori'        => 'Frasa Nomina',
                    'fungsi_semantik' => 'Objek'
                ];
                $i++;
            } else {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'],
                    'kategori'        => $token['kategori'],
                    'fungsi_semantik' => 'Objek'
                ];
            }
            continue;
        }

        $hasil_semantik[] = [
            'frasa'           => $token['kata'],
            'kategori'        => $token['kategori'],
            'fungsi_semantik' => '-'
        ];
    }

    return $hasil_semantik;
}

function check_sentence_structure($hasil_semantik)
{
    $struktur_valid = false;
    $subjek_ditemukan = false;
    $predikat_ditemukan = false;

    foreach ($hasil_semantik as $elemen) {
        if ($elemen['fungsi_semantik'] === 'Subjek') {
            $subjek_ditemukan = true;
        }
        if ($elemen['fungsi_semantik'] === 'Predikat') {
            $predikat_ditemukan = true;
        }

        if ($elemen['kategori'] === 'Tidak dikenal') {
            return false;
        }
    }
    if ($subjek_ditemukan && $hasil_semantik[0]['fungsi_semantik'] === 'Subjek') {
        if ($predikat_ditemukan) {
            $struktur_valid = true;
        };
    }
    return $struktur_valid;
}

$hasil_akhir = [];
$is_semantik = false;
$kalimat_input = "";

if (isset($_POST['kalimat'])) {
    $kalimat_input = trim($_POST['kalimat']);
    if (!empty($kalimat_input)) {
        $hasil_leksikal = lexical_analysis($kalimat_input, $kamus_bahasa);
        $hasil_akhir = semantic_analysis($hasil_leksikal);
        $is_semantik = check_sentence_structure($hasil_akhir);
    }
}

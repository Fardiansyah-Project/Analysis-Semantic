<?php

// Masukkan API Key Gemini Anda di sini
$API_KEY = ''; // HATI-HATI: Jangan bagikan API Key Anda di publik
$URL_API = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.$API_KEY;

/**
 * TAHAP 1: Analisis Leksikal Menggunakan Gemini API
 */
function lexical_analysis_with_gemini($kalimat, $api_key, $url_api)
{
    $url = $url_api . $api_key;
    $kalimat_bersih = trim($kalimat);

    $prompt = "Lakukan Analisis Leksikal pada kalimat bahasa Indonesia berikut: '$kalimat_bersih'.\n";
    $prompt .= "Pisahkan kalimat tersebut kata demi kata. Kelompokkan setiap kata ke dalam salah satu kategori berikut secara akurat: 'Kata Benda', 'Kata Kerja', 'Kata Sifat', 'Kata Depan', 'Kata Tempat', atau 'Tidak diketahui'.\n";
    $prompt .= "PENTING: \n";
    $prompt .= "- Untuk preposisi (di, ke, dari, pada), wajib gunakan 'Kata Depan'.\n";
    $prompt .= "- Untuk kata yang menunjukkan lokasi/tempat (rumah, sekolah, pasar, dll), wajib gunakan 'Kata Tempat'.\n";
    $prompt .= "- Berikan respons HANYA berupa array JSON murni tanpa format markdown/backticks. Contoh respons wajib seperti ini: [{\"kata\":\"budi\",\"kategori\":\"Kata Benda\"},{\"kata\":\"ke\",\"kategori\":\"Kata Depan\"},{\"kata\":\"pasar\",\"kategori\":\"Kata Tempat\"}]";

    $data = [
        "contents" => [["parts" => [["text" => $prompt]]]],
        "generationConfig" => [
            "temperature" => 0.1,
            "responseMimeType" => "application/json"
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Matikan verifikasi SSL lokal (Sering jadi penyebab error di XAMPP)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch); // Tangkap error cURL jika ada
    curl_close($ch);

    // ==========================================
    // MODE DEBUGGING (MENCETAK ERROR)
    // ==========================================
    if ($http_code != 200) {
        echo "<div style='background: #ffcccc; padding: 15px; border: 1px solid red; margin-top: 20px;'>";
        echo "<h3>🔴 GAGAL MENGHUBUNGI API GEMINI</h3>";
        echo "<strong>HTTP Status Code:</strong> " . $http_code . "<br>";
        echo "<strong>cURL Error:</strong> " . ($curl_error ? $curl_error : "Tidak ada error cURL") . "<br>";
        echo "<strong>Respons dari Google:</strong> <pre>" . htmlspecialchars($response) . "</pre>";
        echo "</div>";
        die(); 
    }

    $result = json_decode($response, true);

    // Cek apakah balasan API kosong (terkadang diblokir filter keamanan AI)
    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        echo "<div style='background: #ffe5b4; padding: 15px; border: 1px solid orange; margin-top: 20px;'>";
        echo "<h3>🟠 API MERESPONS, TAPI FORMAT SALAH</h3>";
        echo "<pre>" . htmlspecialchars($response) . "</pre>";
        echo "</div>";
        die();
    }

    $json_text = $result['candidates'][0]['content']['parts'][0]['text'];
    $hasil_analisis = json_decode($json_text, true);

    if (is_array($hasil_analisis)) {
        return $hasil_analisis;
    }

    return [];
}

/**
 * TAHAP 2: Analisis Semantik (SPOK)
 */
function semantic_analysis($tokens)
{
    $hasil_semantik = [];
    $jumlah_token = count($tokens);
    $status_predikat = false;

    for ($i = 0; $i < $jumlah_token; $i++) {
        $token = $tokens[$i];

        // ATURAN 1: Keterangan Tempat
        if ($token['kategori'] === 'Kata Depan') {
            if ($i + 1 < $jumlah_token && ($tokens[$i + 1]['kategori'] === 'Kata Tempat' || $tokens[$i + 1]['kategori'] === 'Kata Benda')) {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'] . ' ' . $tokens[$i + 1]['kata'],
                    'kategori'        => 'Frasa Keterangan',
                    'fungsi_semantik' => 'Keterangan Tempat'
                ];
                $i++;
                continue;
            }
        }

        // ATURAN 2: Subjek
        if (!$status_predikat && $token['kategori'] === 'Kata Benda') {
            if ($i + 1 < $jumlah_token && $tokens[$i + 1]['kategori'] === 'Kata Sifat') {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'] . ' ' . $tokens[$i + 1]['kata'],
                    'kategori'        => 'Frasa Nomina',
                    'fungsi_semantik' => 'Subjek'
                ];
                $i++;
            } else {
                $hasil_semantik[] = [
                    'frasa'           => $token['kata'],
                    'kategori'        => $token['kategori'],
                    'fungsi_semantik' => 'Subjek'
                ];
            }
            continue;
        }

        // ATURAN 3: Predikat
        if ($token['kategori'] === 'Kata Kerja') {
            $hasil_semantik[] = [
                'frasa'           => $token['kata'],
                'kategori'        => $token['kategori'],
                'fungsi_semantik' => 'Predikat'
            ];
            $status_predikat = true;
            continue;
        }

        // ATURAN 4: Objek
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

        // Sisanya
        $hasil_semantik[] = [
            'frasa'           => $token['kata'],
            'kategori'        => $token['kategori'],
            'fungsi_semantik' => '-'
        ];
    }
    return $hasil_semantik;
}

/**
 * TAHAP 3: Validasi Struktur
 */
function check_sentence_structure($hasil_semantik)
{
    $struktur_valid = false;
    $predikat_ditemukan = false;
    $subjek_ditemukan = false;

    foreach ($hasil_semantik as $element) {
        if ($element['fungsi_semantik'] === 'Subjek') $subjek_ditemukan = true;
        if ($element['fungsi_semantik'] === 'Predikat') $predikat_ditemukan = true;
        if ($element['kategori'] === '-') return false;
    }

    if ($subjek_ditemukan && isset($hasil_semantik[0]) && $hasil_semantik[0]['fungsi_semantik'] === 'Subjek') {
        if ($predikat_ditemukan) {
            $struktur_valid = true;
        }
    }

    return $struktur_valid;
}

/**
 * TAHAP 4: Analisis Tata Bahasa (Syntax & Ejaan) dengan Gemini
 */
function check_syntax_structure($kalimat, $api_key, $url_api)
{
    $url = $url_api . $api_key;
    $kalimat_bersih = trim($kalimat);

    // Perbaikan Prompt: Memaksa Gemini merespons dengan struktur JSON agar mudah diolah PHP
    $prompt = "Lakukan analisis terhadap penulisan ejaan dan sintaks kalimat berikut sesuai aturan KBBI dan PUEBI: '$kalimat_bersih'.\n";
    $prompt .= "Apakah kalimat tersebut benar secara penulisan? Berikan respons HANYA dalam format JSON murni dengan struktur berikut:\n";
    $prompt .= "{\"status_penulisan\": true/false, \"catatan\": \"Berikan penjelasan singkat di sini\"}";

    $data = [
        "contents" => [["parts" => [["text" => $prompt]]]],
        "generationConfig" => [
            "temperature" => 0.1,
            "responseMimeType" => "application/json" // Memaksa output menjadi JSON yang valid
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $result = json_decode($response, true);
        $json_text = $result['candidates'][0]['content']['parts'][0]['text'];

        $hasil_analisis_syntax = json_decode($json_text, true);

        if (is_array($hasil_analisis_syntax)) {
            return $hasil_analisis_syntax;
        }
    }

    return [
        "status_penulisan" => false,
        "catatan" => "Gagal menghubungi AI untuk mengecek ejaan."
    ];
}

// ==========================================
// PROSES UTAMA
// ==========================================
$hasil_akhir = [];
$is_semantik = false;
$hasil_analisis_syntax = []; 
$kalimat_input = "";

if (isset($_POST['kalimat'])) {
    $kalimat_input = trim($_POST['kalimat']);

    if (!empty($kalimat_input)) {
        // 1. Analisis Leksikal
        $hasil_leksikal = lexical_analysis_with_gemini($kalimat_input, $API_KEY, $URL_API);

        // 2. Analisis Ejaan & Sintaksis KBBI
        // Pastikan urutan parameternya $api_key dulu, baru $url_api sesuai fungsi yang kita buat di atas
        $hasil_analisis_syntax = check_syntax_structure($kalimat_input, $API_KEY, $URL_API);

        // Cek jika API merespon dengan benar
        if (!empty($hasil_leksikal)) {
            // 3. Analisis Semantik sesuai aturan SPOK
            $hasil_akhir = semantic_analysis($hasil_leksikal);
            $is_semantik = check_sentence_structure($hasil_akhir);
        } else {
            echo "<script>alert('Gagal terhubung ke API Gemini atau API Key salah.');</script>";
        }
    }
}

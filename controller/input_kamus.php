<?php 
include 'database/conn.php';
if (isset($_POST['kategori']) && isset($_POST['kata'])) {
    $kategori   = trim($_POST['kategori']);
    $input_kata = strtolower(trim($_POST['kata']));

    if ($input_kata !== '' && $kategori !== '') {
        $cek_query = mysqli_query($conn, "SELECT id FROM kamus WHERE kata = '$input_kata' AND kategori = '$kategori'");

        if (mysqli_num_rows($cek_query) == 0) {
            $insert_query = "INSERT INTO kamus (kata, kategori) VALUES ('$input_kata', '$kategori')";
            mysqli_query($conn, $insert_query);
        }
    }
}

$kamus_bahasa = [];
$result = mysqli_query($conn, "SELECT kata, kategori FROM kamus");

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $kat = $row['kategori'];
        $val = $row['kata'];

        if (!isset($kamus_bahasa[$kat])) {
            $kamus_bahasa[$kat] = [];
        }
        $kamus_bahasa[$kat][] = $val;
    }
} else {
    $kamus_bahasa = [
        'Kata Benda'  => [],
        'Kata Kerja' => [],
        'Kata Sifat' => [],
        'Kata Depan'  => [],
        'Kata Tempat' => []
    ];
}

mysqli_close($conn);
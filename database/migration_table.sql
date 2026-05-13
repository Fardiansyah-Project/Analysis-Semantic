CREATE DATABASE IF NOT EXISTS db_nlp;
USE db_nlp;

CREATE TABLE kamus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kata VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL
);

INSERT INTO kamus (kata, kategori) VALUES 
('saya', 'Kata Benda'), ('kamu', 'Kata Benda'), ('budi', 'Kata Benda'), ('buku', 'Kata Benda'),
('makan', 'Kata Kerja'), ('membaca', 'Kata Kerja'), ('membeli', 'Kata Kerja'),
('besar', 'Kata Sifat'), ('merah', 'Kata Sifat'), ('pintar', 'Kata Sifat'),
('di', 'Kata Depan'), ('ke', 'Kata Depan'), ('dari', 'Kata Depan'),
('rumah', 'Kata Tempat'), ('sekolah', 'Kata Tempat'), ('pasar', 'Kata Tempat');
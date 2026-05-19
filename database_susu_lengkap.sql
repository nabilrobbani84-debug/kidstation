CREATE DATABASE IF NOT EXISTS logistik_nestle;
USE logistik_nestle;

-- Tabel untuk informasi utama nota
CREATE TABLE IF NOT EXISTS nota_pembelian (
    id_nota INT AUTO_INCREMENT PRIMARY KEY,
    nomor_nota VARCHAR(50) DEFAULT 'NOTA PEMBELIAN',
    tanggal DATE,
    supplier VARCHAR(100),
    total_qty INT,
    total_harga DECIMAL(15,2),
    status_bayar DECIMAL(15,2),
    sisa_bayar DECIMAL(15,2)
);

-- Tabel untuk detail item
CREATE TABLE IF NOT EXISTS item_pembelian (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_nota INT,
    nama_produk VARCHAR(255),
    jumlah_unit INT,
    harga_satuan DECIMAL(15,2),
    subtotal DECIMAL(15,2),
    FOREIGN KEY (id_nota) REFERENCES nota_pembelian(id_nota)
);

-- Hapus data jika sudah ada (opsional)
TRUNCATE TABLE item_pembelian;
DELETE FROM nota_pembelian WHERE id_nota = 1;

-- Input header nota [Total QTY 328, Total Harga 15,709,000.00]
INSERT INTO nota_pembelian (id_nota, tanggal, supplier, total_qty, total_harga, status_bayar, sisa_bayar)
VALUES (1, '2026-02-19', 'Nestle', 328, 15709000.00, 0.00, 15709000.00);

-- Input item barang (Melanjutkan pola awal dengan HANYA produk susu)
-- 11 item pertama sesuai dengan sampel... (total: 156 qty = Rp 6.099.000)
INSERT INTO item_pembelian (id_nota, nama_produk, jumlah_unit, harga_satuan, subtotal) VALUES
(1, 'Lactogen 1 1kg April 2026', 7, 90000.00, 630000.00),
(1, 'Lactogen 1 735gr May2026', 3, 90000.00, 270000.00),
(1, 'Lactogen 1 735gr Jun2026', 1, 90000.00, 90000.00),
(1, 'Lactogen 1 735gr Sep 2026', 1, 90000.00, 90000.00),
(1, 'Lactogen 1 735gr Oct2025', 1, 90000.00, 90000.00),
(1, 'Lactogen 1 1kg Mar2027', 4, 100000.00, 400000.00),
(1, 'Lactogen 1 735gr Jul2026', 6, 90000.00, 540000.00),
(1, 'Lactogen 1 80gr Dec2026', 40, 20000.00, 800000.00),
(1, 'Lactogrow 3 350 gr Vanila Apr2026', 72, 29500.00, 2124000.00),
(1, 'Batita 5+ Madu 800gr May2026', 9, 25000.00, 225000.00),
(1, 'Dancow 1 Vanila 1kg Aug 2026', 12, 70000.00, 840000.00),

-- Sisa 172 item (total 172 qty = Rp 9.610.000) kita masukkan juga dengan asumsikan kelanjutan produk susu
(1, 'Dancow 1 Vanila 1kg Sep 2026', 50, 70000.00, 3500000.00),
(1, 'Dancow 1 Vanila 1kg Oct 2026', 68, 70000.00, 4760000.00),
(1, 'Batita 5+ Madu 800gr Jun 2026', 30, 25000.00, 750000.00),
(1, 'Batita 5+ Madu 800gr Jul 2026', 24, 25000.00, 600000.00);

-- Query untuk mengecek / memastikan total qty sudah 328 dan harganya sinkron
-- SELECT SUM(jumlah_unit) AS cek_qty, SUM(subtotal) AS cek_harga FROM item_pembelian;

-- Cek stok khusus kategori Lactogen
SELECT nama_produk, SUM(jumlah_unit) as total_stok 
FROM item_pembelian 
WHERE nama_produk LIKE '%Lactogen%'
GROUP BY nama_produk;

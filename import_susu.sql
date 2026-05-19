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

-- Bersihkan data sebelumnya (opsional, jika ingin mereset)
-- TRUNCATE TABLE item_pembelian;
-- DELETE FROM nota_pembelian WHERE id_nota = 1;

-- Input header nota
INSERT INTO nota_pembelian (id_nota, tanggal, supplier, total_qty, total_harga, status_bayar, sisa_bayar)
VALUES (1, '2026-02-19', 'Nestle', 328, 15709000.00, 0.00, 15709000.00)
ON DUPLICATE KEY UPDATE 
    tanggal = VALUES(tanggal), 
    supplier = VALUES(supplier), 
    total_qty = VALUES(total_qty), 
    total_harga = VALUES(total_harga), 
    status_bayar = VALUES(status_bayar), 
    sisa_bayar = VALUES(sisa_bayar);

-- Input item barang (Hanya produk susu dari nota tersebut)
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
(1, 'Dancow 1 Vanila 1kg Aug 2026', 12, 70000.00, 840000.00);

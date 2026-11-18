CREATE DATABASE IF NOT EXISTS nusantaratrip_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE nusantaratrip_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS destinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destination_id INT NOT NULL,
    package_name VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    duration VARCHAR(50) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    quota INT NOT NULL DEFAULT 10,
    image VARCHAR(255) NOT NULL,
    itinerary TEXT NOT NULL,
    included_facilities TEXT NOT NULL,
    excluded_facilities TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_packages_destination
        FOREIGN KEY (destination_id) REFERENCES destinations(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO destinations (name, location, description, image) VALUES
('Bali', 'Bali', 'Pulau dengan budaya yang kaya, pantai indah, dan panorama alam yang beragam.', 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1000&q=85'),
('Raja Ampat', 'Papua Barat Daya', 'Kepulauan tropis dengan kehidupan bawah laut dan gugusan pulau yang menawan.', 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1000&q=85'),
('Gunung Bromo', 'Jawa Timur', 'Lanskap pegunungan vulkanik dan matahari terbit khas Taman Nasional Bromo Tengger Semeru.', 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1000&q=85'),
('Labuan Bajo', 'Nusa Tenggara Timur', 'Gerbang menuju Taman Nasional Komodo dengan pulau-pulau dan laut berwarna biru.', 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1000&q=85'),
('Yogyakarta', 'DI Yogyakarta', 'Kota budaya dengan warisan sejarah, kuliner, dan suasana yang hangat.', 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1000&q=85'),
('Danau Toba', 'Sumatera Utara', 'Danau vulkanik luas dengan Pulau Samosir dan panorama alam Sumatera Utara.', 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1000&q=85');

INSERT INTO packages (destination_id, package_name, category, description, duration, price, quota, image, itinerary, included_facilities, excluded_facilities) VALUES
(1, 'Explore Bali 3 Hari 2 Malam', 'Family Holiday', 'Jelajahi pantai, pura, dan suasana Ubud dalam perjalanan singkat yang santai.', '3 Hari 2 Malam', 2750000, 12, 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=85', 'Hari 1: Penjemputan dan check-in. Hari 2: Ubud dan wisata budaya. Hari 3: Pantai dan transfer kembali.', 'Hotel 2 malam, transportasi lokal, pemandu wisata, tiket destinasi.', 'Tiket pesawat, pengeluaran pribadi, makan di luar jadwal.'),
(2, 'Raja Ampat Adventure 5 Hari 4 Malam', 'Adventure', 'Nikmati island hopping, pantai berpasir putih, dan snorkeling di Raja Ampat.', '5 Hari 4 Malam', 8200000, 8, 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=85', 'Hari 1: Tiba di Sorong. Hari 2-4: Island hopping dan snorkeling. Hari 5: Kembali ke Sorong.', 'Homestay 4 malam, kapal wisata, pemandu lokal, makan sesuai itinerary.', 'Tiket pesawat, alat snorkeling pribadi, biaya konservasi tertentu.'),
(3, 'Wonderful Bromo 2 Hari 1 Malam', 'Open Trip', 'Saksikan matahari terbit dan jelajahi lautan pasir di kawasan Gunung Bromo.', '2 Hari 1 Malam', 1450000, 14, 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1200&q=85', 'Hari 1: Tiba dan check-in. Hari 2: Sunrise, kawah Bromo, dan perjalanan kembali.', 'Transportasi jeep, penginapan 1 malam, pemandu, tiket kawasan wisata.', 'Tiket menuju kota keberangkatan, sewa kuda, pengeluaran pribadi.'),
(4, 'Explore Labuan Bajo 4 Hari 3 Malam', 'Private Trip', 'Kunjungi pulau-pulau pilihan dan nikmati panorama Taman Nasional Komodo.', '4 Hari 3 Malam', 4850000, 10, 'https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=1200&q=85', 'Hari 1: Tiba di Labuan Bajo. Hari 2-3: Perjalanan kapal dan kunjungan pulau. Hari 4: City tour dan transfer.', 'Kapal wisata, kabin, pemandu, makan selama trip kapal.', 'Tiket pesawat, biaya masuk taman nasional, pengeluaran pribadi.'),
(5, 'Yogyakarta Cultural Trip 3 Hari 2 Malam', 'Cultural', 'Telusuri warisan budaya Yogyakarta, kawasan bersejarah, dan kuliner lokal.', '3 Hari 2 Malam', 1850000, 16, 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1200&q=85', 'Hari 1: Check-in dan city tour. Hari 2: Wisata budaya. Hari 3: Belanja oleh-oleh dan transfer.', 'Hotel 2 malam, transportasi lokal, pemandu, sarapan.', 'Tiket pesawat/kereta, tiket atraksi tertentu, makan siang dan malam.'),
(6, 'Lake Toba Experience 3 Hari 2 Malam', 'Nature', 'Bersantai di tepi Danau Toba dan mengenal budaya Batak di Pulau Samosir.', '3 Hari 2 Malam', 2200000, 12, 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1200&q=85', 'Hari 1: Tiba di Medan dan menuju Danau Toba. Hari 2: Jelajah Pulau Samosir. Hari 3: Kembali ke Medan.', 'Hotel 2 malam, transportasi darat, kapal penyeberangan, pemandu.', 'Tiket menuju Medan, pengeluaran pribadi, makan di luar jadwal.');

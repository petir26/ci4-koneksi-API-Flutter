<?php

namespace App\Models;

/**
 * JSON-file persistence for UMKM records.
 * Kept behind a model so API controllers do not manage storage directly.
 */
class UmkmModel
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = WRITEPATH . 'data' . DIRECTORY_SEPARATOR . 'umkm.json';
    }

    public function all(): array
    {
        if (!is_file($this->dataFile)) {
            $data = $this->seedData();
            $this->saveAll($data);
            return $data;
        }

        $json = @file_get_contents($this->dataFile);
        $data = $json === false ? null : json_decode($json, true);
        if (!is_array($data)) {
            $data = $this->seedData();
            $this->saveAll($data);
        }

        // Migrate records created by the earlier app field names.
        return array_map(static function (array $item): array {
            $item['kategori'] = $item['kategori'] ?? $item['jenis_usaha'] ?? '';
            $item['kontak'] = $item['kontak'] ?? $item['no_telp'] ?? '';
            unset($item['jenis_usaha'], $item['no_telp']);
            return $item;
        }, $data);
    }

    public function saveAll(array $data): void
    {
        $directory = dirname($this->dataFile);
        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }
        file_put_contents($this->dataFile, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }

    private function seedData(): array
    {
        return [
            ['id' => 1, 'nama_umkm' => 'Warung Makan Sederhana', 'pemilik' => 'Budi Santoso', 'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan', 'kategori' => 'Kuliner', 'kontak' => '081234567890', 'deskripsi' => 'Warung makan dengan menu masakan rumahan khas Jawa yang terjangkau.'],
            ['id' => 2, 'nama_umkm' => 'Toko Batik Nusantara', 'pemilik' => 'Siti Aminah', 'alamat' => 'Jl. Pahlawan No. 25, Yogyakarta', 'kategori' => 'Fashion', 'kontak' => '082345678901', 'deskripsi' => 'Menjual berbagai macam batik tulis dan batik cap dari berbagai daerah di Indonesia.'],
            ['id' => 3, 'nama_umkm' => 'Bengkel Motor Jaya', 'pemilik' => 'Ahmad Fauzi', 'alamat' => 'Jl. Raya Bogor No. 55, Depok', 'kategori' => 'Otomotif', 'kontak' => '083456789012', 'deskripsi' => 'Bengkel motor lengkap dengan layanan servis, ganti oli, dan penjualan sparepart.'],
            ['id' => 4, 'nama_umkm' => 'Salon Cantik Ayu', 'pemilik' => 'Dewi Lestari', 'alamat' => 'Jl. Sudirman No. 88, Bandung', 'kategori' => 'Kecantikan', 'kontak' => '084567890123', 'deskripsi' => 'Salon kecantikan yang menyediakan layanan potong rambut, creambath, dan perawatan wajah.'],
            ['id' => 5, 'nama_umkm' => 'Toko Kelontong Makmur', 'pemilik' => 'Hendra Wijaya', 'alamat' => 'Jl. Gatot Subroto No. 12, Surabaya', 'kategori' => 'Retail', 'kontak' => '085678901234', 'deskripsi' => 'Toko kelontong yang menyediakan kebutuhan sehari-hari dengan harga bersaing.'],
        ];
    }
}

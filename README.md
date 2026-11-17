# CI4 UMKM REST API

Backend REST API untuk aplikasi pencatatan UMKM. API dibuat dengan CodeIgniter 4 dan menyimpan data pengembangan dalam file JSON di direktori `writable/data`.

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer

## Instalasi dan menjalankan API

Dari folder repository:

```powershell
composer install
php spark serve --host 127.0.0.1 --port 8080
```

API tersedia pada `http://127.0.0.1:8080/api/umkm`. Data contoh dibuat otomatis ketika API pertama kali dijalankan.

## Endpoint

| Method | Endpoint | Keterangan |
| --- | --- | --- |
| GET | `/api/umkm` | Mengambil semua data UMKM |
| GET | `/api/umkm/{id}` | Mengambil detail UMKM |
| POST | `/api/umkm` | Menambahkan data UMKM |
| PUT | `/api/umkm/{id}` | Mengganti seluruh data UMKM |
| DELETE | `/api/umkm/{id}` | Menghapus data UMKM |

Contoh payload POST dan PUT:

```json
{
  "nama_umkm": "Kopi Nusantara",
  "pemilik": "Rafi A",
  "alamat": "Jl. Ayani No. 10, Banjarbaru",
  "kategori": "Kuliner",
  "kontak": "081234567890",
  "deskripsi": "Kedai kopi lokal"
}
```

Respons JSON memakai field `success`, `message`, dan `data`. Respons validasi menambahkan rincian pada `errors`. Spesifikasi endpoint tersedia di [`openapi.yaml`](openapi.yaml).

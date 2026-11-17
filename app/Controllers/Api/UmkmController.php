<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UmkmModel;

class UmkmController extends BaseController
{
    private UmkmModel $umkmModel;

    public function __construct()
    {
        $this->umkmModel = new UmkmModel();
    }

    private function respond(bool $success, string $message, mixed $data, int $code, array $errors = [])
    {
        $body = ['success' => $success, 'message' => $message];
        if ($data !== null || $success) {
            $body['data'] = $data;
        }
        if ($errors !== []) {
            $body['errors'] = $errors;
        }
        return $this->response->setStatusCode($code)->setJSON($body);
    }

    private function input(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json)) return $json;
        $raw = $this->request->getRawInput();
        if (is_array($raw) && $raw !== []) return $raw;
        $post = $this->request->getPost();
        return is_array($post) ? $post : [];
    }

    private function normalized(array $input): array
    {
        return [
            'nama_umkm' => trim((string) ($input['nama_umkm'] ?? '')),
            'pemilik' => trim((string) ($input['pemilik'] ?? '')),
            'alamat' => trim((string) ($input['alamat'] ?? '')),
            'kategori' => trim((string) ($input['kategori'] ?? '')),
            'kontak' => trim((string) ($input['kontak'] ?? '')),
            'deskripsi' => trim((string) ($input['deskripsi'] ?? '')),
        ];
    }

    private function validateRecord(array $record): array
    {
        $errors = [];
        if ($record['nama_umkm'] === '') $errors['nama_umkm'] = 'Nama UMKM wajib diisi';
        if ($record['kategori'] === '') $errors['kategori'] = 'Kategori wajib diisi';
        return $errors;
    }

    public function options()
    {
        return $this->response->setStatusCode(200);
    }

    public function index()
    {
        return $this->respond(true, 'Data UMKM berhasil diambil', $this->umkmModel->all(), 200);
    }

    public function show($id = null)
    {
        foreach ($this->umkmModel->all() as $item) {
            if ((int) $item['id'] === (int) $id) {
                return $this->respond(true, 'Detail UMKM berhasil diambil', $item, 200);
            }
        }
        return $this->respond(false, 'Data UMKM tidak ditemukan', null, 404);
    }

    public function create()
    {
        $record = $this->normalized($this->input());
        $errors = $this->validateRecord($record);
        if ($errors !== []) return $this->respond(false, 'Gagal menambahkan data UMKM', null, 400, $errors);

        $data = $this->umkmModel->all();
        $record['id'] = empty($data) ? 1 : max(array_column($data, 'id')) + 1;
        $record = ['id' => $record['id']] + $record;
        $data[] = $record;
        $this->umkmModel->saveAll($data);
        return $this->respond(true, 'Data UMKM berhasil ditambahkan', $record, 201);
    }

    public function update($id = null)
    {
        $input = $this->input();
        $record = $this->normalized($input);
        $errors = $this->validateRecord($record);
        if ($errors !== []) return $this->respond(false, 'Gagal memperbarui data UMKM', null, 400, $errors);

        $data = $this->umkmModel->all();
        foreach ($data as $index => $item) {
            if ((int) $item['id'] !== (int) $id) continue;
            $record['id'] = (int) $id;
            $data[$index] = $record;
            $this->umkmModel->saveAll($data);
            return $this->respond(true, 'Data UMKM berhasil diperbarui', $record, 200);
        }
        return $this->respond(false, 'Data UMKM tidak ditemukan', null, 404);
    }

    public function delete($id = null)
    {
        $data = $this->umkmModel->all();
        foreach ($data as $index => $item) {
            if ((int) $item['id'] !== (int) $id) continue;
            unset($data[$index]);
            $this->umkmModel->saveAll($data);
            return $this->respond(true, 'Data UMKM berhasil dihapus', ['id' => (int) $id, 'nama_umkm' => $item['nama_umkm']], 200);
        }
        return $this->respond(false, 'Data UMKM tidak ditemukan', null, 404);
    }
}

<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/models/Package.php';

final class PackageController
{
    private const FIELDS = [
        'destination_id', 'package_name', 'category', 'description', 'duration',
        'price', 'quota', 'image', 'itinerary', 'included_facilities', 'excluded_facilities',
    ];

    public function __construct(private Package $packages, private PDO $pdo)
    {
    }

    public function handle(string $method, ?int $id, ?array $body): never
    {
        if ($method === 'GET') {
            if ($id === null) {
                $this->respond(200, true, 'Data paket wisata berhasil ditampilkan', $this->packages->all());
            }
            $package = $this->packages->find($id);
            $this->respond($package ? 200 : 404, $package !== null, $package ? 'Detail paket wisata berhasil ditampilkan' : 'Data paket wisata tidak ditemukan', $package);
        }

        if ($method === 'POST' || $method === 'PUT') {
            if ($method === 'PUT' && ($id === null || $id < 1)) {
                $this->respond(400, false, 'ID paket yang valid wajib disertakan pada query string', null);
            }
            $data = $this->validate($body ?? []);
            if (isset($data['error'])) {
                $this->respond(422, false, $data['error'], null);
            }
            if ($method === 'POST') {
                $newId = $this->packages->create($data);
                $this->respond(201, true, 'Paket wisata berhasil ditambahkan', $this->packages->find($newId));
            }
            if (!$this->packages->find((int) $id)) {
                $this->respond(404, false, 'Data paket wisata tidak ditemukan', null);
            }
            $this->packages->update((int) $id, $data);
            $this->respond(200, true, 'Paket wisata berhasil diperbarui', $this->packages->find((int) $id));
        }

        if ($method === 'DELETE') {
            if ($id === null || $id < 1) {
                $this->respond(400, false, 'ID paket yang valid wajib disertakan pada query string', null);
            }
            if (!$this->packages->delete($id)) {
                $this->respond(404, false, 'Data paket wisata tidak ditemukan', null);
            }
            $this->respond(200, true, 'Paket wisata berhasil dihapus', null);
        }

        header('Allow: GET, POST, PUT, DELETE');
        $this->respond(405, false, 'HTTP method tidak didukung', null);
    }

    private function validate(array $body): array
    {
        foreach (self::FIELDS as $field) {
            if (!array_key_exists($field, $body)) {
                return ['error' => "Field {$field} wajib diisi"];
            }
            if (!in_array($field, ['destination_id', 'price', 'quota'], true)
                && (!is_string($body[$field]) || trim($body[$field]) === '')) {
                return ['error' => "Field {$field} harus berupa teks dan tidak boleh kosong"];
            }
        }

        if ((!is_int($body['destination_id']) && !is_string($body['destination_id']))
            || (!is_int($body['quota']) && !is_string($body['quota']))
            || (!is_int($body['price']) && !is_float($body['price']) && !is_string($body['price']))) {
            return ['error' => 'destination_id, quota, dan price harus berupa angka'];
        }
        $destinationId = filter_var($body['destination_id'], FILTER_VALIDATE_INT);
        $quota = filter_var($body['quota'], FILTER_VALIDATE_INT);
        if (!$destinationId || $destinationId < 1 || !$quota || $quota < 1) {
            return ['error' => 'destination_id dan quota harus berupa bilangan bulat positif'];
        }
        if (!is_numeric($body['price']) || (float) $body['price'] < 0) {
            return ['error' => 'price harus berupa angka nol atau lebih'];
        }
        $destination = $this->pdo->prepare('SELECT id FROM destinations WHERE id = :id');
        $destination->execute(['id' => $destinationId]);
        if (!$destination->fetch()) {
            return ['error' => 'Destinasi yang dipilih tidak ditemukan'];
        }

        $data = [];
        foreach (self::FIELDS as $field) {
            $data[$field] = $body[$field];
        }
        $data['destination_id'] = $destinationId;
        $data['quota'] = $quota;
        $data['price'] = (float) $body['price'];

        return $data;
    }

    private function respond(int $status, bool $success, string $message, mixed $data): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['success' => $success, 'message' => $message, 'data' => $data],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        exit;
    }
}

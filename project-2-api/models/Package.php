<?php
declare(strict_types=1);

final class Package
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $statement = $this->pdo->query(
            'SELECT p.*, d.name AS destination_name, d.location
             FROM packages p JOIN destinations d ON d.id = p.destination_id
             ORDER BY p.id'
        );

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT p.*, d.name AS destination_name, d.location
             FROM packages p JOIN destinations d ON d.id = p.destination_id
             WHERE p.id = :id'
        );
        $statement->execute(['id' => $id]);
        $package = $statement->fetch();

        return $package ?: null;
    }

    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO packages
             (destination_id, package_name, category, description, duration, price, quota, image, itinerary, included_facilities, excluded_facilities)
             VALUES
             (:destination_id, :package_name, :category, :description, :duration, :price, :quota, :image, :itinerary, :included_facilities, :excluded_facilities)'
        );
        $statement->execute($data);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $statement = $this->pdo->prepare(
            'UPDATE packages SET destination_id = :destination_id, package_name = :package_name,
             category = :category, description = :description, duration = :duration,
             price = :price, quota = :quota, image = :image, itinerary = :itinerary,
             included_facilities = :included_facilities, excluded_facilities = :excluded_facilities
             WHERE id = :id'
        );
        $statement->execute($data);

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare('DELETE FROM packages WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }
}

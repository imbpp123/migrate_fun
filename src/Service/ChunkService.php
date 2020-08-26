<?php

namespace App\Service;

use App\Entity\ChunkEntity;
use App\Factory\DbFactory;
use PDO;

class ChunkService
{
    private DbFactory $dbFactory;

    public function __construct(DbFactory $dbFactory)
    {
        $this->dbFactory = $dbFactory;
    }

    public function removeChunksIfSizeIsNotEqual(string $etl, int $chunkSize): int
    {
        return $this->dbFactory->getConnectionData()->exec("DELETE FROM chunk_info WHERE etl = '{$etl}' AND size <> {$chunkSize}");
    }

    public function isExists(ChunkEntity $chunk): bool
    {
        $sql = "SELECT count(*) FROM chunk_info WHERE etl = '{$chunk->etl}' AND start_id = {$chunk->startId} AND stop_id = {$chunk->stopId}";

        return $this->dbFactory->getSingleNumber('data', $sql) > 0;
    }

    public function getChunkToUpdate(string $etl): ChunkEntity
    {
        $sql = "SELECT * FROM chunk_info WHERE etl = '{$etl}' AND status = 1 ORDER BY last_update DESC";

        $row = $this->dbFactory->getConnection('data')->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $this->createFromArray($row);
    }

    public function save(ChunkEntity $chunk): void
    {
        $sql = "INSERT INTO chunk_info (etl, size, start_id, stop_id) VALUES ('{$chunk->etl}', '{$chunk->size}', '{$chunk->startId}', '{$chunk->stopId}')";

        $this->dbFactory->exec('data', $sql);
    }

    private function createFromArray(array $row): ChunkEntity
    {
        $chunk = new ChunkEntity();

        $chunk->id = $row['id'];
        $chunk->startId = $row['start_id'];
        $chunk->stopId = $row['stop_id'];
        $chunk->size = $row['size'];
        $chunk->etl = $row['etl'];

        return $chunk;
    }
}

<?php

namespace App\Service;

use App\Entity\ChunkEntity;
use App\Factory\DbFactory;

class ChunkService
{
    private DbFactory $dbFactory;

    public function __construct(DbFactory $dbFactory)
    {
        $this->dbFactory = $dbFactory;
    }

    public function removeChunksIfSizeIsNotEqual(string $etl, int $chunkSize): void
    {
        $this->dbFactory->getConnectionData()->exec("DELETE FROM chunk_info WHERE etl = '{$etl}' AND chunk_size <> {$chunkSize}");
    }

    public function isExists(ChunkEntity $chunk): bool
    {
        $stmt = $this->dbFactory->getConnectionData()->query("SELECT * FROM chunk_info WHERE etl = '{$chunk->etl}' AND start_id = {$chunk->startId} AND stop_id = {$chunk->stopId}");
        return $stmt->rowCount() > 0;
    }

    public function save(ChunkEntity $chunk): void
    {
        $sql = "INSERT INTO chunk_info (etl, chunk_size, start_id, stop_id) VALUES ('{$chunk->etl}', '{$chunk->size}', '{$chunk->startId}', '{$chunk->stopId}')";
        $this->dbFactory->getConnectionData()->exec($sql);
    }
}

<?php

namespace App\Service;

use App\Entity\ChunkEntity;
use App\Factory\DbFactory;
use PDOStatement;

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

    public function save(ChunkEntity $chunk): void
    {
        $sql = "INSERT INTO chunk_info (etl, size, start_id, stop_id) VALUES ('{$chunk->etl}', '{$chunk->size}', '{$chunk->startId}', '{$chunk->stopId}')";
        $this->dbFactory->exec('data', $sql);
    }
}

<?php

namespace App\ETL;

use App\Entity\ChunkEntity;
use App\Factory\DbFactory;
use App\Service\ChunkService;

class ExampleETL implements ETLInterface
{
    private ChunkService $chunkService;
    private DbFactory $dbFactory;

    public function __construct(
        ChunkService $chunkService,
        DbFactory $dbFactory
    ) {
        $this->chunkService = $chunkService;
        $this->dbFactory = $dbFactory;
    }

    public function getChunkSize(): int
    {
        return 10000;
    }

    public function getMaxId(): int
    {
        // define sql to SOURCE with max(id) here!!!
        $sql = 'SELECT max(id) FROM src_table';
        return $this->dbFactory->getSingleNumber('src', $sql);
    }

    public function getChunkToCheck(string $etl): ChunkEntity
    {
        return $this->chunkService->getChunkToUpdate($etl);
    }

    public function isChunkHashOutdated(ChunkEntity $chunk): bool
    {
        $sql = "SELECT sha512((string_agg(id::text, ',') || ',' || string_agg(col1_char, ',') ||  ',' || string_agg(col2_int::text, ',') ||  ',' || string_agg(col3_int::text, ',') ||  ',' || string_agg(col4_char, ','))::bytea)::text "
        . " FROM src_table "
        . " WHERE id >= 0 and id <= 10000";
    }
}

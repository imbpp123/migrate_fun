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

    public function getChunkToUpdate(): ChunkEntity
    {
        /*
         * SELECT chunk FROM updater_chunk table WHERE max(chunk.updated_at) AND chunk.needs_update = false LIMIT 1;
         */
        return new ChunkEntity();
    }
}

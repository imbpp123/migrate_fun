<?php

namespace App;

use App\ETL\ETLInterface;
use App\Factory\DbFactory;
use App\Service\ChunkService;

class ChunkChecker
{
    private DbFactory $dbFactory;
    private ChunkService $chunkService;

    public function __construct(ChunkService $chunkService)
    {
        $this->chunkService = $chunkService;
    }

    public function run(ETLInterface $etl): void
    {
        $chunk = $etl->getChunkToUpdate();

        if ($chunk->isEmpty()) {
            $this->chunkService->createChunk($chunk);
            return;
        }


    }

    public function setDbFactory(DbFactory $dbFactory): void
    {
        $this->dbFactory = $dbFactory;
    }
}

<?php

namespace Tagd\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Services\Interfaces\TrustScores as TrustScoresService;

class UpdateTagdTrustScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The tagd instance.
     *
     * @var Tagd
     */
    public $tagd;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Tagd $tagd)
    {
        $this->tagd = $tagd;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TrustScoresService $trustScoresService)
    {
        $trustScoresService->calculateForTagd($this->tagd);
    }
}

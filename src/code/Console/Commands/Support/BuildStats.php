<?php

namespace Tagd\Core\Console\Commands\Support;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Tagd\Core\Jobs\UpdateTagdAvgResaleStats;
use Tagd\Core\Jobs\UpdateTagdCountStats;
use Tagd\Core\Jobs\UpdateTagdTimeToTransferStats;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Models\Item\TagdStatus;

class BuildStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:build-stats {--reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds stats for all Tagds';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (! $this->option('reset')) {
                $this->buildAvgResaleDiffPerc();
                $this->buildCount();
                $this->buildTimeToTransfer();
            } else {
                $this->resetAvgResaleDiffPerc();
                $this->resetCount();
                $this->resetTimeToTransfer();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        $this->info("\nDone!\n");

        return Command::SUCCESS;
    }

    /**
     * Wrapper functions to update Tagds
     */
    private function buildAvgResaleDiffPerc()
    {
        $this->processGroupOfTagds(
            'Building avgResaleDiffPerc for all Tagds...',
            Tagd::where('status', TagdStatus::TRANSFERRED)
                ->whereNotNull('consumer_id'),
            function ($tagd) {
                UpdateTagdAvgResaleStats::dispatch($tagd);
            },
        );
    }

    private function buildCount()
    {
        $this->processGroupOfTagds(
            'Building count for all Tagds...',
            Tagd::leafs(),
            function ($tagd) {
                UpdateTagdCountStats::dispatch($tagd);
            },
        );
    }

    private function buildTimeToTransfer()
    {
        $this->processGroupOfTagds(
            'Building timeToTransfer for all Tagds...',
            Tagd::where('status', TagdStatus::TRANSFERRED)
                ->whereNotNull('consumer_id'),
            function ($tagd) {
                UpdateTagdTimeToTransferStats::dispatch($tagd);
            },
        );
    }

    /**
     * Wrapper functions to reset Tagds
     */
    private function resetAvgResaleDiffPerc()
    {
        $this->resetGroupOfTagds(
            'Resetting avgResaleDiffPerc for all Tagds...',
            function ($tagd) {
                $stats = (array) $tagd->stats;
                if (array_key_exists('avgResaleDiffPerc', $stats)) {
                    unset($stats['avgResaleDiffPerc']);
                }
                $tagd->update([
                    'stats' => $stats,
                ]);
            }
        );
    }

    private function resetCount()
    {
        $this->resetGroupOfTagds(
            'Resetting count for all Tagds...',
            function ($tagd) {
                $stats = (array) $tagd->stats;
                if (array_key_exists('count', $stats)) {
                    unset($stats['count']);
                }
                $tagd->update([
                    'stats' => $stats,
                ]);
            }
        );
    }

    private function resetTimeToTransfer()
    {
        $this->resetGroupOfTagds(
            'Resetting ttt for all Tagds...',
            function ($tagd) {
                $stats = (array) $tagd->stats;
                if (array_key_exists('ttt', $stats)) {
                    unset($stats['ttt']);
                }
                $tagd->update([
                    'stats' => $stats,
                ]);
            }
        );
    }

    /**
     * Helper function to run a process on a group of Tagds
     *
     * @param  mixed  $query
     * @return void
     */
    private function processGroupOfTagds(
        string $message,
        $query,
        callable $process
    ) {
        $this->info("\n{$message}\n");

        $total = $query->count();

        $bar = $this->output->createProgressBar($total);

        $bar->start();

        $query->chunk(100, function (Collection $tagds) use ($bar, $process) {
            foreach ($tagds as $tagd) {
                Tagd::withoutEvents(function () use ($tagd, $process) {
                    return $process($tagd);
                });

                $bar->advance();
            }
        });

        $bar->finish();
    }

    /**
     * Helper function to reset a group of Tagds
     *
     * @return void
     */
    private function resetGroupOfTagds(
        string $message,
        callable $process
    ) {
        $this->info("\n{$message}\n");

        $total = Tagd::count();

        $bar = $this->output->createProgressBar($total);

        $bar->start();

        Tagd::chunk(100, function (Collection $tagds) use ($bar, $process) {
            foreach ($tagds as $tagd) {
                Tagd::withoutEvents(function () use ($tagd, $process) {
                    return $process($tagd);
                });

                $bar->advance();
            }
        });

        $bar->finish();
    }
}

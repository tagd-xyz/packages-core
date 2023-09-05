<?php

namespace Tagd\Core\Console\Commands\Support;

use Illuminate\Console\Command;
use Tagd\Core\Models\Item\Tagd;

class Tree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tagd:tree {tagdId} {--I|item} {--T|trust} {--S|stats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows a tree given a tagdId';

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
        $tagdId = $this->argument('tagdId');

        try {
            $tagd = Tagd::with([
                'consumer',
                'reseller',
                'item',
            ])->findOrFail($tagdId);

            if (! $tagd->is_root
                && $this->confirm('This tagd is not the tree root. Do you want the root instead?')
            ) {
                $this->renderNode($tagd->root);
            } else {
                $this->renderNode($tagd);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function renderNode(Tagd $tagd, $indentLevel = 0)
    {
        $showTrust = $this->option('trust');
        $showStats = $this->option('stats');
        $showItem = $this->option('item');

        if ($showItem && 0 == $indentLevel) {
            $retailer = $tagd->item->retailer->name;
            $this->info("{$tagd->item->name}");
            $this->info("{$tagd->item->description}");
            $this->info("Retailer: {$retailer}");
            foreach ($tagd->item->properties as $propName => $propValue) {
                $this->info("{$propName}: {$propValue}");
            }
            $this->info('');
        }

        $indent = str_repeat(' ', $indentLevel * 3);
        $status = strtoupper($tagd->status->value);
        $owner = null;
        if ($tagd->consumer) {
            $owner = 'Consumer ' . $tagd->consumer->name;
        } elseif ($tagd->reseller) {
            $owner = 'Reseller ' . $tagd->reseller->name;
        }

        $this->info("{$indent}+ {$tagd->id} ({$status})");
        $this->info("{$indent}| owned by $owner");
        $this->info("{$indent}| {$tagd->slug}");
        $this->info("{$indent}|");
        if ($showTrust) {
            $score = $tagd->trust['score'] ?? '';
            $this->info("{$indent}| trust score {$score}");
            $this->info("{$indent}|");
        }
        if ($showStats) {
            $ttt = $tagd->stats['ttt'] ?? [];
            $ttt = json_encode($ttt);
            $count = $tagd->stats['count'] ?? [];
            $count = json_encode($count);
            $this->info("{$indent}| ttt {$ttt}");
            $this->info("{$indent}| count {$count}");
            $this->info("{$indent}|");
        }

        foreach ($tagd->children()->with([
            'consumer',
            'reseller',
            'item',
        ])->get() as $child) {
            $this->renderNode($child, $indentLevel + 1);
        }
    }
}

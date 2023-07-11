<?php

namespace Tagd\Core\Tests\Traits;

use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Services\ResellerSales\Service as ResellerSalesService;

trait NeedsResales
{
    /**
     * Lists an item for resale
     */
    protected function aResale(array $options = []): Tagd
    {
        extract([
            'reseller' => Reseller::factory()->create(),
            'tagd' => Tagd::factory()
                ->for(Consumer::factory()->create())
                ->create(),
            ...$options,
        ]);

        $resellerSalesService = app()->make(ResellerSalesService::class);

        return $resellerSalesService->startResellerSale($reseller, $tagd);
    }

    /**
     * Creates a confirmed resale
     */
    protected function aConfirmedResale(array $options = []): Tagd
    {
        extract([
            'consumer' => Consumer::factory()->create(),
            'tagd' => null,
            ...$options,
        ]);

        if (is_null($tagd)) {
            $tagd = $this->aResale($options);
        }

        $resellerSalesService = app()->make(ResellerSalesService::class);

        return $resellerSalesService->confirmResale($tagd, $consumer);
    }
}

<?php

namespace Tagd\Core\Services\TrustScores\Rules;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Tagd\Core\Models\Item\Tagd;

class NearestNeighbour extends Base
{
    const MIN_TRESHOLD = 9;

    const ALPHA_FACTOR = 0.5;

    /**
     * Apply the rule
     *
     * @throws BindingResolutionException
     */
    public function apply(): float
    {
        // find other items which match the brand, model, and location
        $brand = $this->tagd->item->properties['brand'] ?? null;
        $model = $this->tagd->item->properties['model'] ?? null;
        $country = $this->tagd->meta['location']['country'] ?? null;

        if (! $brand || ! $model || ! $country) {
            return 0.0;
        }

        $tagds = Tagd::query()
            ->where('id', '<>', $this->tagd->id)
            ->whereHas('item', function (Builder $query) use ($brand, $model) {
                $query
                    ->where('properties->brand', $brand)
                    ->where('properties->model', $model);
            })
            ->where('meta->location->country', $country)
            ->orderBy('created_at', 'desc')
            ->get();

        // if there are less than treshold, do nothing further
        if ($tagds->count() < self::MIN_TRESHOLD) {
            return 0.0;
        }

        // exclude nodes in the tree for the current node
        $tree = $this->tagd->root->buildChildrenCollection()->pluck('id');
        $nodes = $tagds->filter(function (Tagd $tagd, int $key) use ($tree) {
            return $tree->doesntContain(function (string $id, int $key) use ($tagd) {
                return $id == $tagd->id;
            });
        });

        // take the first k nodes
        $k = round(sqrt($tagds->count()));
        $nodes = $nodes->take($k);

        // look at the absolute difference between the trust of this node and the
        // mean of the k nodes, and multiply this by an alpha factor.
        $scores = $nodes->map(function (Tagd $tagd, int $key) {
            return $tagd->trust_score;
        });

        $mean = $scores->avg();
        $diff = abs($this->tagd->trust_score - $mean);

        return $diff * self::ALPHA_FACTOR;
    }
}

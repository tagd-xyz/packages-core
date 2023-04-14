<?php

namespace Tagd\Core\Services\RetailerSales;

use Tagd\Core\Services\Interfaces\RetailerSales as RetailerSalesInterface;

class Service implements RetailerSalesInterface
{
    use ProcessRetailerSale;
}

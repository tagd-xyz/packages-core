<?php

namespace Tagd\Core\Services\ResellerSales;

use Tagd\Core\Services\Interfaces\ResellerSales as ResellerSalesInterface;

class Service implements ResellerSalesInterface
{
    use ConfirmResale, StartResellerSale;
}

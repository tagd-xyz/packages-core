<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Actor;

use Illuminate\Notifications\Notifiable;
use Tagd\Core\Models\Model;

class Actor extends Model
{
    use Notifiable;
}

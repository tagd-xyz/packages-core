<?php

// phpcs:ignoreFile

namespace Tagd\Core\Models\Upload;

enum Collection: string
{
    case RESELLER_AVATAR = 'resellerAvatar';
    case ITEM_IMAGES = 'itemImages';
    case STOCK_IMAGES = 'stockImages';
}

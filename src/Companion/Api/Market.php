<?php

namespace Companion\Api;

use Companion\Http\Sight;
use Companion\Models\SightRequest;

class Market extends Sight
{
    public function prices(int $itemId)
    {
        $req = new SightRequest();
        $req->setMethod(self::METHOD_GET)
            ->setRegion(self::REGION_EU)
            ->setEndpoint("/market/items/catalog/{$itemId}");
        
        return $this->request($req);
    }
}

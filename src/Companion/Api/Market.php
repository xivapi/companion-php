<?php

namespace Companion\Api;

use Companion\Http\Sight;
use Companion\Models\CompanionRequest;

/**
 * Based on: MarketService.java
 */
class Market extends Sight
{
    /**
     * Methods: delete, patch
     */
    public function retainersRack(string $cid, int $itemId)
    {
    
    }
    
    public function itemsCatalogHq(int $itemId)
    {
    
    }
    
    public function itemsCatalog(int $itemId)
    {
        $req = new CompanionRequest([
            'uri'      => CompanionRequest::URI_EU,
            'endpoint' => "/market/items/catalog/{$itemId}",
        ]);
    
        return $this->get($req)->getJson();
    }
    
    public function itemsCategory(int $categoryId)
    {
    
    }
}

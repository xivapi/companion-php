<?php

namespace Companion\Api;

use Companion\Config\CompanionConfig;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Models\Method;

/**
 * Based on: MarketService.java
 */
class Market extends Sight
{
    /**
     * @DELETE("market/retainers/{cid}/rack/{itemId}")
     */
    public function cancelListing(string $cid, int $itemId)
    {
    
    }
    
    /**
     * @PATCH("market/retainers/{cid}/rack/{itemId}")
     */
    public function changePrice(string $cid, int $itemId, int $pointType = null)
    {
    
    }
    
    /**
     * catalogId = itemId
     * @GET("market/items/catalog/{catalogId}/hq")
     */
    public function getItemMarketHqListings(int $itemId)
    {
        $req = new CompanionRequest([
            'method'   => Method::GET,
            'uri'      => CompanionConfig::getToken()->region,
            'endpoint' => "/market/items/catalog/{$itemId}/hq",
        ]);
    
        return CompanionConfig::isAsync() ? $req : $this->request($req)->getJson();
    }
    
    /**
     * catalogId = itemId
     * @GET("market/items/catalog/{itemId}")
     */
    public function getItemMarketListings(int $itemId)
    {
        $req = new CompanionRequest([
            'method'    => Method::GET,
            'uri'       => CompanionConfig::getToken()->region,
            'endpoint'  => "/market/items/catalog/{$itemId}"
        ]);

        return CompanionConfig::isAsync() ? $req : $this->request($req)->getJson();
    }
    
    /**
     * @GET("market/items/category/{categoryId}")
     */
    public function getMarketListingsByCategory(int $categoryId)
    {
        $req = new CompanionRequest([
            'method'   => Method::GET,
            'uri'      => CompanionConfig::getToken()->region,
            'endpoint' => "/market/items/category/{$categoryId}",
        ]);
    
        return CompanionConfig::isAsync() ? $req : $this->request($req)->getJson();
    }
    
    /**
     * @GET("market/retainers/{cid}")
     */
    public function getRetainerInfo(string $cid)
    {
        $req = new CompanionRequest([
            'method'   => Method::GET,
            'uri'      => CompanionConfig::getToken()->region,
            'endpoint' => "/market/retainers/{$cid}",
        ]);
    
        return CompanionConfig::isAsync() ? $req : $this->request($req)->getJson();
    }
    
    /**
     * @GET("market/items/history/catalog/{itemId}")
     */
    public function getTransactionHistory(int $itemId)
    {
        $req = new CompanionRequest([
            'method'   => Method::GET,
            'uri'      => CompanionConfig::getToken()->region,
            'endpoint' => "/market/items/history/catalog/{$itemId}",
        ]);
    
        return CompanionConfig::isAsync() ? $req : $this->request($req)->getJson();
    }
    
    /**
     * @POST("market/item")
     */
    public function purchaseItem(int $pointType = null, array $json = [])
    {
    
    }
    
    /**
     * @POST("market/retainers/{cid}/rack")
     */
    public function registerListing(string $cid, int $pointType = null, array $json = [])
    {
    
    }
    
    /**
     * @POST("market/retainers/{cid}")
     */
    public function resumeListing(string $cid)
    {
    
    }
    
    /**
     * @POST("market/payment/transaction")
     */
    public function setTransactionLock()
    {
    
    }
    
    /**
     * @DELETE("market/retainers/{cid}")
     */
    public function stopListing(string $cid)
    {
    
    }
}

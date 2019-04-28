<?php

namespace Companion\Api;

use Companion\Config\CompanionTokenManager;
use Companion\Exceptions\CompanionServerException;
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
    public function getItemMarketHqListings(int $itemId, string $server = null)
    {
        $server = $server ?: CompanionTokenManager::getToken()->server;
    
        if ($server == null) {
            throw new CompanionServerException('You must provide a server with requests to this endpoint.');
        }
        
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => "/market/items/catalog/{$itemId}/hq",
                'query'    => [
                    'worldName' => $server
                ]
            ])
        );
    }
    
    /**
     * catalogId = itemId
     * @GET("market/items/catalog/{itemId}")
     */
    public function getItemMarketListings(int $itemId, string $server = null)
    {
        $server = $server ?: CompanionTokenManager::getToken()->server;
    
        if ($server == null) {
            throw new CompanionServerException('You must provide a server with requests to this endpoint.');
        }
        
        return $this->json(
            new CompanionRequest([
                'method'    => Method::GET,
                'uri'       => CompanionTokenManager::getToken()->region,
                'endpoint'  => "/market/items/catalog/{$itemId}",
                'query'    => [
                    'worldName' => $server
                ]
            ])
        );
    }
    
    /**
     * @GET("market/items/category/{categoryId}")
     */
    public function getMarketListingsByCategory(int $categoryId, string $server = null)
    {
        $server = $server ?: CompanionTokenManager::getToken()->server;
        
        if ($server == null) {
            throw new CompanionServerException('You must provide a server with requests to this endpoint.');
        }
        
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => "/market/items/category/{$categoryId}",
                'query'    => [
                    'worldName' => $server
                ]
            ])
        );
    }
    
    /**
     * @GET("market/retainers/{cid}")
     */
    public function getRetainerInfo(string $cid)
    {
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => "/market/retainers/{$cid}",
            ])
        );
    }
    
    /**
     * @GET("market/items/history/catalog/{itemId}")
     */
    public function getTransactionHistory(int $itemId, string $server = null)
    {
        $server = $server ?: CompanionTokenManager::getToken()->server;
    
        if ($server == null) {
            throw new CompanionServerException('You must provide a server with requests to this endpoint.');
        }
        
        return $this->json(
            new CompanionRequest([
                'method'   => Method::GET,
                'uri'      => CompanionTokenManager::getToken()->region,
                'endpoint' => "/market/items/history/catalog/{$itemId}",
                'query'    => [
                    'worldName' => $server
                ]
            ])
        );
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

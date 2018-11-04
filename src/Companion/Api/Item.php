<?php

namespace Companion\Api;

use Companion\Http\Sight;

/**
 * Based on: ItemService.java
 */
class Item extends Sight
{
    /**
     * @GET("items/character")
     */
    public function getCharacterItems()
    {
    
    }
    
    /**
     * @GET("character/login-status")
     */
    public function getLoginStatus()
    {
    
    }
    
    /**
     * @GET("items/retainers/{retainerCid}")
     */
    public function getRetainerItems(string $retainerCid)
    {
    
    }
    
    /**
     * @GET("retainers")
     */
    public function getRetainers()
    {
    
    }
    
    /**
     * @PUT("items/{type}/{cid}/gil")
     */
    public function moveGil(string $type, string $cid)
    {
    
    }
    
    /**
     * @PUT("items/{type}/{cid}/{storage}")
     */
    public function moveItems(string $type, string $cid, string $storage)
    {
    
    }
    
    /**
     * @DELETE("items/{type}/{cid}/{storage}/{itemId}")
     */
    public function processItem(string $type, string $cid, string $storage, string $itemId, int $deleteType)
    {
    
    }
    
    /**
     * @PUT("items/recycle/{itemId}")
     */
    public function recycleItem(string $itemId, int $recoveryType)
    {
    
    }
}

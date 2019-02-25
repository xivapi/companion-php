<?php

namespace Companion\Api;

use Companion\Config\CompanionTokenManager;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Models\Method;

/**
 * Based on: AddressBookService.java
 */
class AddressBook extends Sight
{
    /**
     * todo - investigate
     * @HTTP(hasBody=true, method="DELETE", path="address-book/blocklist")
     */
    public function deleteBlockList(array $json = [])
    {
        $req = new CompanionRequest([
            'method'   => Method::DELETE,
            'uri'      => CompanionTokenManager::getToken()->region,
            'endpoint' => "/address-book/blocklist",
        ]);
    
        return $this->json($req);
    }
    
    /**
     * todo - investigate: updatedAt seems to be a unix timestamp
     * @GET("address-book")
     */
    public function getAddressBook(int $updatedAt = null)
    {
        $req = new CompanionRequest([
            'method'   => Method::GET,
            'uri'      => CompanionTokenManager::getToken()->region,
            'endpoint' => "/address-book",
        ]);
    
        return $this->json($req);
    }
    
    /**
     * todo - investigate: updatedAt seems to be a unix timestamp
     * @GET("address-book/{cid}/profile")
     */
    public function getCharacter(string $characterId, int $updatedAt = null)
    {
        $req = new CompanionRequest([
            'method'    => Method::GET,
            'uri'       => CompanionTokenManager::getToken()->region,
            'endpoint'  => "/address-book/{$characterId}/profile",
        ]);
    
        return $this->json($req);
    }
    
    /**
     * todo - investigate
     * @POST("address-book/blocklist")
     */
    public function postBlockList(array $json = [])
    {
        $req = new CompanionRequest([
            'method'   => Method::POST,
            'uri'      => CompanionTokenManager::getToken()->region,
            'endpoint' => "/address-book/blocklist",
        ]);
        
        return $this->json($req);
    }
}

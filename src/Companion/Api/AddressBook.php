<?php

namespace Companion\Api;

use Companion\Config\Profile;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;

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
            'uri'      => Profile::get('region'),
            'endpoint' => "/address-book/blocklist",
        ]);
    
        return $this->delete($req)->getJson();
    }
    
    /**
     * todo - investigate: updatedAt seems to be a unix timestamp
     * @GET("address-book")
     */
    public function getAddressBook(int $updatedAt = null)
    {
        $req = new CompanionRequest([
            'uri'      => Profile::get('region'),
            'endpoint' => "/address-book",
        ]);
    
        return $this->get($req)->getJson();
    }
    
    /**
     * todo - investigate: updatedAt seems to be a unix timestamp
     * @GET("address-book/{cid}/profile")
     */
    public function getCharacter(string $characterId, int $updatedAt = null)
    {
        $req = new CompanionRequest([
            'uri'      => Profile::get('region'),
            'endpoint' => "/address-book/{$characterId}/profile",
        ]);
    
        return $this->get($req)->getJson();
    }
    
    /**
     * todo - investigate
     * @POST("address-book/blocklist")
     */
    public function postBlockList(array $json = [])
    {
        $req = new CompanionRequest([
            'uri'      => Profile::get('region'),
            'endpoint' => "/address-book/blocklist",
        ]);
        
        return $this->post($req)->getJson();
    }
}

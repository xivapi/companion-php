<?php

namespace Companion\Api;

use Companion\Config\CompanionConfig;
use Companion\Http\Sight;
use Companion\Models\CompanionRequest;
use Companion\Models\Method;

/**
 * Based on: PaymentService.java
 */
class Payments extends Sight
{
    /**
     * No, you can't spam this for free nuts. I tried.
     * @POST("points/kupo-nuts")
     */
    public function acquirePoints()
    {
        $req = new CompanionRequest([
            'method'   => Method::POST,
            'uri'      => CompanionConfig::getToken()->region,
            'endpoint' => "/points/kupo-nuts",
        ]);
    
        return $this->request($req)->getJson();
    }
    
    /**
     * @PUT("points/mog-coins/android")
     */
    public function finishPurchase(array $json)
    {
    
    }
    
    /**
     * @GET("purchase/charge")
     */
    public function getBillingAmount()
    {
    
    }
    
    /**
     * @GET("purchase/cesa-limit")
     */
    public function getBillingLimits(string $updatedAt = null)
    {
    
    }
    
    /**
     * @GET("purchase/user-birth")
     */
    public function getBirthDate()
    {
    
    }
    
    /**
     * @GET("points/history")
     */
    public function getCurrencyHistory(int $type = null)
    {
    
    }
    
    /**
     * @GET("points/status")
     */
    public function getCurrencyStatus()
    {
        $req = new CompanionRequest([
            'uri'      => CompanionConfig::getToken()->region,
            'endpoint' => "/points/status",
        ]);
        
        return $this->get($req)->getJson();
    }
    
    /**
     * @GET("points/products")
     */
    public function getProducts()
    {
    
    }
    
    /**
     * @POST("purchase/user-birth")
     */
    public function postBirthDate(array $json)
    {
    
    }
    
    /**
     * @POST("points/interrupted-process")
     */
    public function resumeTransaction()
    {
    
    }
    
    /**
     * @POST("purchase/transaction")
     */
    public function setTransactionLock(array $json)
    {
    
    }
}

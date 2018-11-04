<?php

namespace Companion\Api;

use Companion\Http\Sight;

/**
 * Based on: PaymentService.java
 */
class Payments extends Sight
{
    /**
     * @POST("points/kupo-nuts")
     */
    public function acquirePoints()
    {
    
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

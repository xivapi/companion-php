<?php

namespace Companion\Api;

use Companion\Http\Sight;

/**
 * Based on: ScheduleService.java
 */
class Schedule extends Sight
{
    /**
     * @PATCH("schedules/{sid}/cancel")
     */
    public function cancelSchedule(string $sid)
    {
    
    }
    
    /**
     * @PATCH("schedules/{sid}/close")
     */
    public function closeSchedule(string $sid, array $json = [])
    {
    
    }
    
    /**
     * @DELETE("schedules/{sid}")
     */
    public function deleteSchedule(string $sid)
    {
    
    }
    
    /**
     * @PATCH("schedules/{sid}")
     */
    public function editSchedule(string $sid, array $json = [])
    {
    
    }
    
    /**
     * This doesn't seem to be in use? no endpoint
     */
    public function getChatRoomScheduleHistory(string $rid, string $lastResponseTime = null, string $startAt = null, string $from = null, string $to = null, string $count = null)
    {
    
    }
    
    /**
     * @GET("schedules/chatrooms/{rid}")
     */
    public function getChatRoomSchedules(string $rid, string $lastResponseTime = null, string $startAt = null, string $from = null, string $to = null, string $count = null)
    {
    
    }
    
    /**
     * @GET("schedules/{sid}")
     */
    public function getSchedule(string $sid, string $updatedAt = null)
    {
    
    }
    
    /**
     * @GET("schedules/history")
     */
    public function getScheduleHistory(string $lastResponseTime = null, string $startAt = null, string $from = null, string $to = null, string $count = null)
    {
    
    }
    
    /**
     * @GET("schedules")
     */
    public function getSchedules(string $lastResponseTime = null, string $startAt = null, string $from = null, string $to = null, string $count = null)
    {
    
    }
    
    /**
     * @POST("schedules")
     */
    public function postSchedule(array $json = [])
    {
    
    }
    
    /**
     * @POST("schedules/{sid}/push-notification")
     */
    public function setPushNotification(string $sid, array $json = [])
    {
    
    }
    
    /**
     * @PATCH("schedules/{sid}/role")
     */
    public function updateScheduleEntry(string $sid, array $json = [])
    {
    
    }
}

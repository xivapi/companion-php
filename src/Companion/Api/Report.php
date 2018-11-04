<?php

namespace Companion\Api;

use Companion\Http\Sight;

/**
 * Based on: ReportService.java
 */
class Report extends Sight
{
    /**
     * @POST("report/chatrooms/{rid}/message")
     */
    public function reportChatMessage(string $rid, array $json = [])
    {
    
    }
    
    /**
     * @POST("report/chatrooms/{rid}")
     */
    public function reportChatRoom(string $rid, array $json = [])
    {
    
    }
    
    /**
     * @POST("report/schedules/{sid}")
     */
    public function reportSchedule(string $sid, array $json = [])
    {
    
    }
    
    /**
     * @POST("report/schedules/{sid}/comment")
     */
    public function reportScheduleComment(string $sid, array $json = [])
    {
    
    }
}

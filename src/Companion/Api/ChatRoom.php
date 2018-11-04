<?php

namespace Companion\Api;

use Companion\Http\Sight;

/**
 * Based on: ChatRoomService.java
 */
class ChatRoom extends Sight
{
    /**
     * @POST("chatrooms/{rid}/members")
     */
    public function addMembers(string $rid)
    {
    
    }
    
    /**
     * @DELETE("chatrooms/{rid}")
     */
    public function deleteChatRoom(string $rid)
    {
    
    }
    
    /**
     * @DELETE("chatrooms/{rid}/messages/{seqNum}")
     */
    public function deleteMessage(string $rid, $seqNum)
    {
    
    }
    
    /**
     * @GET("chatrooms/{rid}")
     */
    public function getChatRoom(string $rid, int $startAt = null)
    {
    
    }
    
    /**
     * @GET("chatrooms")
     */
    public function getChatRoomList(int $updatedAt = null)
    {
    
    }
    
    /**
     * @GET("chatrooms/{rid}/messages")
     */
    public function getMessages(string $rid, int $readSeqNum = null, int $seqNum = null, int $count = null)
    {
    
    }
    
    /**
     * @POST("chatrooms")
     */
    public function postChatRoom()
    {
    
    }
    
    /**
     * @POST("chatrooms/{rid}/messages")
     */
    public function postMessage(string $rid)
    {
    
    }
    
    /**
     * @POST("chatrooms/{rid}/push-notification")
     */
    public function setPushNotification(string $rid)
    {
    
    }
    
    /**
     * @PATCH("chatrooms/{rid}/messages/last-chat")
     */
    public function updateSeqNum(string $rid)
    {
    
    }
    
    /**
     * @PATCH("chatrooms/{rid}/setting")
     */
    public function updateSettings(string $rid)
    {
    
    }
}

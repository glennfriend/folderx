<?php
/**
 *  這是給開發人員使用的工具
 *  可以將 文字資訊 傳送/儲存/顯示 到某處
 *
 *      - 適用於 傳遞給開發人員的 測試訊息/debug-info
 *      - 不適用於 發送資訊到 公開場合/客戶通知
 *
 */
class InfoBrg
{

    /**
     *  傳送至 hipChat service
     *  @return boolean
     */
    static public function hipChat( $text, $roomName='stargazer', $from='wms', $color=null )
    {
        Yii::import('application.vendors.HipChat.*');
        require_once 'HipChat.php';
        $hc = new HipChat\HipChat('bd4eebbe15ea209d5c51c737bfe6e9');

        $roomId = 0;
        try {
            foreach ( $hc->get_rooms() as $room) {
                if ( $roomName === $room->name ) {
                    $roomId = $room->room_id;
                    break;
                }
            }
        }
        catch (HipChat_Exception $e) {
            return false;
        }

        if ( !$roomId ) {
            return false;
        }

        try {
            $room_data = $hc->get_room($roomId);
            // print_r( $room_data->participants );
            $hc->message_room( $roomName, $from, $text, false, $color );
        }
        catch (HipChat_Exception $e) {
            return false;
        }

        return true;
    }

    /**
     *  專門給 messages.php API 使用
     *  @return boolean
     */
    static public function hipChatByMessagesApi( $text, $roomName, $from )
    {
        self::hipChat( $text, $roomName, $from, 'purple' );
    }

    /**
     *  儲存至檔案, 做為 log file 
     *  @return boolean
     */
    // static public function file( $text, $filename )
    // {
    //     return false;
    // }

    /**
     *  傳送至 email
     *  @return boolean
     */
    // static public function email( $text, $email )
    // {
    //     return false;
    // }

    /**
     *  傳送至 yii database log
     *  @return boolean
     */
    static public function db( $text, $level, $category )
    {
        if ( is_array($data) ) {
            $message = json_encode($data);
        }
        elseif ( is_object($data) ) {
            // ob_start();
            // var_dump($data);
            // $message = ob_get_clean();
            $message = json_encode($data);
        }
        else {
            $message =& $data;
        }

        if ( $ip = self::_getClientIp() ) {
            $message = $message ."\n". "client ip=". $ip;
        }

        Yii::log( $message, $level, $category );
        return true;
    }


    /**
     *  get client ip
     */
    static protected function _getClientIp()
    {
        foreach ( array(
                    'HTTP_CLIENT_IP',
                    'HTTP_X_FORWARDED_FOR',
                    'HTTP_X_FORWARDED',
                    'HTTP_X_CLUSTER_CLIENT_IP',
                    'HTTP_FORWARDED_FOR',
                    'HTTP_FORWARDED',
                    'REMOTE_ADDR') as $key
        ) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if ((bool) filter_var($ip, FILTER_VALIDATE_IP,
                                    FILTER_FLAG_IPV4 |
                                    FILTER_FLAG_NO_PRIV_RANGE |
                                    FILTER_FLAG_NO_RES_RANGE)
                    ) {
                        return $ip;
                    }
                }
            }
        }
        return null;
    }

}

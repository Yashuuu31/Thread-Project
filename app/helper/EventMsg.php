<?php 

namespace App\Helper;

class EventMsg{
    
    // Success Msg Store Session Flash
    public static function SuccessMsg($msg){
        session()->flash('success', $msg);
    }

}

?>
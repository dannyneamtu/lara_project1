<?php

#################### Helper Function for Flash Messages ######################
class FlashMessage
{
    public static function DisplayAlert($message, $type)
    {
        return "<h4 class='alert alert-". $type ."' style='color:red' align='center'>". $message ."</h4>";
    }//DisplayAlert

}//FlashMessage
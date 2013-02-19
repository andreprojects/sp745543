<?php


namespace Login\Custom;

Class timer{
  
  public function timestamp_timezone($locale, $timestamp=null){
  
    if(is_null($timestamp)) $timestamp = time();
  
     //Prepare to calculate the time zone offset
      $current = time();
    
    $tz = date_default_timezone_get();
      date_default_timezone_set($locale);
    
    $offset = time() - $current;
     
    return $timestamp - $offset;
  }
  
  public function getTimeCurrent(){
    return $timezone = $this->timestamp_timezone("America/Sao_Paulo",time());
    //return date("Y-m-d H:i:s",$timezone);
  }
  
}

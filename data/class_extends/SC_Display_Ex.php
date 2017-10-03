<?php

require_once CLASS_REALDIR . 'SC_Display.php';

class SC_Display_Ex extends SC_Display
{
	
	// 追記部分
public static function detectDevice($reset = FALSE) {
  return DEVICE_TYPE_PC;
}
// 追記ここまで
}



<?php
function customErrorHandler($errno,$errstr,$errfile,$errline){
    //tangkap semua jenis error
    throw new
    
    ErrorException($errstr,0,$errno,$errfile,$errline);
}
set_error_handler('customErrorHandler');
?>
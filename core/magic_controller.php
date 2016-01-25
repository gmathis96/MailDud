<?php
    if(uri(1) == "ajax"){
        class controller extends controller_ajax{}
    }else if(uri(1) == "cron"){
        class controller extends controller_cron{}
    }else{
        class controller extends controller_site{}
    }
?>
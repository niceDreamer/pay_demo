<?php
function qrcode($url,$size=4){
    Vendor('phpqrcode.phpqrcode');
    QRcode::png($url,false,QR_ECLEVEL_L,$size,3,false,0xFFFFFF,0x000000);
}
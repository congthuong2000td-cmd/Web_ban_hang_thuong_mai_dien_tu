<?php

return [
    /*
     |-----------------------------------------------------------------------
     | VNPay Sandbox Configuration
     |-----------------------------------------------------------------------
     | Đăng ký tài khoản test tại: https://sandbox.vnpayment.vn/
     */
    'url'         => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'tmn_code'    => env('VNPAY_TMN_CODE', ''),
    'hash_secret' => env('VNPAY_HASH_SECRET', ''),
];

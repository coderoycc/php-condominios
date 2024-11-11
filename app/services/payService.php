<?php

namespace App\Services;

use App\Models\Payment;
use App\Services\Interfaces\IPay;

class PayService implements IPay {
  public function new($con, $condominio, $amount, $gloss, $phoneNumber, $city): Payment {
    return new Payment();
  }

  public function getqr($con, $qr_id, $condominio): Payment {
    return new Payment();
  }
}
if (!function_exists('pay')) {
  function pay(): IPay {
    return new PayService();
  }
}

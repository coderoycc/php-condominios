<?php

namespace Helpers\Resources;

class HandleDates {
  public static function addDate($meses): string { // mejor si es 00:00:00
    $date = date('Y-m-d H:i:s', strtotime("+$meses months"));
    return $date;
  }
  public static function remaining_days($date): string {
    $message = '';
    $today = new \DateTime();
    $expire = new \DateTime($date);
    $interval = $expire->diff($today);
    $days = $interval->days;
    if ($days < 0) {
      $message = 'Advertencia de expirado';
      if ($today->getTimestamp() > $expire->getTimestamp()) {
        $message = 'La suscripción ha expirado';
      }
    } else if ($days < 16) {
      $message = 'Faltan ' . $days . ' días para que expire la suscripción';
    }
    return $message;
  }
  public static function expired($date): bool {
    $today = new \DateTime();
    $expire = new \DateTime($date);
    return $today->getTimestamp() > $expire->getTimestamp();
  }
}

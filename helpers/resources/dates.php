<?php

namespace Helpers\Resources;

class HandleDates {
  public static function add_date($meses): string { // mejor si es 00:00:00
    $date = date('Y-m-d H:i:s', strtotime("+$meses months"));
    return $date;
  }
  /**
   * Devuelve un string con una fecha anterior de acuerdo al parametro enviado (Hoy - X meses)
   * @param mixed $months Cantidad de meses que desea retroceder
   * @return string
   */
  public static function prev_date($months) {
    $months = intval($months ?? 1);
    $date = date('Y-m-d', strtotime("-$months months"));
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
  public static function date_expire_month($months): string {
    $current = time();
    $with_n_months = intval($months) * 2592000; // 30*24*60*60
    $date_expire = date('Y-m-d', $current + $with_n_months) . 'T23:59:59';
    return $date_expire;
  }
  public static function date_format_db($date = null) {
    if ($date) {
      return date('Y-m-d\TH:i:s', strtotime($date));
    }
    return null;
  }
  public static function get_month_str($date): string {
    $months = ['01' => 'ENERO', '02' => 'FEBRERO', '03' => 'MARZO', '04' => 'ABRIL', '05' => 'MAYO', '06' => 'JUNIO', '07' => 'JULIO', '08' => 'AGOSTO', '09' => 'SEPTIEMPRE', '10' => 'OCTUBRE', '11' => 'NOVIEMBRE', '12' => 'DICIEMBRE'];
    $month = date('m', strtotime($date));
    $year = intval(date('Y', strtotime($date)));
    $year_curr = intval(date('Y'));
    if ($year_curr != $year) {
      $res = substr($months[$month], 0, 3) . '-' . $year;
    } else {
      $res = $months[$month];
    }
    return $res;
  }
}

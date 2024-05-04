<?php

namespace App\Providers;

use App\Config\Database;
use App\Models\Subscription;
use App\Models\User;
use Helpers\Resources\HandleDates;
use Helpers\Resources\Response;

class AuthProvider {
  private $con = null;
  public function __construct($pin = null, $dbName = null) {
    if (is_null($pin) && is_null($dbName)) {
      Response::error_json(['message' => 'Error Auth Provider names NULL']);
    }
    try {
      if (is_null($pin))
        $this->con = Database::getInstanceX($dbName);
      else
        $this->con = Database::getInstanceByPin($pin);
    } catch (\Throwable $th) {
      Response::error_json(['message' => 'AuthProvider Constructor' . $th->getMessage()], 500);
    }
  }
  public function auth($user, $password): array {
    $data = [];
    try {
      $user = User::exist($user, $password, $this->con);
      unset($user->password);
      $data['user'] = $user;
      if ($user->id_user > 0) {
        $subscription = Subscription::getSusbscriptionUser($this->con, $user->id_user);
        $subscription->type();
        $data['subscription'] = $subscription;
        if ($user->role == 'resident') {
          $data['expired'] = HandleDates::expired($subscription->expires_in);
          $data['message_subscription'] = HandleDates::remaining_days($subscription->expires_in);
        } else {
          $data['expired'] = false;
          $data['message_subscription'] = '';
        }
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return $data;
  }
  public function auth_web($user, $password) {
    $data = ['user' => null, 'admin' => 0];
    try {
      $user = User::exist($user, $password, $this->con);
      unset($user->password);
      if ($user->id_user > 0) {
        $data['user'] = $user;
        if ($user->role == 'admin') {
          $data['admin'] = 1;
        }
      }
      return $data;
    } catch (\Throwable $th) {
      Response::error_json(['message' => 'Error Auth Provider names NULL' . $th->getMessage()]);
    }
  }
}

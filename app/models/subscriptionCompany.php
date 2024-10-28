<?php

namespace App\Models;

use App\Models\BaseModel;
use PDO;

/**
 * Clase que almacena las suscripciones que tiene el usuario
 * REPOSITORIO
 */
class SubscriptionCompany {
  public static function addNewSubscription($con, $company, $company_id, $id_subscription) {
    try {
      $sql = "INSERT INTO tblSubscriptionCompany(company, company_id, subscription_id) VALUES(?, ?, ?);";
      $stmt = $con->prepare($sql);
      $stmt->execute([$company, $company_id, $id_subscription]);
      return 1;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return -1;
  }
  public static function getMySubscriptions($con, $sub_id) {
    try {
      $sql = "SELECT * FROM tblSubscriptionCompany WHERE subscription_id = $sub_id;";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return [];
  }
  public static function deleteSubscription($con, $company_id, $sub_id) {
    try {
      $sql = "DELETE FROM tblSubscriptionCompany WHERE company_id = ? AND subscription_id = ?;";
      $stmt = $con->prepare($sql);
      $stmt->execute([$company_id, $sub_id]);
      return 1;
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return -1;
  }
}

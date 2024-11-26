<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\Ads;
use App\Models\Company;
use Helpers\Resources\Render;
use Helpers\Resources\Request;
use Helpers\Resources\Response;

class AdsController {
  /**
   * Devuelve la lista de anunciantes
   */
  public function list_advertiser($query)/*web*/ {
    $companies = Company::get_companies([]);
    Render::view('ads/list_advertiser', ['companies' => $companies]);
  }
  /**
   * Devuelve lista de anuncios
   */
  public function list_ad($query)/*web*/ {
    $con = Database::getInstaceCondominios();
    $ads = Ads::all($con, 'WHERE b.status = 1');
    Render::view('ads/list_ad', ['ads' => $ads]);
  }
  public function form_new_ad($query)/*web*/ {
    $advertisers = Company::get_companies([]);
    Render::view('ads/form_new_ad', ['advertisers' => $advertisers]);
  }
  public function form_edit_ad($query)/*web*/ {
    $con = Database::getInstaceCondominios();
    $ad = new Ads($con, $query['id']);
    $advertisers = Company::get_companies([]);
    Render::view('ads/form_edit_ad', ['advertisers' => $advertisers, 'ad' => $ad]);
  }
  /**
   * Crea un anuncio
   * @return void
   */
  public function create_ad($body, $files = null)/*web*/ {
    if (!Request::required(['company_id', 'type', 'description'], $body))
      Response::error_json(['message' => 'Error, parametros faltantes']);
    $con = Database::getInstaceCondominios();
    $company = new Company($con, $body['company_id']);
    $ad = new Ads($con);
    $ad->company_id = intval($body['company_id']);
    $ad->description = $body['description'];
    $ad->type = $body['type'];
    $ad->direct_to = $body['direct_to'] ?? '';
    $ad->start_date = $body['start_date'] ?? date('Y-m-d');
    $ad->end_date = $body['end_date'] ?? date('Y-m-d');
    $ad->target = $body['target'] ?? 'O';
    if (isset($files['file'])) {
      $originalFilename = $files['file']['name'];
      $fileExtension = pathinfo($originalFilename, PATHINFO_EXTENSION);
      $filename = str_replace(' ', '_', strtolower($company->company)) . '_' . date('Y-m-d_H-i-s') . '.' . $fileExtension;
      $destination = __DIR__ . '/../../public/ads/' . $filename;
      move_uploaded_file($files['file']['tmp_name'], $destination);
      $ad->content = $filename;
    } else {
      $ad->content = $body['video_url'] ?? '';
    }
    if ($ad->insert() > 0) {
      Response::success_json('Anuncio creado correctamente', ['ad' => $ad]);
    } else {
      Response::error_json(['message' => 'Error al crear el anuncio'], 200);
    }
  }
  public function update($body)/*web*/ {
    if (!Request::required(['id', 'company_id', 'start_date', 'end_date', 'description'], $body))
      Response::error_json(['message' => 'Error, parametros faltantes']);
    $con = Database::getInstaceCondominios();
    $ad = new Ads($con, $body['id']);
    $ad->company_id = intval($body['company_id']);
    $ad->description = $body['description'];
    $ad->direct_to = $body['direct_to'] ?? '';
    $ad->start_date = $body['start_date'];
    $ad->end_date = $body['end_date'];
    $ad->target = $body['target'] ?? $ad->target;
    if ($ad->update()) {
      Response::success_json('Anuncio actualizado correctamente', ['ad' => $ad]);
    } else {
      Response::error_json(['message' => 'Error al actualizar el anuncio'], 200);
    }
  }
  public function today($query) /*protected*/ {
    $con = Database::getInstaceCondominios();
    $today = date('Y-m-d');
    $ads = Ads::all($con, "WHERE '$today' BETWEEN a.start_date AND a.end_date");
    $res_ads = [];
    foreach ($ads as $ad) {
      $new_ad = new Ads();
      $new_ad->load($ad);
      $company = new Company($con, $ad['id_company']);
      // $company->load($ad);
      $new_ad->{'company'} = $company;
      $res_ads[] = $new_ad;
    }
    Response::success_json('Anuncios obtenidos correctamente', $res_ads);
  }
  public function delete($body)/*web*/ {
    if (!Request::required(['id'], $body))
      Response::error_json(['message' => 'Error, parametros faltantes'], 200);

    $con = Database::getInstaceCondominios();
    $ad = new Ads($con, $body['id']);
    if ($ad->delete()) {
      Response::success_json('Anuncio eliminado correctamente', ['ad' => $ad]);
    } else {
      Response::error_json(['message' => 'Error al eliminar el anuncio'], 200);
    }
  }
}

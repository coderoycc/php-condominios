<?php

namespace App\Controllers;

use App\Models\Advertiser;
use App\Providers\DBWebProvider;
use Helpers\Resources\Render;

class AdsController {
  /**
   * Devuelve la lista de anunciantes
   */
  public function list_advertiser($query)/*web*/ {
    Render::view('ads/list_advertiser', $query);
  }
  /**
   * Devuelve lista de anuncios
   */
  public function list_ad($query)/*web*/ {
    Render::view('ads/list_ad', $query);
  }
  public function form_new_ad($query)/*web*/ {
    $con = DBWebProvider::getSessionDataDB();
    // $advertisers = Advertiser::all();
    $advertisers = [['id_advertiser' => 1, 'name' => 'LIDITA SRL.', 'created' => '2024-12-12'], ['id_advertiser' => 2, 'name' => 'Floreria San Julian', 'created' => '2024-12-09']];
    Render::view('ads/form_new_ad', ['advertisers' => $advertisers]);
  }
  /**
   * Crea un anuncio
   * @return void
   */
  public function create_ad()/*web*/ {
  }
  /**
   * Crea anunciantes 
   * @return void
   */
  public function create_advertiser()/*web*/ {
  }
}

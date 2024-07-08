<?php

namespace App\Controllers;

use Helpers\Resources\Render;

class AdsController {
  /**
   * Devuelve la lista de anunciantes
   */
  public function list_advertiser($query) {
    Render::view('ads/list_advertiser', $query);
  }
  /**
   * Devuelve lista de anuncios
   */
  public function list_ad($query) {
    Render::view('ads/list_ad', $query);
  }
  /**
   * Crea un anuncio
   * @return void
   */
  public function create_ad() {
  }
  /**
   * Crea anunciantes 
   * @return void
   */
  public function create_advertiser() {
  }
}

<?php

namespace Drupal\ac_rest_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for AC REST API routes.
 */
class AcRestApiController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}

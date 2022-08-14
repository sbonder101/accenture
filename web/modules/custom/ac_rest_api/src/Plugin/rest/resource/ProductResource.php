<?php

namespace Drupal\ac_rest_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\app\Controller\Methods;
use Drupal\node\Entity\Node;
/**
 * Provides a resource for article.
 *
 * @RestResource(
 *   id = "product_resource",
 *   label = @Translation("Product Resource"),
 *   uri_paths = {
 *     "canonical" = "/api/products/all"
 *   }
 * )
 */
class ProductResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */

  public function get() {
    // $methods = new Methods();

    $code = 200;
    $message = 'Something went wrong';
    $data = [];

    

    try {

        $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');

    
        $ids = $nodeStorage->getQuery()
        ->condition('status', 1)
        ->condition('type', 'product') // type = bundle id (machine name)
        //->sort('created', 'ASC') // sorted by time of creation
        //->pager(15) // limit 15 items
        ->execute();

        $products = $nodeStorage->loadMultiple($ids);

        foreach($products as $product) {

            $product_count = 0;
           
            array_push($data, [
              'id' => $product->id(),
              'name' => $product->getTitle(),
              'sku' => $product->field_sku->value,
              'price' => $product->field_price->value,
              'description' => $product->get('body')->value,
            ]);
          }

    } catch (\Exception $e) {
        $code = 400;
        $response = [
          'message' => $e->getMessage(),
        ];
    }

    $response = [
        'status' => $code,
        'data' => $data
    ];

    $response = new ResourceResponse($response, $code);
    $disable_cache = new CacheableMetadata();
    $disable_cache->setCacheMaxAge(0);
    $response->addCacheableDependency($disable_cache);

    return $response;
  }

    /** 
    * 
    * {@inheritdoc} 
    *
    */
  public function permissions() {
    return [];
  }

}

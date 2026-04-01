<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Vin;

class SAQService
{
  private $url = "https://catalog-service.adobe.io/graphql";

  private function headers()
  {
    return [
      "x-api-key" => "7a7d7422bd784f2481a047e03a73feaf",
      "magento-customer-group" => "b6589fc6ab0dc82cf12099d1c2d40ab994e8410c",
      "magento-environment-id" => "2ce24571-9db9-4786-84a9-5f129257ccbb",
      "magento-store-code" => "main_website_store",
      "magento-store-view-code" => "fr",
      "magento-website-code" => "base"
    ];
  }

  public function getWines($page = 1, $pageSize = 100)
  {

    $query = "
        {
          productSearch(
            phrase: \"\",
            page_size: $pageSize,
            current_page: $page
          ) {
            total_count
            items {
              product {
                sku
                name
                small_image { url }
                price_range {
                  minimum_price {
                    regular_price { value }
                  }
                }
              }
              productView {
                attributes {
                  name
                  value
                }
              }
            }
          }
        }";

    $response = Http::withHeaders($this->headers())
      ->post($this->url, ['query' => $query])
      ->json();

    $items = $response['data']['productSearch']['items'] ?? [];
    $total = $response['data']['productSearch']['total_count'] ?? 0;

    // Filtrer les produits pour ne garder que ceux qui sont des bouteilles de vin
    $bouteilles_de_vin = array_values(array_filter($items, function ($item) {
      // Vérifier si le produit a un attribut "identite_produit" qui contient "vin", "champagne" ou "porto"
      foreach (($item['productView']['attributes']) as $attribute) {
        if (($attribute['name'] ?? '') === 'identite_produit' &&
          (str_contains(strtolower($attribute['value']), 'vin') ||
            str_contains(strtolower($attribute['value']), 'champagne') ||
            str_contains(strtolower($attribute['value']), 'porto'))
        ) {
          return true;
        }
      }
      return false;
    }));

    return [
      // 'data' => $this->transform($items),
      'bouteilles' => $bouteilles_de_vin,
      'total' => $total
    ];
  }
}

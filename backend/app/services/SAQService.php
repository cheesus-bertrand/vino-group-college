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

    $response = Http::withoutVerifying()
      ->withHeaders($this->headers())
      ->post($this->url, ['query' => $query])
      ->json();

    $items = $response['data']['productSearch']['items'] ?? [];
    $total = $response['data']['productSearch']['total_count'] ?? 0;

    return [
      'bouteilles_de_vin' => $items,
      'total' => $total
    ];
  }

  //  Filtrer les produits pour ne garder que les bouteilles de vin
  public function filtrerVins($bouteillesVin)
  {
    // Filtrer les produits pour ne garder que ceux que les bouteilles de vin
    $bouteillesDeVin = array_values(array_filter($bouteillesVin, function ($item) {
      // Vérifier si le produit a un attribut "identite_produit" qui contient "vin", "champagne", "sauternes" ou "porto"
      foreach (($item['productView']['attributes']) as $attribute) {
        if (($attribute['name'] ?? '') === 'identite_produit' &&
          (str_contains(strtolower($attribute['value']), 'vin') ||
            str_contains(strtolower($attribute['value']), 'champagne') ||
            str_contains(strtolower($attribute['value']), 'sauternes') ||
            str_contains(strtolower($attribute['value']), 'porto'))
        ) {
          return true;
        }
      }
      return false;
    }));
    return $bouteillesDeVin;
  }

  // Formater les attributs des bouteilles de vin filtrées
  public function formatterAttributsVins($bouteilles)
  {
    $vins_attributs = [];

    // Parcourir les bouteilles de vin filtrées et formater leurs attributs pour les stocker dans un tableau associatif
    foreach ($bouteilles as $bouteille) {
      $vin_attributs = [];

      // Parcourir les attributs du productView 
      foreach (($bouteille['productView']['attributes']) as $attribut) {

        switch ($attribut['name']) {
          case 'couleur':
            $vin_attributs["couleur"] = $attribut['value'] ?? null;
            break;

          case 'cepage':
            $vin_attributs["cepage"] =
              is_array($attribut['value'])
              ? implode(', ', $attribut['value']) : $attribut['value'] ?? null;
            break;

          case 'format_contenant_ml':
            $vin_attributs["format"] = $attribut['value'] ?? null;
            break;

          case 'millesime_produit':
            $vin_attributs["annee"] = $attribut['value'] ?? null;
            break;

          case 'pourcentage_alcool_par_volume':
            $vin_attributs["degre_alcool"] = $attribut['value'] ?? null;
            break;

          case 'pays_origine':
            $vin_attributs["pays"] = $attribut['value'] ?? null;
            break;

          case 'region_origine':
            //case 'appellation':
            $vin_attributs["region"] = $attribut['value'] ?? null;
            break;

          case 'taux_sucre':
            $vin_attributs["taux_sucre"] = $attribut['value'] ?? null;
            break;

          default:
            break;
        }
      }

      // formater l'url de l'image du vin
      $image = $bouteille['product']['small_image']['url'] ?? null;

      if ($image) {
        if (str_starts_with($image, '//')) {
          $image = 'https:' . $image;
        } elseif (str_starts_with($image, '/')) {
          $image = 'https://www.saq.com' . $image;
        }
      } else {
        $image = "https://www.saq.com/media/catalog/product/placeholder/default_image.jpg";
      }

      // Ajouter les autres attributs du vin à partir des données du produit
      $vin_attributs["image_url"] =  $image;
      $vin_attributs["saq_id"] = $bouteille['product']['sku'] ?? null;
      $vin_attributs["nom"] = $bouteille['product']['name'] ?? null;
      $vin_attributs["prix"] = $bouteille['product']['price_range']['minimum_price']['regular_price']['value'] ?? null;

      // Ajouter les attributs formatés du vin à la liste finale
      $vins_attributs[] = $vin_attributs;
    }
    return $vins_attributs;
  }
}

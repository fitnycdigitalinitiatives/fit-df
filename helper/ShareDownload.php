<?php

namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\ItemRepresentation;

class ShareDownload extends AbstractHelper
{
  public function __invoke(ItemRepresentation $item)
  {
    $escape = $this->getView()->plugin('escapeHtml');
    $title = $item->displayTitle();
    $url = $escape($item->url());
    $body = 'From the FIT Designer Files:%0D%0A' . $title . '%0D%0A' . $url;
    $subject = $item->displayTitle();
    // if ($media = $item->media()) {
    //   $media = $media[0];
    //   $mediaType = $media->mediaType();
    //   $accessURL = $media->mediaData()['access'];
    //   $iiifEndpoint = $this->getView()->setting('fit_module_aws_iiif_endpoint');
    //   if ((strpos($mediaType, 'image') === 0) && ($accessURL != '') && ($iiifEndpoint != '')) {
    //     $parsed_url = parse_url($accessURL);
    //     $key = ltrim($parsed_url["path"], '/');
    //     $imageEndpoint = $iiifEndpoint . str_replace("/", "%2F", substr($key, 0, -4)) . "/full/max/0/default.jpg";
    //     $filename = "{$title}.jpg";
    //     foreach ($media->value('dcterms:identifier', ['all' => true, 'type' => 'uri']) as $value) {
    //       if ($value->value() == "Reference Code") {
    //         $filename = "{$value->uri()}.jpg";
    //         break;
    //       }
    //     }
    //   }
    // }


    $html = '
        <!-- Social Share  -->
        <ul id="social-share" class="list-inline mb-0 mt-2">
          <li class="list-inline-item">
            <button class="border-0 bg-transparent p-0 clip-button link-light" aria-label="Copy item link" data-clipboard-text="' . $url . '">
              <i class="fas fa-link" title="Copy item link">
              </i>
            </button>
          </li>
          <li class="list-inline-item">
            <a class="link-light" target="_blank" href="mailto:?body=' . $body . '&subject=' . $subject . '">
              <i class="fas fa-envelope" title="Share this item via email">
              </i>
              <span class="sr-only">Share this item via email</span>
            </a>
          </li>
          ';
    $html .= '</ul>';
    return $html;
  }
}

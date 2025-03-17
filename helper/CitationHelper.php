<?php

namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\ItemRepresentation;

class CitationHelper extends AbstractHelper
{
  public function __invoke(ItemRepresentation $item)
  {
    $escape = $this->getView()->plugin('escapeHtml');
    $title = $item->displayTitle();
    $titleCased = $this->titleCase($title);
    $url = $escape($item->url());
    $date = $item->value('dcterms:date');
    $type = "";
    foreach ($item->itemSets() as $itemSet) {
      $itemSetTitle = $itemSet->displayTitle();
      if ($itemSetTitle == "Runway Slides") {
        $type = "Runway Slides";
        break;
      } elseif ($itemSetTitle == "Designer Clippings") {
        $type = "Designer Clippings";
        break;
      }
    }
    switch ($type) {
      case 'Runway Slides':
        $apaPhotographer = '';
        $chicagoPhotographer = '';
        $mlaPhotographer = '';
        foreach ($item->value('dcterms:contributor', ['all' => true]) as $contributor) {
          if (str_contains($contributor->asHtml(), 'Photographer')) {
            $photographer = $contributor;
            $lastName = trim(explode(",", $photographer)[0]);
            $firstAndMiddle = trim(explode(",", $photographer)[1]);
            $firstAndMiddleArray = explode(' ', $firstAndMiddle);
            $initials = '';
            foreach ($firstAndMiddleArray as $initialKey => $part) {
              if ($initialKey == 0) {
                $initials .= strtoupper($part[0]) . '.';
              } else {
                $initials .= ' ' . strtoupper($part[0]) . '.';
              }
            }
            $apaPhotographer .= $lastName . ', ' . $initials . '. ';
            $chicagoPhotographer .= $photographer . '. ';
            $mlaPhotographer .= $photographer . '. ';
            break;
          }
        }
        if ($apaPhotographer) {
          $apa = $apaPhotographer . '(' . $date . '). ' . $title . ' [Runway photo]. <em>The FIT Designer Files</em>. ' . $url;
        } else {
          $apa = $title . ' [Runway photo]. (' . $date . ')' . '. <em>The FIT Designer Files</em>. ' . $url;
        }
        $chicago = $chicagoPhotographer . '<em>' . $titleCased . '</em>. ' . $date . '. Runway photo. The FIT Designer Files. ' . $url;
        $mla = $mlaPhotographer . $titleCased . ' Runway Photo. ' . $date . '. The FIT Designer Files, ' . $url;
        break;

      case 'Designer Clippings':
        $source = $item->value('dcterms:source');
        if ($source) {
          $apa = $title . ' [Fashion clipping]. (' . $date . '). <em>' . $source . '</em>. The FIT Designer Files. ' . $url;
          $chicago = '<em>' . $titleCased . '</em>. Fashion clipping. In <em>' . $source . '</em>. ' . $date . '. The FIT Designer Files. ' . $url;
          $mla = $titleCased . ' Fashion Clipping. <em>' . $source . '</em>, ' . $date . '. The FIT Designer Files, ' . $url;
        } else {
          $apa = $title . ' [Fashion clipping]. (' . $date . ')' . '. <em>The FIT Designer Files</em>. ' . $url;
          $chicago = '<em>' . $titleCased . '</em>. ' . $date . '. Fashion clipping. The FIT Designer Files. ' . $url;
          $mla = $titleCased . ' Fashion Clipping. ' . $date . '. The FIT Designer Files, ' . $url;
        }
        break;

      default:
        $apa = $title . '. (' . $date . ')' . '. <em>The FIT Designer Files</em>. ' . $url;
        $chicago = '<em>' . $titleCased . '</em>. ' . $date . '. The FIT Designer Files. ' . $url;
        $mla = $titleCased . '. ' . $date . '. The FIT Designer Files, ' . $url;
        break;
    }
    return '
        <ul class="nav nav-underline mb-3" id="citationTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link pt-0 active text-light" id="apa-tab" data-bs-toggle="tab" data-bs-target="#apa" type="button" role="tab" aria-controls="apa" aria-selected="true">APA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link pt-0 text-light" id="mla-tab" data-bs-toggle="tab" data-bs-target="#mla" type="button" role="tab" aria-controls="mla" aria-selected="false">MLA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link pt-0 text-light" id="chicago-tab" data-bs-toggle="tab" data-bs-target="#chicago" type="button" role="tab" aria-controls="chicago" aria-selected="false">Chicago/Turabian</button>
          </li>
        </ul>
        <div class="tab-content" id="citationTabContent">
          <div class="tab-pane fade show active" id="apa" role="tabpanel" aria-labelledby="apa-tab">
            <div class="input-group mb-3">
              <div class="form-control font-monospace text-break" id="apaCitation">
              ' . str_replace('..', '.', $apa) . '
              </div>
              <button class="btn btn-dark border clip-button" type="button" id="apa-button" data-clipboard-target="#apaCitation" aria-label="Copy citation to clipboard">
                <i class="fas fa-copy" title="Copy citation to clipboard" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <div class="tab-pane fade" id="mla" role="tabpanel" aria-labelledby="mla-tab">
          <div class="input-group mb-3">
            <div class="form-control font-monospace text-break" id="mlaCitation">
            ' . str_replace('..', '.', $mla) . '
            </div>
            <button class="btn btn-dark border clip-button" type="button" id="mla-button" data-clipboard-target="#mlaCitation" aria-label="Copy citation to clipboard">
              <i class="fas fa-copy" title="Copy citation to clipboard" aria-hidden="true"></i>
            </button>
          </div>
          </div>
          <div class="tab-pane fade" id="chicago" role="tabpanel" aria-labelledby="chicago-tab">
          <div class="input-group mb-3">
            <div class="form-control font-monospace text-break" id="chicagoCitation">
            ' . str_replace('..', '.', $chicago) . '
            </div>
            <button class="btn btn-dark border clip-button" type="button" id="chicago-button" data-clipboard-target="#chicagoCitation" aria-label="Copy citation to clipboard">
              <i class="fas fa-copy" title="Copy citation to clipboard" aria-hidden="true"></i>
            </button>
          </div>
          </div>
        </div>

        ';
  }
  // Converts $title to Title Case, and returns the result.
  protected function titleCase($title)
  {
    $smallwordsarray = array(
      'of',
      'a',
      'the',
      'and',
      'an',
      'or',
      'nor',
      'but',
      'is',
      'if',
      'then',
      'else',
      'when',
      'at',
      'from',
      'by',
      'on',
      'off',
      'for',
      'in',
      'out',
      'over',
      'to',
      'into',
      'with'
    );

    // Split the string into separate words
    $words = explode(' ', $title);

    foreach ($words as $key => $word) {
      // If this word is the first, or it's not one of our small words, capitalise it
      // with ucwords().
      if ($key == 0 or !in_array($word, $smallwordsarray)) {
        $words[$key] = ucwords($word);
      }
    }

    // Join the words back into a string
    $newtitle = implode(' ', $words);

    return $newtitle;
  }
}

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
    $url = $escape($item->url());
    $apa = 'Finley, R. <em>' . $title . '</em>. Fashion Calendar Research Database, ' . $url;
    $chicago = 'Finley, Ruth. "' . $title . '". Fashion Calendar Research Database. ' . $url;
    $mla = 'Finley, Ruth. <em>' . $title . '</em>. Fashion Calendar Research Database, ' . $url;
    return '
        <ul class="nav nav-tabs mb-3" id="citationTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active text-light" id="apa-tab" data-bs-toggle="tab" data-bs-target="#apa" type="button" role="tab" aria-controls="apa" aria-selected="true">APA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-light" id="mla-tab" data-bs-toggle="tab" data-bs-target="#mla" type="button" role="tab" aria-controls="mla" aria-selected="false">MLA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link text-light" id="chicago-tab" data-bs-toggle="tab" data-bs-target="#chicago" type="button" role="tab" aria-controls="chicago" aria-selected="false">Chicago/Turabian</button>
          </li>
        </ul>
        <div class="tab-content" id="citationTabContent">
          <div class="tab-pane fade show active" id="apa" role="tabpanel" aria-labelledby="apa-tab">
            <div class="input-group mb-3">
              <div class="form-control font-monospace text-break" id="apaCitation">
              ' . str_replace('..', '.', $apa) . '
              </div>
              <button class="btn btn-dark clip-button" type="button" id="apa-button" data-clipboard-target="#apaCitation" aria-label="Copy citation to clipboard">
                <i class="fas fa-copy" title="Copy citation to clipboard" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <div class="tab-pane fade" id="mla" role="tabpanel" aria-labelledby="mla-tab">
          <div class="input-group mb-3">
            <div class="form-control font-monospace text-break" id="mlaCitation">
            ' . str_replace('..', '.', $mla) . '
            </div>
            <button class="btn btn-dark clip-button" type="button" id="mla-button" data-clipboard-target="#mlaCitation" aria-label="Copy citation to clipboard">
              <i class="fas fa-copy" title="Copy citation to clipboard" aria-hidden="true"></i>
            </button>
          </div>
          </div>
          <div class="tab-pane fade" id="chicago" role="tabpanel" aria-labelledby="chicago-tab">
          <div class="input-group mb-3">
            <div class="form-control font-monospace text-break" id="chicagoCitation">
            ' . str_replace('..', '.', $chicago) . '
            </div>
            <button class="btn btn-dark clip-button" type="button" id="chicago-button" data-clipboard-target="#chicagoCitation" aria-label="Copy citation to clipboard">
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

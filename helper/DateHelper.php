<?php

namespace OmekaTheme\Helper;

use Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class DateHelper extends AbstractHelper
{
    public function __invoke(AbstractResourceEntityRepresentation $resource)
    {
        if ($date = $resource->value('dcterms:date', ['all' => true])) {
            $thisDate = (string) $date[array_key_last($date)];
            if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $thisDate)) {
                return substr($thisDate, 0, 4);
            } elseif (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $thisDate)) {
                return substr($thisDate, 0, 4);
            } else {
                return $thisDate;
            }
        } else {
            return "undated";
        }
    }
}

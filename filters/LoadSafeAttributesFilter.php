<?php
namespace pastuhov\logstock\filters;

/**
 * Class for filtering yii's messages for loading safe attributes to model.
 *
 * @package pastuhov\logstock\filters
 */
class LoadSafeAttributesFilter extends RegexpFilter
{
    /**
     * @var array
     */
    public $patterns = [
        '/Failed to set unsafe attribute[^\n]+\n/',
    ];

    /**
     * @var array
     */
    public $replacement = [
        '',
    ];
}

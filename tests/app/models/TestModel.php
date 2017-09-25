<?php

namespace pastuhov\logstock\tests\app\models;

use yii\base\Model;

/**
 * Class TestModel
 */
class TestModel extends Model
{
    /**
     * @var mixed
     */
    public $testAttribute;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['testAttribute', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'test' => [
                'testAttribute'
            ],
        ];
    }
}

<?php


class BalanceForm extends CFormModel
{
    public $clan1;
    public $clan2;

    public function rules()
    {
        return array(
            array('clan1, clan2', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'clan1' => 'First Clan',
            'clan2' => 'Second Clan',
        );
    }
} 
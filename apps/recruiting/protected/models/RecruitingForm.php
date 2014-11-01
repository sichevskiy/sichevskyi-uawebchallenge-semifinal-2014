<?php


class RecruitingForm extends CFormModel
{
    public $firstName;
    public $lastName;
    public $phone;
    public $username;

    public function rules()
    {
        return array(
            array('firstName, lastName, phone, username', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array();
    }
} 
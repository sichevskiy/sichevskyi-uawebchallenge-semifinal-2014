<?php


class RecruitingForm extends CFormModel
{
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $username;

    public function rules()
    {
        return array(
            array('username', 'required'),
            array('firstName, lastName, email, phone, ', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'download' => 'Export to CSV',
        );
    }
} 
<?php

use opensrs\backwardcompatibility\dataconversion\mail\CreateMailbox;

/**
 * @group backwardcompatibility
 * @group dataconversion
 * @group publishing
 * @group BC_MailCreateMailbox
 */
class BC_CreateMailboxTest extends PHPUnit_Framework_TestCase
{
    protected $validSubmission = array(
        'data' => array(
            'admin_username' => '',
            'admin_password' => '',
            'admin_domain' => '',
            ),
        );

    /**
     * Valid conversion should complete with no
     * exception thrown.
     *
     *
     * @group validconversion
     */
    public function testValidDataConversion()
    {
        $data = json_decode(json_encode($this->validSubmission));

        $data->data->admin_username = 'phptest'.time();
        $data->data->admin_password = 'password1234';
        $data->data->admin_domain = 'mail.phptest'.time().'.com';

        $shouldMatchNewDataObject = new \stdClass();
        $shouldMatchNewDataObject->attributes = $data->data;

        $ns = new CreateMailbox();
        $newDataObject = $ns->convertDataObject($data);

        $this->assertTrue($newDataObject == $shouldMatchNewDataObject);
    }
}

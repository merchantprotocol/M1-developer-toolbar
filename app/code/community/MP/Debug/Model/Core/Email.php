<?php
/**
 * Class MP_Debug_Model_Core_Email rewrites core/email and overwrites send() to capture any e-mail information.
 *
 * @method string getType()
 * @method string getFromEmail()
 * @method string getFromName()
 * @method string getToEmail()
 * @method string getToName()
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: Pirate MP, 2016
 * @link     https://piratesheep.com
 */

class MP_Debug_Model_Core_Email extends Mage_Core_Model_Email
{
    use MP_Debug_Model_Core_Email_Trait;
}

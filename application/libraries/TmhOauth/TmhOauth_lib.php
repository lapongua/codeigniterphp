<?php if (!defined('BASEPATH')) 
    exit('No direct script access allowed');

class TmhOauth_lib 
{
    /**
     * Constructor
     */
    public function __construct() 
    {
		require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib/tmhOAuth' . EXT ;
	}
}
<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Security extends  CI_Security {
    public function csrf_verify()
    {

        // If it's not a POST request we will set the CSRF cookie
        if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')
        {
            return $this->csrf_set_cookie();
        }

                /**
                 *  mine implementation for application/json
                 */
                $reqHeaders = getallheaders();
                $content_type = $reqHeaders["Content-Type"];

                #it's a json request?
                if(preg_match("/(application\/json)/i",$content_type))
                {
                    #the check the cookie from request
                    $reqCookies = explode("; ",$reqHeaders["Cookie"]);
                    foreach($reqCookies as $c)
                    {
                        if(preg_match("/(".$this->_csrf_cookie_name."\=)/", $c))
                        {
                          $c = explode("=",$c);

                          if($_COOKIE[$this->_csrf_cookie_name] == $c[1])
                          {
                             return $this; 
                          }
                        }
                    }

                }
                //< end

        // Check if URI has been whitelisted from CSRF checks
        if ($exclude_uris = config_item('csrf_exclude_uris'))
        {
            $uri = load_class('URI', 'core');
            foreach ($exclude_uris as $excluded)
            {
                if (preg_match('#^'.$excluded.'$#i'.(UTF8_ENABLED ? 'u' : ''), $uri->uri_string()))
                {
                    return $this;
                }
            }
        }

        // Do the tokens exist in both the _POST and _COOKIE arrays?
        if ( ! isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])
            OR $_POST[$this->_csrf_token_name] !== $_COOKIE[$this->_csrf_cookie_name]) // Do the tokens match?
        {
            $this->csrf_show_error();
        }

        // We kill this since we're done and we don't want to polute the _POST array
        unset($_POST[$this->_csrf_token_name]);

        // Regenerate on every submission?
        if (config_item('csrf_regenerate'))
        {
            // Nothing should last forever
            unset($_COOKIE[$this->_csrf_cookie_name]);
            $this->_csrf_hash = NULL;
        }

        $this->_csrf_set_hash();
        $this->csrf_set_cookie();

        log_message('info', 'CSRF token verified');
        return $this;
    }

}
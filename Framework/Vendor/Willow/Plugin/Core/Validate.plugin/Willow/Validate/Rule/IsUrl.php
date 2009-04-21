<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsUrl extends Willow_Validate_Rule_Matches
{

    public function __construct()
    {
        /**
         * Build the URL PCRE pattern
         * {{{
         */

         /**
          * Scheme (required part)
          */
         $pattern = '(https?|ftp)\:\/\/';

         /**
          * Username:password (optional part)
          */
         $pattern .= '([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?';

         /**
          * hostname or IP (http://x.xx(x) = minimum)
          */
         $pattern .= '([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}';

         /**
          * Optional port
          */
         $pattern .= '(\:[0-9]{2,5})?';

         /**
          * Path (optional)
          */
         $pattern .= '(\/([a-z0-9+\$_-]\.?)+)*\/?';

         /**
          * Query string (optional)
          */
         $pattern .= '(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?';

         /**
          * Anchor (optional)
          */
         $pattern .= '(#[a-z_.-][a-z0-9+\$_.-]*)?';

        /**
         * }}}
         */

        $pattern = sprintf('/^%s$/i', $pattern);

        parent::__construct($pattern);
    }

}

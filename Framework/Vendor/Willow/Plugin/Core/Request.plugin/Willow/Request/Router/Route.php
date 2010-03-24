<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Router_Route implements Willow_Request_Router_Route_Interface
{

    protected $_route;
    protected $_defaultRegex = null;


    protected $_params = array();
    protected $_segments = array();

    public function __construct(Willow_Yaml_Node $route)
    {
        $this->_route = $route;

        foreach ($this->_getSegmentsFromRoute($this->_route->route) as $index => $segment)
        {
            if ($this->_isUriParam($segment) === true)
            {
                $name = substr($segment, 1);
                $this->_params[$index] = $name;
                $this->_segments[$index] = (isset($this->_route->params->$name) ? $this->_route->params->$name : $this->_defaultRegex);
            }
            else
            {
                $this->_segments[$index] = $segment;
            }
        }
    }

    public function match(Willow_Request_Interface $request)
    {
        $params = array('params' => array());

        foreach ($request->segments() as $index => $segment)
        {
            /**
             * Path is longer than a route, it's not a match
             */
            if (!array_key_exists($index, $this->_segments))
            {
                return false;
            }

            $name = null;
            if (isset($this->_params[$index]))
            {
                $name = $this->_params[$index];
            }

            $segment = urldecode($segment);

            /**
             * If it's a static part, must match directly
             */
            if (($name === null) && ($this->_segments[$index] != $segment))
            {
                return false;
            }

            /**
             * If it's a variable with requirement, match a regex. If not - everything matches
             */
            elseif ($this->_segments[$index] !== null)
            {
                if (!preg_match('#^' . $this->_segments[$index] . '$#iu', $segment))
                {
                    return false;
                }
            }

            /**
             * If it's a variable store it's value for later
             */
            if ($name !== null)
            {
                switch ($name)
                {
                    case 'module':
                    case 'section':
                    case 'action':
                        $params[$name] = $segment;
                        break;
                    default:
                        $params['params'][$name] = $segment;
                        break;
                }
            }
        }

        if (isset($this->_route->defaults))
        {
            $params = $params + $this->_route->defaults->toArray();
        }

        /**
         * Check if all map variables have been initialized
         */
        foreach ($this->_params as $param)
        {
            switch ($param)
            {
                case 'module':
                case 'section':
                case 'action':
                    if (!array_key_exists($param, $params))
                    {
                        return false;
                    }
                    break;
                default:
                    if (!array_key_exists($param, $params['params']))
                    {
                        return false;
                    }
                    break;
            }
        }

        return $params;
    }

    protected function _getSegmentsFromRoute($route)
    {
        return preg_split('/[\/,]/', $route);
    }

    protected function _isUriParam($var)
    {
        return (substr($var, 0, 1) === ':');
    }

}

<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Actions factory builder
 */
class Willow_Actions_Factory
{

    /**
     * @var Willow_Request
     */
    protected $_request;

    /**
     * Constructor
     */
    public function __construct(Willow_Request_Interface $request)
    {
        $this->_request = $request;
    }

    /**
     * Get the actions class for the request
     */
    public function getClass()
    {
        if (($dataPath = $this->_getDataPath()) === false)
        {
            if (class_exists($this->_getClassName()) === false)
            {
                $this->_generateClass();
            }
        }
        
        return $this->_getClassName();
    }


    protected $_className;

    protected function _getClassName()
    {
        if ($this->_className === null)
        {
            if (($className = $this->_getClassNameFromDataPath()) === false)
            {
                $className = sprintf(
                    '%s_%s%s%s%s_Actions',
                    $this->_request->getModule(),
                    $this->_request->getSection(),
                    $this->_request->getAction(),
                    $this->_request->getProtocol(),
                    $this->_request->getMethod()
                );
            }

            $this->_className = $className;
        }

        return $this->_className;
    }

    protected function _getClassNameFromDataPath()
    {
        if (($dataPath = $this->_getDataPath()) === false)
        {
            return false;
        }

        $dataPath = explode(':', $dataPath);

        /**
         * Remove "Modules"
         */
        unset($dataPath[0]);

        /**
         * Remove "Actions"
         */
        unset($dataPath[2]);

        /**
         * Build class name
         */
        return sprintf(
            '%s_%s_Actions',
            array_shift($dataPath),
            implode('', $dataPath)
        );
    }


    protected $_dataPath = null;

    /**
     * ModuleName_SectionActionHttpGet_Actions
     * ModuleName_SectionActionHttp_Actions
     * ModuleName_SectionActionGet_Actions
     * ModuleName_SectionAction_Actions
     *
     *      Modules.{Module}.Actions.{Section}{Action}{RequestProtocol}{RequestMethod}
     *      Modules.{Module}.Actions.{Section}{Action}{RequestProtocol}
     *      Modules.{Module}.Actions.{Section}{Action}{RequestMethod}
     *      Modules.{Module}.Actions.{Section}{Action}
     */
    protected function _getDataPath()
    {
        if ($this->_dataPath === null)
        {
            /**
             * Attempt to locate first:
             *   Modules.{Module}.Actions.{Section}{Action}{RequestProtocol}{RequestMethod}
             */
            try
            {
                $dataPath = array(
                    'Modules',
                    $this->_request->getModule(),
                    'Actions',
                    $this->_request->getSection() . $this->_request->getAction() . 
                        $this->_request->getProtocol() . $this->_request->getMethod(),
                );

                $dataPath = implode(':', $dataPath);

                $this->_checkDataPath($dataPath);
            }

            /**
             * Failed! Attempt to locate:
             *   Modules.{Module}.Actions.{Section}{Action}{RequestProtocol}
             */
            catch (Willow_DataPath_Exception $e)
            {
                try
                {
                    $dataPath = array(
                        'Modules',
                        $this->_request->getModule(),
                        'Actions',
                        $this->_request->getSection() . $this->_request->getAction() .
                            $this->_request->getProtocol(),
                    );

                    $dataPath = implode(':', $dataPath);

                    $this->_checkDataPath($dataPath);
                }

                /**
                 * Failed! Attempt to locate:
                 *   Modules.{Module}.Actions.{Section}{Action}{RequestMethod}
                 */
                catch (Willow_DataPath_Exception $e)
                {
                    try
                    {
                        $dataPath = array(
                            'Modules',
                            $this->_request->getModule(),
                            'Actions',
                            $this->_request->getSection() . $this->_request->getAction() .
                                $this->_request->getMethod(),
                        );

                        $dataPath = implode(':', $dataPath);

                        $this->_checkDataPath($dataPath);
                    }

                    /**
                     * Failed! Attempt to locate:
                     *   Modules.{Module}.Actions.{Section}{Action}
                     */
                    catch (Willow_DataPath_Exception $e)
                    {
                        try
                        {
                            $dataPath = array(
                                'Modules',
                                $this->_request->getModule(),
                                'Actions',
                                $this->_request->getSection() . $this->_request->getAction(),
                            );

                            $dataPath = implode(':', $dataPath);

                            $this->_checkDataPath($dataPath);
                        }

                        /**
                         * Failed! Nothing left to try.
                         */
                        catch (Willow_DataPath_Exception $e)
                        {
                            $dataPath = false;
                        }
                    }
                }
            }

            $this->_dataPath = $dataPath;
        }

        return $this->_dataPath;
    }

    protected function _checkDataPath($path)
    {
        Willow_Loader::loadFile('App:' . $path);
    }

    protected function _generateClass()
    {
        $className = $this->_getClassName();

        try
        {
            $dataPath = array(
                'Modules',
                $this->_request->getModule(),
                'Actions',
            );

            $dataPath = implode(':', $dataPath);

            $this->_checkDataPath($dataPath);

            $parent = sprintf('%s_Actions', $this->_request->getModule());
        }
        catch (Willow_DataPath_Exception $e)
        {
            $parent = 'Willow_Actions_Abstract';

            if ($this->_request->getProtocol())
            {
                $parent = sprintf('Willow_%s_Actions', $this->_request->getProtocol());
            }
        }

        $class = sprintf(
            'class %s extends %s' .
            '{' .
                'public function doAction()' .
                '{' .
                '}' .
            '}',
            $className,
            $parent
        );

        eval($class);

        return $className;
    }

}

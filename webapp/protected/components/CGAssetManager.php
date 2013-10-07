<?php
/**
 * Author: Pavel Kostenko <poul.kg@gmail.com>
 * Date: 06.10.13
 * Time: 23:49
 */

class CGAssetManager extends CAssetManager
{
    /**
     * @var string base web accessible path for storing private files
     */
    private $_basePath;
    /**
     * @var string base URL for accessing the publishing directory.
     */
    private $_baseUrl;
    /**
     * @var array published assets
     */
    private $_published=array();

    /**
     * Sets the root directory storing published asset files.
     * @param string $value the root directory storing published asset files
     * @throws CException if the base path is invalid
     */
    public function setBasePath($value)
    {
        if(0 !== strpos('gs://', $value) && is_dir($value) && is_writable($value))
            $this->_basePath=$value;
        else
            throw new CException(Yii::t('yii','CAssetManager.basePath "{path}" is invalid. Please make sure the directory exists and is writable by the Web server process.',
                array('{path}'=>$value)));
    }
} 
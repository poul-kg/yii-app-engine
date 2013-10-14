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
        if(0 !== strpos('gs://', $value))
            $this->_basePath=$value;
        elseif(($basePath=realpath($value))!==false && is_dir($basePath) && is_writable($basePath))
            $this->_basePath=$value;
        else
            throw new CException(Yii::t('yii','CAssetManager.basePath "{path}" is invalid. Please make sure the directory exists and is writable by the Web server process.',
                array('{path}'=>$value)));
    }

    /**
     * @throws Exception
     * @return string the root directory storing the published asset files. Defaults to 'WebRoot/assets'.
     */
    public function getBasePath()
    {
        if(is_null($this->_basePath))
        {
            if (preg_match('/Google App Engine/', getenv("SERVER_SOFTWARE"))) {
                // there is not way to detect base path of asset manager from Google App Engine
                // the only way is to specify it in configuration file
                throw new Exception(__CLASS__ . ' was not configured with proper basePath');
            } else {
                $request=Yii::app()->getRequest();
                $this->setBasePath(dirname($request->getScriptFile()).DIRECTORY_SEPARATOR.self::DEFAULT_BASEPATH);
            }
        }
        return $this->_basePath;
    }

    /**
     * Publishes a file or a directory.
     * This method will copy the specified asset to a web accessible directory
     * and return the URL for accessing the published asset.
     * <ul>
     * <li>If the asset is a file, its file modification time will be checked
     * to avoid unnecessary file copying;</li>
     * <li>If the asset is a directory, all files and subdirectories under it will
     * be published recursively. Note, in case $forceCopy is false the method only checks the
     * existence of the target directory to avoid repetitive copying.</li>
     * </ul>
     *
     * Note: On rare scenario, a race condition can develop that will lead to a
     * one-time-manifestation of a non-critical problem in the creation of the directory
     * that holds the published assets. This problem can be avoided altogether by 'requesting'
     * in advance all the resources that are supposed to trigger a 'publish()' call, and doing
     * that in the application deployment phase, before system goes live. See more in the following
     * discussion: http://code.google.com/p/yii/issues/detail?id=2579
     *
     * @param string $path the asset (file or directory) to be published
     * @param boolean $hashByName whether the published directory should be named as the hashed basename.
     * If false, the name will be the hash taken from dirname of the path being published and path mtime.
     * Defaults to false. Set true if the path being published is shared among
     * different extensions.
     * @param integer $level level of recursive copying when the asset is a directory.
     * Level -1 means publishing all subdirectories and files;
     * Level 0 means publishing only the files DIRECTLY under the directory;
     * level N means copying those directories that are within N levels.
     * @param boolean $forceCopy whether we should copy the asset file or directory even if it is already
     * published before. In case of publishing a directory old files will not be removed.
     * This parameter is set true mainly during development stage when the original
     * assets are being constantly changed. The consequence is that the performance is degraded,
     * which is not a concern during development, however. Default value of this parameter is null meaning
     * that it's value is controlled by {@link $forceCopy} class property. This parameter has been available
     * since version 1.1.2. Default value has been changed since 1.1.11.
     * Note that this parameter cannot be true when {@link $linkAssets} property has true value too. Otherwise
     * an exception would be thrown. Using this parameter with {@link $linkAssets} property at the same time
     * is illogical because both of them are solving similar tasks but in a different ways. Please refer
     * to the {@link $linkAssets} documentation for more details.
     * @return string an absolute URL to the published asset
     * @throws CException if the asset to be published does not exist.
     */
    public function publish($path,$hashByName=false,$level=-1,$forceCopy=null)
    {
        if($forceCopy===null)
            $forceCopy=$this->forceCopy;
        if($forceCopy && $this->linkAssets)
            throw new CException(Yii::t('yii','The "forceCopy" and "linkAssets" cannot be both true.'));
        if(isset($this->_published[$path]))
            return $this->_published[$path];
        elseif(($src=realpath($path))!==false)
        {
            $dir=$this->generatePath($src,$hashByName);
            $dstDir=$this->getBasePath().DIRECTORY_SEPARATOR.$dir;
            if(is_file($src))
            {
                $fileName=basename($src);
                $dstFile=$dstDir.DIRECTORY_SEPARATOR.$fileName;

                if(!$this->isDstDir($dstDir))
                {
                    mkdir($dstDir,$this->newDirMode,true);
                    @chmod($dstDir,$this->newDirMode);
                }

                if($this->linkAssets && !is_file($dstFile)) symlink($src,$dstFile);
                elseif(@filemtime($dstFile)<@filemtime($src))
                {
                    copy($src,$dstFile);
//                    @chmod($dstFile,$this->newFileMode);
                }

                return $this->_published[$path]=$this->getBaseUrl()."/$dir/$fileName";
            }
            elseif(is_dir($src))
            {
                if($this->linkAssets && !$this->isDstDir($dstDir))
                {
                    symlink($src,$dstDir);
                }
                elseif(!$this->isDstDir($dstDir) || $forceCopy)
                {
                    CGFileHelper::copyDirectory($src,$dstDir,array(
                        'exclude'=>$this->excludeFiles,
                        'level'=>$level,
                        'newDirMode'=>$this->newDirMode,
                        'newFileMode'=>$this->newFileMode,
                    ));
                }

                return $this->_published[$path]=$this->getBaseUrl().'/'.$dir;
            }
        }
        throw new CException(Yii::t('yii','The asset "{asset}" to be published does not exist.',
            array('{asset}'=>$path)));
    }


    /**
     * This method should be used here only to test dstDirectory, not srcDirectory
     * This method was added to be able to run AssetManager on local development Google App Engine version
     * which has one bug related to is_dir() function
     * @param $dstDir
     * @return bool
     */
    public function isDstDir($dstDir)
    {
        if (false === strpos($dstDir, 'gs://')) {
            // if not Google Cloud Storage, then use native is_dir()
            return is_dir($dstDir);
        } else {
            // Google Cloud Storage Path
            // Local development version of Google App Engine 1.8.5 has a bug which crashes the whole app when
            // is_dir() function is used, so we always return true here
            return true;
        }
    }
} 
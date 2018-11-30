<?php

namespace app\libs;

use yii\base\Configurable;
use weyii\base\traits\ObjectTrait;
use League\Flysystem\Config;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Adapter\AbstractAdapter;

/**
 * Class FDFS.
 */
class FDFS extends AbstractAdapter implements Configurable
{

    use ObjectTrait;

    const VISIBILITY_PUBLIC = 'public';

    const VISIBILITY_PRIVATE = 'private';

    /** 
     * @var string 上传url
     */
    public $baseUrl;

    /**
     * @var string 加密盐salt
     */
    public $salt;

    /**
     * @var string 上传文件名称
     */
    public $fileField;

    /**
     * @var string path prefix
     */
    public $pathPrefix;

    /**
     * @var string
     */
    protected $pathSeparator = '/';

    /**
     * 上传文件流到fdfs服务器
     * @param @fileList 上传文件列表 
     * @param @postParam 上传参数
     *
     * @author YXH
     * @date 2018/05/15
     *
     * @return mixed 
     */
    public function uploadFile($fileList, $format = 'jpg')
    {
        $count = count($fileList);
        if ($count < 1) {
            // 无文件
            return false;
        }

        if ($count == 1) {
            // 单文件
            $file = new \CURLFile(realpath($fileList[0]));
            $data = [$this->fileField => $file];
        } else {
            $file = $data = [];
            for ($i = 0; $i < $count; ++$i) {
                // curl 多文件上传
                $file = new \CURLFile(realpath($fileList[$i]));
                $data[$this->fileField.'['.$i.']'] = $file;
            }
        }

        // 13位时间戳
        list($s1, $s2) = explode(' ', microtime());  
        $timestamp = (float) sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        // 加密token
        $token = md5($this->salt.$timestamp);
        $postParam = [
            'time' => $timestamp,
            'token' => $token,
            'file_field' => $this->fileField,
            'format' => $format,
        ];
        $param = array_merge($postParam, $data);
        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            return curl_error($ch);
        }

        curl_close($ch);
        if (!$response) {
            return false;
        }

        return $response;
    }

    /**
     * Set the path prefix.
     *
     * @param string $prefix
     */
    public function setPathPrefix($prefix)
    {
        $prefix = (string) $prefix;

        if ($prefix === '') {
            $this->pathPrefix = null;

            return;
        }

        $this->pathPrefix = rtrim($prefix, '\\/').$this->pathSeparator;
    }

    /**
     * Get the path prefix.
     *
     * @return string path prefix
     */
    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    /**
     * Prefix a path.
     *
     * @param string $path
     *
     * @return string prefixed path
     */
    public function applyPathPrefix($path)
    {
        return $this->getPathPrefix().ltrim($path, '\\/');
    }

    /**
     * Remove a path prefix.
     *
     * @param string $path
     *
     * @return string path without the prefix
     */
    public function removePathPrefix($path)
    {
        return substr($path, strlen($this->getPathPrefix()));
    }

    /**
     * Write a new file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function write($path, $contents, Config $config)
    {
    }

    /**
     * Write a new file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function writeStream($path, $resource, Config $config)
    {
    }

    /**
     * Update a file.
     *
     * @param string $path
     * @param string $contents
     * @param Config $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function update($path, $contents, Config $config)
    {
    }

    /**
     * Update a file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object
     *
     * @return array|false false on failure file meta data on success
     */
    public function updateStream($path, $resource, Config $config)
    {
    }

    /**
     * Rename a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function rename($path, $newpath)
    {
    }

    /**
     * Copy a file.
     *
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($path, $newpath)
    {
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @return bool
     */
    public function delete($path)
    {
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($dirname)
    {
    }

    /**
     * Create a directory.
     *
     * @param string $dirname directory name
     * @param Config $config
     *
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
    }

    /**
     * Set the visibility for a file.
     *
     * @param string $path
     * @param string $visibility
     *
     * @return array|false file meta data
     */
    public function setVisibility($path, $visibility)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function has($path)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function read($path)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function readStream($path)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function listContents($directory = '', $recursive = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($path)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getSize($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getMimetype($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp($path)
    {
        return $this->getMetadata($path);
    }

    /**
     * {@inheritdoc}
     */
    public function getVisibility($path)
    {
        return [
            'visibility' => $this->isPrivate ? AdapterInterface::VISIBILITY_PRIVATE : AdapterInterface::VISIBILITY_PUBLIC,
        ];
    }
}

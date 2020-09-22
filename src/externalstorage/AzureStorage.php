<?php

/**
 * Imager plugin for Craft CMS 3.x
 *
 * Image transforms gone wild
 *
 * @link      https://github.com/dande-sun/Imager-Craft
 * @copyright Copyright (c) 2020 Dande Sun
 */

namespace aelvan\imager\externalstorage;

use Craft;
use craft\helpers\FileHelper;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use aelvan\imager\models\Settings;
use aelvan\imager\models\ConfigModel;
use aelvan\imager\services\ImagerService;

class AzureStorage implements ImagerStorageInterface
{
  public static function upload(string $file, string $uri, bool $isFinal, array $settings)
  {
    /** @var ConfigModel $settings */
    $config = ImagerService::getConfig();
    $defaultSettings = new Settings();
    $settings = array_merge($defaultSettings['storageConfig']['abs'],$settings);
    $connectionString = sprintf('DefaultEndpointsProtocol=%s;AccountName=%s;AccountKey=%s', $settings['useHttps']?'https':'http', $settings['accountName'], $settings['accountKey']);
    // $connectionString = 'UseDevelopmentStorage=true';
    $blobClient = BlobRestProxy::createBlobService($connectionString);

    if (!file_exists($file)) {
      Craft::error("ERROR AzureStorage: file is not exists.", __METHOD__);
      return false;
    }

    // $contentType = 'text/plain; charset=UTF-8';
    $options = new CreateBlockBlobOptions();
    $options->setContentType(FileHelper::getMimeType($file));

    try {
      //Upload blob
      $blobClient->createBlockBlob($settings['containerName'], str_replace('\\', '/', $uri), fopen($file, "r"), $options);
    } catch (ServiceException $e) {
      Craft::error("ERROR AzureStorage: " . $e->getCode() . " " . $e->getMessage() . PHP_EOL, __METHOD__);
      return false;
    }
    return true;
  }
}

<?php

namespace Algolia\AlgoliaSearch\Helper;

use AlgoliaSearch\Client;
use Magento\Framework\Message\ManagerInterface;

class AlgoliaHelper
{
    /** @var Client */
    protected $client;
    protected $config;
    protected $messageManager;

    public function __construct(ConfigHelper $configHelper, ManagerInterface $messageManager)
    {
        $this->messageManager = $messageManager;
        $this->config = $configHelper;
        $this->resetCredentialsFromConfig();
    }

    public function resetCredentialsFromConfig()
    {
        if ($this->config->getApplicationID() && $this->config->getAPIKey()){
            $this->client = new Client($this->config->getApplicationID(), $this->config->getAPIKey());
        }
    }

    public function getIndex($name)
    {
        return $this->client->initIndex($name);
    }

    public function listIndexes()
    {
        return $this->client->listIndexes();
    }

    public function query($index_name, $q, $params)
    {
        return $this->client->initIndex($index_name)->search($q, $params);
    }

    public function setSettings($indexName, $settings)
    {
        $index = $this->getIndex($indexName);

        $index->setSettings($settings);
    }

    public function deleteIndex($index_name)
    {
        $this->client->deleteIndex($index_name);
    }

    public function deleteObjects($ids, $index_name)
    {
        $index = $this->getIndex($index_name);

        $index->deleteObjects($ids);
    }

    public function moveIndex($index_name_tmp, $index_name)
    {
        $this->client->moveIndex($index_name_tmp, $index_name);
    }

    public function mergeSettings($index_name, $settings)
    {
        $onlineSettings = array();

        try
        {
            $onlineSettings = $this->getIndex($index_name)->getSettings();
        }
        catch(\Exception $e)
        {
        }

        $removes = array('slaves');

        foreach ($removes as $remove)
            if (isset($onlineSettings[$remove]))
                unset($onlineSettings[$remove]);

        foreach ($settings as $key => $value)
            $onlineSettings[$key] = $value;

        return $onlineSettings;
    }

    public function handleTooBigRecords(&$objects, $index_name)
    {
        $long_attributes = array('description', 'short_description', 'meta_description', 'content');

        $good_size = true;

        $ids = array();

        foreach ($objects as $key => &$object)
        {
            $size = mb_strlen(json_encode($object));

            if ($size > 20000)
            {
                foreach ($long_attributes as $attribute)
                {
                    if (isset($object[$attribute]))
                    {
                        unset($object[$attribute]);
                        $ids[$index_name.' objectID('.$object['objectID'].')'] = true;
                        $good_size = false;
                    }

                }

                $size = mb_strlen(json_encode($object));

                if ($size > 20000)
                {
                    unset($objects[$key]);
                }
            }
        }

        if (count($objects) <= 0)
            return;

        if ($good_size === false)
        {
            $this->messageManager->addError('Algolia reindexing : You have some records ('.implode(',', array_keys($ids)).') that are too big. They have either been truncated or skipped');
        }
    }

    public function addObjects($objects, $index_name)
    {
        $this->handleTooBigRecords($objects, $index_name);

        $index = $this->getIndex($index_name);

        if ($this->config->isPartialUpdateEnabled())
            $index->partialUpdateObjects($objects);
        else
            $index->addObjects($objects);
    }
}
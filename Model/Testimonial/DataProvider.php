<?php
/**
 * Copyright © Ashokdubariya. All rights reserved.
 */

declare(strict_types=1);

namespace Ashokdubariya\Testimonial\Model\Testimonial;

use Ashokdubariya\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Data provider for testimonial form
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    private array $loadedData = [];

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $testimonial) {
            $this->loadedData[$testimonial->getId()] = $testimonial->getData();
        }

        $data = $this->dataPersistor->get('ashokdubariya_testimonial');
        if (!empty($data)) {
            $testimonial = $this->collection->getNewEmptyItem();
            $testimonial->setData($data);
            $this->loadedData[$testimonial->getId()] = $testimonial->getData();
            $this->dataPersistor->clear('ashokdubariya_testimonial');
        }

        return $this->loadedData;
    }
}

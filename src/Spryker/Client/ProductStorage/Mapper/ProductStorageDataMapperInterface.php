<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Mapper;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;

interface ProductStorageDataMapperInterface
{
    /**
     * @param string $locale
     * @param array $productStorageData
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer|null $productOfferStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData($locale, array $productStorageData, array $selectedAttributes = [], ?ProductOfferStorageCriteriaTransfer $productOfferStorageCriteriaTransfer = null);
}

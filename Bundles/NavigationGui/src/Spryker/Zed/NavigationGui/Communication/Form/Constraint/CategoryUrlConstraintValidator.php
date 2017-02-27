<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use InvalidArgumentException;
use Spryker\Zed\NavigationGui\Communication\Form\NavigationNodeLocalizedAttributesFormType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CategoryUrlConstraintValidator extends ConstraintValidator
{

    /**
     * @param mixed $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CategoryUrlConstraint) {
            throw new UnexpectedTypeException($constraint, CategoryUrlConstraint::class);
        }

        if (!$value instanceof NavigationNodeLocalizedAttributesTransfer) {
            throw new InvalidArgumentException(sprintf(
                'Expected instance of %s, got %s',
                NavigationNodeLocalizedAttributesTransfer::class,
                $value
            ));
        }

        if (!$value->getCategoryUrl()) {
            return;
        }

        $urlTransfer = $this->findUrl($value, $constraint);

        if (!$urlTransfer || !$urlTransfer->getFkResourceCategorynode() || $urlTransfer->getFkLocale() != $value->getFkLocale()) {
            $this->context
                ->buildViolation('This value is not a valid category URL.')
                ->atPath(NavigationNodeLocalizedAttributesFormType::FIELD_CATEGORY_URL)
                ->addViolation();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $value
     * @param \Spryker\Zed\NavigationGui\Communication\Form\Constraint\CategoryUrlConstraint $constraint
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    protected function findUrl(NavigationNodeLocalizedAttributesTransfer $value, CategoryUrlConstraint $constraint)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setUrl($value->getCategoryUrl());

        return $constraint->getUrlFacade()->findUrl($urlTransfer);
    }

}

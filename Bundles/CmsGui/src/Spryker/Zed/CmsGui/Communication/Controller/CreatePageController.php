<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\CmsGui\CmsGuiConfig;
use Spryker\Zed\CmsGui\Communication\Form\Page\CmsPageFormType;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class CreatePageController extends AbstractController
{

    const ERROR_MESSAGE_INVALID_DATA_PROVIDED = 'Invalid data provided.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $this->getFactory()
            ->getCmsFacade()
            ->syncTemplate(CmsGuiConfig::CMS_FOLDER_PATH);

        $pageTabs = $this->getFactory()->createPageTabs();

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsPageFormTypeDataProvider = $this->getFactory()
            ->createCmsPageFormTypeDataProvider();

        $pageForm = $this->getFactory()
            ->createCmsPageForm($cmsPageFormTypeDataProvider)
            ->handleRequest($request);

        if ($pageForm->isSubmitted()) {
            if ($pageForm->isValid()) {
                $redirectUrl = $this->createPage($pageForm);
                if (!empty($redirectUrl)) {
                    return $this->redirectResponse($redirectUrl);
                }
            } else {
                $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
            }
        }

        return [
            'pageTabs' => $pageTabs->createView(),
            'pageForm' => $pageForm->createView(),
            'availableLocales' => $availableLocales,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $pageForm
     *
     * @return string|null
     */
    protected function createPage(FormInterface $pageForm)
    {
        try {
            $idCmsPage = $this->getFactory()
                ->getCmsFacade()
                ->createPage($pageForm->getData());

            $this->addSuccessMessage('Page successfully created.');

            return Url::generate(
                '/cms-gui/create-glossary/index',
                [CreateGlossaryController::URL_PARAM_ID_CMS_PAGE => $idCmsPage]
            )
                ->build();
        } catch (TemplateFileNotFoundException $exception) {
            $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);

            $error = $this->createTemplateErrorForm();
            $pageForm->get(CmsPageFormType::FIELD_FK_TEMPLATE)->addError($error);
        }
    }

    /**
     * @return \Symfony\Component\Form\FormError
     */
    protected function createTemplateErrorForm()
    {
        return new FormError("Selected template doesn't exist anymore");
    }

}

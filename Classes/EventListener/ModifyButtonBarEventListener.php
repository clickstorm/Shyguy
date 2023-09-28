<?php

namespace WapplerSystems\Shyguy\EventListener;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\Components\ModifyButtonBarEvent;
use TYPO3\CMS\Core\Page\PageRenderer;

/**
 * Adds visualisation and control over soft hyphens in content elements.
 *
 * Class ShyGuyHook
 * @package WapplerSystems\Shyguy\Hooks
 */
class ModifyButtonBarEventListener
{
    /**
     * @param array $params
     * @param $buttonBar
     * @return array
     */
    public function __invoke(ModifyButtonBarEvent $event): void
    {
        $buttons = $event->getButtons();
        $buttonBar = $event->getButtonBar();
        $saveButton = $buttons[ButtonBar::BUTTON_POSITION_LEFT][2][0] ?? null;

        // load js file whenever button is added
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addJsFile('EXT:shyguy/Resources/Public/JavaScript/Shyguy.min.js', 'text/javascript');

        if ($saveButton instanceof InputButton && $saveButton->getName() === '_savedok') {
            $iconFactory = GeneralUtility::makeInstance(IconFactory::class);

            $insertSoftHyphen = $buttonBar->makeLinkButton()
                ->setHref('#insertSoftHyphen')
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:shyguy/Resources/Private/Language/locallang.xlf:set_hyphen'))
                ->setIcon($iconFactory->getIcon('insert-soft-hyphen', Icon::SIZE_SMALL))
                ->setShowLabelText(true);

            $pos = max(array_keys($buttons[ButtonBar::BUTTON_POSITION_LEFT])) + 1;
            $buttons[ButtonBar::BUTTON_POSITION_LEFT][$pos][] = $insertSoftHyphen;

            $event->setButtons($buttons);
        }
    }

    /**
     * Returns the language service
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}

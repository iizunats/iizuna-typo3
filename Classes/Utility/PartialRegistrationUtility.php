<?php

namespace iizunats\iizuna\Utility;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\TemplateView;



/**
 * Class PartialRegistrationUtility
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Utility
 */
class PartialRegistrationUtility implements SingletonInterface {

	/**
	 * Contains all registered (an therefore allowed) partials for extensions
	 *
	 * @var array
	 */
	private $registered = [];


	/**
	 * Adds a Partial file for a given extension to the allowed partials.
	 * Only partials inside of the private $registered Array are allowed for display as api
	 *
	 * @param string $extension The extension name (preferable in lower_case_underscored)
	 * @param string $partial The Path to the Partial (excluding the path to the partial folder itself)
	 */
	public function register (string $extension, string $partial) {
		$this->registered[] = [$extension, $partial];
	}


	/**
	 * Checks whether a partial for the given extension and partial name was registered
	 *
	 * @param string $extensionName
	 * @param string $partialName
	 *
	 * @return bool
	 */
	public function isRegistered (string $extensionName, string $partialName) {
		foreach ($this->registered as list($registeredExtension, $registeredPartial)) {
			if ($registeredExtension === $extensionName && $partialName === $registeredPartial) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Returns the contents of the given partial of the given extension.
	 * Variables inside of the partial itself are returned as variable names without injection data.
	 * ViewHelpers are getting executed (but keep in mind that conditional ViewHelpers are not gonna work, because the variables are not getting filled!)
	 *
	 * @param string $extension The extension name (preferable in lower_case_underscored)
	 * @param string $partial The Path to the Partial (excluding the path to the partial folder itself)
	 *
	 * @return mixed|string
	 */
	public function getPartial (string $extension, string $partial) {
		$view = $this->getPreparedViewForExtension($extension);
		$partial = $this->trimPartialPath($partial);
		$extension = GeneralUtility::camelCaseToLowerCaseUnderscored($extension);
		$absolutePartialPath = GeneralUtility::getFileAbsFileName("EXT:$extension/Resources/Private/Partials/$partial.html");

		return $this->getPartialWithoutVariableContent($view, $absolutePartialPath);
	}


	/**
	 * Removes leading and trailing slashes and also removes a potentially appended html file suffix
	 *
	 * @param string $partial
	 *
	 * @return string
	 */
	private function trimPartialPath (string $partial): string {
		return preg_replace('/\.html$/', '', trim($partial, '/'));
	}


	/**
	 * Returns the content of the requested partial but without any interpolated variables.
	 * This is archived by preg_replacing the {variables} with °variables| which don't have any functionality.
	 * After the fluid templates was successfully rendered (with these dummy variables) the dummy varibales are
	 * transformed into javascript template variables (e.g. ${variables})
	 *
	 * @param \TYPO3\CMS\Fluid\View\TemplateView $view
	 * @param string $absolutePartialPath
	 *
	 * @return string
	 */
	private function getPartialWithoutVariableContent (TemplateView $view, string $absolutePartialPath): string {
		$partialContent = file_get_contents($absolutePartialPath);
		$partialContentWithEscapedVariables = preg_replace('/{([^}]+)}/', '°$1|', $partialContent);

		return $this->createTemporaryFileWith($partialContentWithEscapedVariables, function ($tmpFilePath) use ($view) {
			$view->setTemplatePathAndFilename($tmpFilePath);

			return preg_replace('/°([^|]+)|/', '\${$1}', $view->render());
		});
	}


	/**
	 * Creates a temporary file which is only present while the callback is running.
	 * The file is automatically being removed after that
	 *
	 * @param string $temporaryFileContent
	 * @param \Closure $callable callable function THAT HAS TO RETURN A STRING!
	 *
	 * @return mixed
	 */
	private function createTemporaryFileWith (string $temporaryFileContent, \Closure $callable): string {
		$tempFile = tmpfile();
		fwrite($tempFile, $temporaryFileContent);
		$path = stream_get_meta_data($tempFile)['uri'];
		$ret = $callable($path);
		unlink($path);

		return $ret;
	}


	/**
	 * Creates a view for the given extension.
	 * Does not load any template at this point!
	 *
	 * @param string $extension
	 *
	 * @return \TYPO3\CMS\Fluid\View\TemplateView
	 */
	private function getPreparedViewForExtension (string $extension) {
		/** @var TemplateView $view */
		$view = GeneralUtility::makeInstance(TemplateView::class);
		$view->setPartialRootPaths(["EXT:$extension/Resources/Private/Partials"]);
		$view->setLayoutRootPaths(["EXT:$extension/Resources/Private/Layouts"]);
		$view->setTemplateRootPaths(["EXT:$extension/Resources/Private/Templates"]);

		return $view;
	}
}
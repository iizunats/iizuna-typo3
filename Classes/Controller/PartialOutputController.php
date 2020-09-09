<?php

namespace Iizunats\Iizuna\Controller;

use Iizunats\Iizuna\View\PartialOutput\Render;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Fluid\View\TemplateView;



/**
 * Class PartialOutputController
 *
 * This controller is used to bootstrap the rendering process of the partial.
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\Iizuna\Controller
 */
class PartialOutputController extends ActionController {

	/**
	 * partialCache
	 *
	 * @var \Iizunats\Iizuna\Utility\PartialRegistrationUtility
	 * @inject
	 */
	protected $partialRegistrationUtility = null;


	public function renderAction () {
		$r = GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
		$rr = parse_url($r);
		$path = trim($rr['path'], '/');
		$partialContent = $this->getPartialForPath($path);

		return $partialContent;
	}


	/**
	 * Tries to get a partial from the database based by the passed path
	 *
	 * @param string $path
	 *
	 * @return null|string
	 */
	private function getPartialForPath (string $path) {
		list($url, $arguments) = explode('?', $path);
		$items = explode('/', $url);
		$startIndex = array_search('iizuna', $items);
		$extension = '';
		for ($i = -1; $i <= $startIndex; $i++) {
			$extension = array_shift($items);
		}
		$partial = implode('/', $items);

		if ($this->partialRegistrationUtility->isRegistered($extension, $partial)) {
			$configuration = $this->partialRegistrationUtility->getConfiguration($extension, $partial);
			$passingArguments = [];

			if (is_array($configuration) || is_object($configuration))
			{
			foreach ($configuration[2] as $allowedArguments) {
				if (isset($_GET[$allowedArguments])) {
					$passingArguments[$allowedArguments] = strip_tags(urldecode($_GET[$allowedArguments]));
				}
			}
		}

			return $this->getPartial($extension, $partial, $passingArguments);
		}

		return null;
	}


	/**
	 * Returns the contents of the given partial of the given extension.
	 * Variables inside of the partial itself are returned as variable names without injection data.
	 * ViewHelpers are getting executed (but keep in mind that conditional ViewHelpers are not gonna work, because the variables are not getting filled!)
	 *
	 * @param string $extension The extension name (preferable in lower_case_underscored)
	 * @param string $partial The Path to the Partial (excluding the path to the partial folder itself)
	 * @param array $passingArguments
	 *
	 * @return mixed|string
	 */
	public function getPartial (string $extension, string $partial, array $passingArguments = []) {
		$view = $this->getPreparedViewForExtension($extension);
		$view->assignMultiple($passingArguments);
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
		$partialContent = GeneralUtility::getURL($absolutePartialPath);
		$partialContentWithEscapedVariables = preg_replace('/{([^}=:]+)}/', 'IIZUSTART$1-IIZUEND', $partialContent);

		return $this->createTemporaryFileWith($partialContentWithEscapedVariables, function ($tmpFilePath) use ($view) {
			$view->setTemplatePathAndFilename($tmpFilePath);

			return preg_replace('/IIZUSTART([^-]+)-IIZUEND/', '\${$1}', $view->render());
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
		$tmpName = GeneralUtility::tempnam('iizuna_');
		GeneralUtility::writeFile($tmpName, $temporaryFileContent);
		$ret = $callable($tmpName);
		GeneralUtility::unlink_tempfile($tmpName);

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
		$view = $this->objectManager->get(Render::class);
		$view->setControllerContext($this->controllerContext);
		/** @var TemplateView $view */
		$view->setPartialRootPaths(["EXT:$extension/Resources/Private/Partials"]);
		$view->setLayoutRootPaths(["EXT:$extension/Resources/Private/Layouts"]);
		$view->setTemplateRootPaths(["EXT:$extension/Resources/Private/Templates"]);

		return $view;
	}
}
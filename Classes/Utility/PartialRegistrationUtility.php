<?php

namespace Iizunats\Iizuna\Utility;

use TYPO3\CMS\Core\SingletonInterface;



/**
 * Class PartialRegistrationUtility
 *
 * This class is used to register partials for latter output.
 * If a partial was not registered that way, then it won't be shown for security reasons
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package Iizunats\Iizuna\Utility
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
	 * @param array $allowedArguments
	 */
	public function register (string $extension, string $partial, array $allowedArguments = []) {
		$this->registered[] = [$extension, $partial, $allowedArguments];
	}


	/**
	 * Checks whether a partial for the given extension and partial name was registered
	 *
	 * @param string $extensionName
	 * @param string $partialName
	 *
	 * @return bool
	 */
	public function isRegistered (string $extensionName, string $partialName): bool {
		return $this->getConfiguration($extensionName, $partialName) !== null;
	}


	/**
	 * @param string $extensionName
	 * @param string $partialName
	 *
	 * @return array|null
	 */
	public function getConfiguration (string $extensionName, string $partialName) {
		foreach ($this->registered as $key => list($registeredExtension, $registeredPartial)) {
			if ($registeredExtension === $extensionName && $partialName === $registeredPartial) {
				return $this->registered[$key];
			}
		}

		return null;
	}
}
<?php

namespace iizunats\iizuna\Utility;

/**
 * Class PartialCacheUtility
 *
 * @author Tim Rücker <tim.ruecker@iizunats.com>
 * @package iizunats\iizuna\Utility
 */
class PartialCacheUtility {

	public static function hash (string $template): string {
		return md5($template);
	}
}
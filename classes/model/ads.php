<?php

namespace Foolz\FoolFuuka\Plugins\Adverts\Model;

class Ads
{
	static $processed = false;

	public static function display($result)
	{
		if (static::$processed === true || !is_object(($board = $result->getParam('board', null)))) {
			return null;
		}

		echo '<div class="pull-right">';
		if ($board->getValue('is_nsfw', false)) {
			echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.codensfw');
		} else {
			echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.code');
		}
		echo '</div>';

		static::$processed = true;
	}

	public static function displayunder($result)
	{
		if ($result->getObject()->getBuilderParamManager()->getParam('disable_headers', false) !== true) {
			$board = $result->getObject()->getBuilderParamManager()->getParam('radix', null);
			if ($board !== null) {
				echo '<div style="text-align:center; margin-bottom:10px; padding-top:5px;">';
				if ($board->getValue('is_nsfw', false)) {
					echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.codensfwbottom');
				} else {
					echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.codebottom');
				}
				echo '</div>';
			}
		} else {
			echo '<div style="text-align:center; margin-bottom:10px; padding-top:5px;">';
			echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.codebottom');
			echo '</div>';
		}
	}

	public static function displayheader($result)
	{
		if ($board = $result->getObject()->getBuilderParamManager()->getParam('radix', null)) {
			if ($board->getValue('is_nsfw', false)) {
				echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.headercodensfw');
			} else {
				echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.headercode');
			}
		} else {
			echo $result->getObject()->getPreferences()->get('foolfuuka.plugin.adverts.headercode');
		}
	}
}

<?php

/**
 * DelectusPageExtension added to Page class provides fields needed by Delectus to track indexing and search and hooks to make sure
 * changes made to pages in the CMS are advertised to delectus
 */
class DelectusIndexPageExtension extends DataExtension {

	// set to false to disable Delectus functions at runtime, e.g. during testing other functionality
	private static $delectus_enabled = true;


	public function onAfterPublish() {
		if ( static::enabled() && $this->owner->config()->get( 'delectus_enabled', Config::UNINHERITED ) ) {
			DelectusIndexModule::add_page( $this->owner, $responseMessage );
		}
	}

	public function onAfterUnpublish() {
		if ( static::enabled() && $this->owner->config()->get( 'delectus_enabled', Config::UNINHERITED ) ) {
			DelectusIndexModule::remove_page( $this->owner, $responseMessage );
		}
	}

	/**
	 * Set and/or get the current enabled state of this extension.
	 *
	 * @param null|bool $enable if passed then use it to set the enabled state of this extension
	 *
	 * @return bool if enable parameter was passed this will be the previous value otherwise the current value
	 */
	public static function enabled( $enable = null ) {
		if ( func_num_args() ) {
			$return = \Config::inst()->get( static::class, 'delectus_enabled' );
			\Config::inst()->update( static::class, 'delectus_enabled', $enable );
		} else {
			$return = \Config::inst()->get( static::class, 'delectus_enabled' );
		}

		return (bool) $return;
	}

}
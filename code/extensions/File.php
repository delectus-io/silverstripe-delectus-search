<?php

class DelectusIndexFileExtension extends DataExtension {
	// set to false to disable Delectus functions at runtime, e.g. during testing other functionality
	private static $delectus_enabled = true;

	public function onAfterWrite() {
		parent::onAfterWrite();
		if ( static::enabled() && $this->owner->config()->get('delectus_enabled', Config::UNINHERITED)) {
			if ( ! $this->owner->hasExtension( Versioned::class ) ) {
				DelectusIndexModule::add_file( $this->owner, $responseMessage );
			}
		}
	}

	public function onAfterDelete() {
		parent::onAfterDelete();
		if ( static::enabled() && $this->owner->config()->get( 'delectus_enabled', Config::UNINHERITED ) ) {
			DelectusIndexModule::remove_file( $this->owner, $responseMessage );
		}
	}

	public function onAfterPublish() {
		if ( static::enabled() && $this->owner->config()->get( 'delectus_enabled', Config::UNINHERITED )) {
			DelectusIndexModule::add_file( $this->owner, $responseMessage );
		}
	}

	public function onAfterUnpublish() {
		if ( static::enabled() && $this->owner->config()->get( 'delectus_enabled', Config::UNINHERITED )) {
			DelectusIndexModule::remove_file( $this->owner, $responseMessage );
		}
	}

	/**
	 * Set and/or get the current enabled state of this extension.
	 *
	 * @param null|bool $enable if passed then use it to set the enabled state of this extension
	 *
	 * @return bool
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
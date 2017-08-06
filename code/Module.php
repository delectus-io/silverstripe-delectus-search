<?php

/**
 * Delectus module class holds configuration and common functionality for the delectus module. Provides an interface which can be used internally
 * and by other code on the site.
 */
class DelectusIndexModule extends DelectusModule {

	const Endpoint = 'index';

	const ActionAdd     = 'add';
	const ActionRemove  = 'remove';
	const ActionReindex = 'reindex';
	const ActionBlock   = 'block';


	/**
	 * @param SiteTree|int $pageOrID
	 *
	 * @return mixed
	 * @throws \Exception
	 * @throws \InvalidArgumentException
	 *
	 */
	public static function add_page( $pageOrID, &$responseMessage ) {
		try {
			if ( $page = static::resolve_page( $pageOrID ) ) {
				if ( $page->ShowInSearch ) {
					$response = static::make_request(
						self::Endpoint,
						self::ActionAdd,
						$page,
						[
							DelectusModule::RequestLinkKey => $page->Link()
						],
						$responseMessage
					);

					return $responseMessage == 'OK';

				} else {
					$responseMessage = _t(
						'Delectus.ShowInSearchMessage',
						"{type} {id} ShowInSearch precludes indexing",
						[
							'type' => 'Page',
							'id'   => $page->ID,
						]
					);
				}
			}
		} catch ( Exception $e ) {
			$responseMessage = _t(
				'Delectus.AddActionFailed',
				"Failed to add {type} to index: {exception}",
				[
					'type'      => 'Page',
					'exception' => $e->getMessage(),
				]
			);
		}

		return false;
	}

	public static function remove_page( $pageOrUrlOrID, &$responseMessage ) {
		try {
			if ( $page = static::resolve_page( $pageOrUrlOrID ) ) {
				$response = static::make_request(
					self::Endpoint,
					self::ActionRemove,
					$page,
					[
						DelectusModule::RequestLinkKey => $page->Link()
					],
					$responseMessage
				);

				return $responseMessage == 'OK';
			}
		} catch ( Exception $e ) {
			$responseMessage = _t(
				'Delectus.RemoveActionFailed',
				"Failed to remove {type} from index: {exception}",
				[
					'type'      => 'Page',
					'exception' => $e->getMessage(),
				]
			);

		}

		return false;
	}

	public static function add_file( $fileOrIDOrPath, &$responseMessage ) {
		try {
			if ( $file = static::resolve_file( $fileOrIDOrPath ) ) {
				if ( $file->ShowInSearch ) {
					$response = static::make_request(
						self::Endpoint,
						self::ActionAdd,
						$file,
						[
							DelectusModule::RequestLinkKey => $file->Link()
						],
						$responseMessage
					);

					return $responseMessage == 'OK';
				}
			}
		} catch ( Exception $e ) {
			$responseMessage = _t(
				'Delectus.AddActionFailed',
				"Failed to add {type} to index: {exception}",
				[
					'type'      => 'File',
					'exception' => $e->getMessage(),
				]
			);
		}

		return false;
	}

	public static function remove_file( $fileOrUrlOrID, &$responseMessage ) {
		try {
			if ( $file = static::resolve_file( $fileOrUrlOrID ) ) {
				$response = static::make_request(
					self::Endpoint,
					self::ActionRemove,
					$file,
					[
						DelectusModule::RequestLinkKey => $file->Link()
					],
					$responseMessage
				);

				return $responseMessage == 'OK';
			}
		} catch ( Exception $e ) {
			$responseMessage = _t(
				'Delectus.RemoveActionFailed',
				"Failed to remove {type} from index: {exception}",
				[
					'type'      => 'File',
					'exception' => $e->getMessage(),
				]
			);

		}

		return false;
	}


}
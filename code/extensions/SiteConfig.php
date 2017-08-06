<?php

/**
 * Add fields to SiteTree model and CMS for Delectus
 */
class DelectusIndexSiteConfigExtension extends DataExtension {
	const ClientTokenFieldName         = 'DelectusClientToken';
	const ClientSaltFieldName          = 'DelectusClientSalt';
	const SiteIdentifierFieldName      = 'DelectusSiteIdentifier';
	const TokensInURLFieldName         = 'DelectusTokensInURL';
	const EncryptionAlgorythmFieldName = 'DelectusEncryptionAlgorythm';

	private static $db = [
		self::ClientTokenFieldName         => 'Varchar(255)',
		self::ClientSaltFieldName          => 'Varchar(255)',
		self::SiteIdentifierFieldName      => 'Varchar(255)',
		self::EncryptionAlgorythmFieldName => 'Enum("aes-256-ctr,No Encryption")',
		self::TokensInURLFieldName         => 'Boolean',
	];

	public function updateCMSFields( FieldList $fields ) {
		$fields->addFieldsToTab(
			DelectusModule::cms_tab_name(),
			[
				TextField::create(
					self::ClientTokenFieldName,
					_t(
						'Delectus.ClientTokenLabel',
						"Client Token"
					),
					DelectusModule::client_token() )
					->setRightTitle( _t( 'Delectus.ClientTokenDescription', "Enter the client token from your Delectus client account, or configure in SilverStripe" ) )
					->setAttribute( 'placeholder', "e.g. " . DelectusIndexModule::generate_token() )
				,
				TextField::create(
					self::ClientSaltFieldName,
					_t(
						'Delectus.ClientSaltLabel',
						"Client Salt"
					),
					DelectusModule::client_salt() )
					->setRightTitle( _t( 'Delectus.ClientSaltDescription', "Enter the client salt from your Delectus client account, or configure in SilverStripe" ) )
					->setAttribute( 'placeholder', "e.g. " . DelectusIndexModule::generate_token() ),
				TextField::create(
					self::SiteIdentifierFieldName,
					_t(
						'Delectus.SiteIdentifierLabel',
						"Site Identifier"
					),
					DelectusModule::site_identifier() )
					->setRightTitle( _t( 'Delectus.SiteIdentifierDescription', "Enter the site identifier from your Delectus record for this website, or configure in SilverStripe" ) )
					->setAttribute( 'placeholder', "e.g. " . DelectusIndexModule::generate_token() ),
				CheckboxField::create(
					self::TokensInURLFieldName,
					_t(
						'Delectus.TokensInURLLabel',
						'Request Tokens in URL'
					),
					DelectusModule::tokens_in_url() )
					->setRightTitle( _t( 'Delectus.TokensInURLDescription', "Send tokens on URL instead of headers, usefull if a proxy is preventing headers from getting through" ) ),
				DropdownField::create(
					self::EncryptionAlgorythmFieldName,
					_t(
						'Delectus.EncryptionAlgorythmLabel',
						'Request Data Encryption Method'
					),
					DelectusModule::tokens_in_url() )
					->setRightTitle( _t( 'Delectus.EncryptionAlgorythmDescription', "How to encrypt data in requests, only choose No Encryption if over ssl or local testing!" ) )

			]
		);
	}
}
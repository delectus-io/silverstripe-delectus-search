<?php

/**
 * DelectusPageExtension added to Page class provides fields needed by Delectus to track indexing and search and hooks to make sure
 * changes made to pages in the CMS are advertised to delectus
 */
class DelectusPageExtension extends DataExtension {
	public function onAfterPublish() {
		//
	}
	public function onAfterUnpublish() {
		// remove f
	}
}
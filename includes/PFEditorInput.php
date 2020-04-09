<?php

namespace MediaWiki\Extension\PFEditorInput;

use PFTextInput;
use RequestContext;

class PFEditorInput extends PFTextInput {

	/**
	 * @inheritDoc
	 * @return String|null
	 */
	public static function getName() {
		return 'editor';
	}

	public static function getDefaultPropTypes() {
		return [];
	}

	public static function getOtherPropTypesHandled() {
		return [];
	}

	public static function getDefaultPropTypeLists() {
		return [];
	}

	/**
	 * @inheritDoc
	 * @return array[]
	 */
	public static function getParameters() {
		$params = parent::getParameters();
		$params[] = [
			'name' => 'append',
			'type' => 'string',
			'description' => wfMessage( 'pf_forminputs_editorinput_append' )->text()
		];
		return $params;
	}

	/**
	 * Returns the HTML code to be included in the output page for this input.
	 * @return string
	 */
	public function getHtmlText() {
		$editor = RequestContext::getMain()->getUser();
		if ( !$editor->isAnon() ) {
			if ( isset( $this->mOtherArgs['append'] ) ) {
				if ( $this->mCurrentValue ) {
					$sep = isset( $this->mOtherArgs['delimiter'] ) ? $this->mOtherArgs['delimiter'] : ',';
					$this->mCurrentValue = implode(
						$sep,
						array_unique(
							array_merge(
								explode( $sep, $this->mCurrentValue ),
								[ $editor->getName() ]
							)
						)
					);
				} else {
					$this->mCurrentValue = $editor->getName();
				}
			} else {
				$this->mCurrentValue = $editor->getName();
			}
		}

		return self::getHTML(
			$this->mCurrentValue,
			$this->mInputName,
			$this->mIsMandatory,
			$this->mIsDisabled,
			$this->mOtherArgs
		);
	}

}

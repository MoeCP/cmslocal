<?php
/* * 
 * Tiny Spelling Interface for TinyMCE Spell Checking.
 *
 * Copyright  2006 Moxiecode Systems AB
 * modified by leo.liuxl@gmail.com
 *
 */

class TinyPSpell {
	var $lang;
	var $mode;
	var $string;
	var $plink;
	var $errorMsg;
	//var $editablePersonalDict = true;

	var $jargon;
	var $spelling;
	var $encoding;
	var $pspell_config;

	function TinyPSpell(&$config, $lang, $mode, $spelling, $jargon, $encoding) {
		global $personal_dict_path;

		$this->lang = $lang;
		$this->mode = $mode;
		$this->plink = false;
		$this->errorMsg = array();

		if (!function_exists("pspell_new")) {
			$this->errorMsg[] = "PSpell not found.";
			return;
		}

		//modified by leo.liuxl@gmail.com 2006-11-27
		$this->pspell_config = pspell_config_create($this->lang, $this->spelling, $this->jargon, $this->encoding);
		pspell_config_mode($this->pspell_config, $this->mode);
		pspell_config_personal($this->pspell_config, $personal_dict_path);
		$this->plink = pspell_new_config($this->pspell_config);
		//end modified;

		//$this->plink = pspell_new($this->lang, $this->spelling, $this->jargon, $this->encoding, $this->mode);

	}

	// Returns array with bad words or false if failed.
	function checkWords($wordArray) {
		if (!$this->plink) {
			$this->errorMsg[] = "No PSpell link found for checkWords.";
			return array();
		}

		$wordError = array();
		foreach($wordArray as $word) {
			if(!pspell_check($this->plink, trim($word)))
				$wordError[] = $word;
		}

		return $wordError;
	}

	/*************************************************************
	 * addWordToPersonalDict($str)
	 *
	 * This function adds a word to the custom dictionary
	 *
	 * @param $str The word to be added
	 * @author leo.liuxl@gmail.com
	 *************************************************************/
	function addWordToPersonalDict($str)
	{
		global $personal_dict_path;

		if (trim($str) == "") {
			return;
		}
		/*
		if (!function_exists("pspell_new_config")) {
			$this->errorMsg[] = "PSpell not found.";
			return;
		}
		$pspell_personal_config = pspell_config_create('en');//defalt is en
		pspell_config_personal($pspell_personal_config, $personal_dict_path);
		$pspell_personal_link = pspell_new_config($pspell_personal_config);
		pspell_add_to_personal($pspell_personal_link, $str);
		//pspell_save_wordlist($pspell_personal_link);
		if (pspell_save_wordlist($pspell_personal_link)) {
		*/

		pspell_add_to_personal($this->plink, $str);
		if (pspell_save_wordlist($this->plink)) {
			return true;
		} else {
			return false;
		}
	} //end addWordToPersonalDict

	// Returns array with suggestions or false if failed.
	function getSuggestion($word) {
		if (!$this->plink) {
			$this->errorMsg[] = "No PSpell link found for getSuggestion.";
			return array();
		}

		return pspell_suggest($this->plink, $word);
	}
}

// Setup classname, should be the same as the name of the spellchecker class
$spellCheckerConfig['class'] = "TinyPspell";

?>
<?php
	// General settings
	$config['general.engine'] = 'GoogleSpell';
	//$config['general.engine'] = 'PSpell';
	//$config['general.engine'] = 'PSpellShell';
	//$config['general.remote_rpc_url'] = 'http://some.other.site/some/url/rpc.php';

	// PSpell settings
	$config['PSpell.mode'] = PSPELL_FAST;
	$config['PSpell.spelling'] = "";
	$config['PSpell.jargon'] = "";
	$config['PSpell.encoding'] = "";

	// PSpellShell settings
	$config['PSpellShell.mode'] = PSPELL_FAST;
	$config['PSpellShell.aspell'] = '/usr/bin/aspell';
	$config['PSpellShell.tmp'] = '/tmp';

	// Windows PSpellShell settings
	//$config['PSpellShell.aspell'] = '"c:\Program Files\Aspell\bin\aspell.exe"';
	//$config['PSpellShell.tmp'] = 'c:/temp';


    // Personal word list --- Load a new dictionary with personal wordlist -- added by leo.liuxl@gmail.com
    // if you don't wanna use your personal wordlist, just leave the value as blank,
    // Such as: $config['general.personal_word_list'] = ""
    $config['general.personal_word_list'] = "/home/web/i9cms.com/ckmg_cms_branch/admin/article/spell_checker/spell_checker/personal_dictionary/personal_dictionary.pws"; // the path to the Personal word list file.
?>

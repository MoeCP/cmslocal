<?php
error_reporting(E_ALL);
ini_set("display_errors", 1); 
//##require_once COMMON_PATH.'PhpWord'.DS.'PhpWord.php';
//ini_set('memory_limit', '512M');
function createdocx($article_info, $zip=false) {
    global $filename;
    require_once COMMON_PATH.'PhpOffice'.DS.'PhpWord'.DS.'Autoloader.php';
    require_once COMMON_PATH.'PhpOffice'.DS.'PhpWord'.DS.'PhpWord.php';
    require_once COMMON_PATH.'simplehtmldom'.DS.'simple_html_dom.php';
    require_once COMMON_PATH.'h2d_htmlconverter.php';
    require_once COMMON_PATH.'simplehtmldom'.DS.'styles.inc.php';
    require_once COMMON_PATH.'simplehtmldom'.DS.'support_functions.inc';

    \PhpOffice\PhpWord\Autoloader::register();

    // New Word Document:
    $phpword_object = $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpword_object->createSection();

    $section->addText($article_info["title"], array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));


    $html = ($article_info["richtext_body"] != '') ? $article_info["richtext_body"] : nl2br($article_info["body"]);
    //###$html = "<h1>Hello world</h1>";

    if ($article_info["template"] == 2) {
        if (!empty($article_info["small_image"])) {
            $html .= "<br /><div><span><strong>Small Image: </strong></span><span>".$article_info["small_image"]."</span></div><br />";
        }
        if (!empty($article_info["large_image"])) {
            $html .= "<br /><div><span><strong>Large Image: </strong></span><span>".$article_info["large_image"]."</span></div><br />";
        }
        if (!empty($article_info["image_credit"])) {
            $html .= "<br /><div><span><strong>Image Credit: </strong></span><span>".$article_info["image_credit"]."</span></div><br />";
        }
        if (!empty($article_info["image_caption"])) {
            $html .= "<br /><div><span><strong>Image Caption: </strong></span><span>".$article_info["image_caption"]."</span></div><br />";
        }
        if (!empty($article_info["blurb"])) {
            $html .= "<br /><div><span><strong>Blurb: </strong></span><span>".nl2br($article_info["blurb"])."</span></div><br />";
        }
        if (!empty($article_info["meta_description"])) {
            $html .= "<br /><div><span><strong>Blurb: </strong></span><span>".$article_info["meta_description"]."</span></div><br />";
        }
        if (!empty($article_info["category_id"])) {
            $html .= "<br /><div><span><strong>Category: </strong></span><span>".$article_info["category_id"]."</span></div><br />";
        }
    }

    if ($article_info["show_cp_bio"] == 1) {
        $html .= "<div><span><strong>Author Bio: </strong></span><br /><span>".$article_info["cp_bio"]."</span></div>";
    }

    // HTML Dom object:
    $html_dom = new simple_html_dom();
    $html_dom->load('<html><body>' . $html . '</body></html>');
    // Note, we needed to nest the html in a couple of dummy elements.

    // Create the dom array of elements which we are going to work on:
    $html_dom_array = $html_dom->find('html',0)->children();

    // We need this for setting base_root and base_path in the initial_state array
    // (below). We are using a function here (derived from Drupal) to create these
    // paths automatically - you may want to do something different in your
    // implementation. This function is in the included file 
    // documentation/support_functions.inc.
    $paths = htmltodocx_paths();

    // Provide some initial settings:
    $initial_state = array(
      // Required parameters:
      'phpword_object' => &$phpword_object, // Must be passed by reference.
      // 'base_root' => 'http://test.local', // Required for link elements - change it to your domain.
      // 'base_path' => '/htmltodocx/documentation/', // Path from base_root to whatever url your links are relative to.
      'base_root' => $paths['base_root'],
      'base_path' => $paths['base_path'],
      // Optional parameters - showing the defaults if you don't set anything:
      'current_style' => array('size' => '11'), // The PHPWord style on the top element - may be inherited by descendent elements.
      'parents' => array(0 => 'body'), // Our parent is body.
      'list_depth' => 0, // This is the current depth of any current list.
      'context' => 'section', // Possible values - section, footer or header.
      'pseudo_list' => TRUE, // NOTE: Word lists not yet supported (TRUE is the only option at present).
      'pseudo_list_indicator_font_name' => 'Wingdings', // Bullet indicator font.
      'pseudo_list_indicator_font_size' => '7', // Bullet indicator size.
      'pseudo_list_indicator_character' => 'l ', // Gives a circle bullet point with wingdings.
      'table_allowed' => TRUE, // Note, if you are adding this html into a PHPWord table you should set this to FALSE: tables cannot be nested in PHPWord.
      'treat_div_as_paragraph' => TRUE, // If set to TRUE, each new div will trigger a new line in the Word document.
          
      // Optional - no default:    
      'style_sheet' => htmltodocx_styles_example(), // This is an array (the "style sheet") - returned by htmltodocx_styles_example() here (in styles.inc) - see this function for an example of how to construct this array.
      );



    // Convert the HTML and put it into the PHPWord object
    htmltodocx_insert_html($section, $html_dom_array[0]->nodes, $initial_state);

    // Clear the HTML dom object:
    $html_dom->clear(); 
    unset($html_dom);

    $h2d_file_uri = tempnam('', 'htd');
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpword_object, 'Word2007');
    $objWriter->save($h2d_file_uri);

    if ($zip) {
        readfile($h2d_file_uri);
        unlink($h2d_file_uri);
    } else {
        // Download the file:
        //header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        //header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($h2d_file_uri));
        ob_clean();
        flush();
        $status = readfile($h2d_file_uri);
        unlink($h2d_file_uri);
        //exit;
    }
}
?>
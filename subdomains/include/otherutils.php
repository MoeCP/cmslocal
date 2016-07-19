<?php
function getAtomDATA($p = array(), &$filename = null, &$campaign_info = null) 
{  
    $article_id   = $p['article_id'];
    $campaign_id  = $p['cid'];
    $timestamp = $p['timestamp'];
    $article_ids  = $p['article_ids'];
    $title        = $p['title'];
    $ht           = $p['ht'];
    $mk           = $p['mk'];
    $md           = $p['md'];
    $body         = $p['body'];
    $author       = $p['author'];    
    $is_rich      = $p['is_rich'];
    $text_body = $p['text_body'];
    $rich_body = $p['rich_body'];
    $username     = trim($p['u']);
    $password     = trim($p['p']);
    $url_part     = trim($p['url_part']);
    $topic        = trim($p['topic']);
    //##$info =  $p['result'];
    $mid = $p['mid'];
    $optional1 = $p['optional1'];
    $optional2 = $p['optional2'];
    $optional3 = $p['optional3'];
    $optional4 = $p['optional4'];
    $optional5 = $p['optional5'];
    $optional6 = $p['optional6'];
    $optional7 = $p['optional7'];
    $optional8 = $p['optional8'];
    $optional9 = $p['optional9'];
    $optional10 = $p['optional10'];

    if (is_array($campaign_id)) {
        $campaign_id = implode(",", $campaign_id);
    }
    if (strlen($username) && strlen($password)) {
       switch($url_part) {
       	case 'user':
            $sess = User::getLogin($username, $password);
            if ($sess) {
            	User::setLogin($sess);
            }
            break;
        case 'client':
            $sess = Client::getLogin($username, $password);
            if ($sess) {
            	Client::setLogin($sess);
            }
        	break;
       }
    	if ($sess === false) {
            echo "<script>alert('Incorrect username password combination!');window.close();</script>";
            return false;
        } else if ($campaign_id > 0 && $title == '' && $mk == '' && $mk == '' && $body == '') {
            echo "<script>alert('Incorrect URL format!');window.close();</script>";
        	return false;
        }
    }

	require_once CMS_INC_ROOT.'/Client.class.php';
    require_once CMS_INC_ROOT.'/Article.class.php';
    require_once CMS_INC_ROOT.'/Campaign.class.php';
    $info = array();
    // get campaign information by keyword id
    $all_copy_writer = User::getAllUsers($mode = 'id_name_only', $user_type = 'copy writer', false);
    if (strlen($article_ids) || $article_id >= 0) {
        if ($article_id) {
            $article_info = Article::getInfo($article_id, false);
            $info[$article_id] = $article_info;
            $filename = $article_info['title'];
        } else if (strlen($article_ids) && empty($info)) {
            $article_ids = trim($article_ids, ';');
            $aids = explode(";", $article_ids);
            // $article_info = Article::getInfo($aids[0], false);
            $p['article_id'] = $aids;
            $info = Article::getCheckedArticle($p);
        }
        if ($campaign_id <= 0) {
            $campaign_id = $article_info['campaign_id'];
        }
        
    }
    $do_html_entity_decode = false;
    if ($campaign_id >= 0 && count($info) == 0) {
        if (client_is_loggedin()) {
            global $client_downloaded_statuses;
            $p['article_status'] = $client_downloaded_statuses;
        }
        $info = Article::downloadArticleByCampaignID($campaign_id, $p);
        $do_html_entity_decode = true;
    }


    $campaign_info = Campaign::getInfo($campaign_id);
    
    if ($url_part == 'client' ) {
        if ($campaign_info['client_id'] != Client::getID()) {
            echo "<script>alert('You have no privilege to download this campaign article');window.close();</script>";
            return false;
        }
    }
    if (count($info) == 0 && count($campaign_info) == 0) {
        echo "<script>alert('There is no article');window.close();</script>";
        return false;
    }
    // generate xml info

    if (count($info) && count($campaign_info)) {
        if (count($info) == 1) {
            $_infokey = key($info);
            $maintitle = $info[$_infokey]["title"];
            $mainsubtitle = $info[$_infokey]["keyword"];
            $maintitle = escapeSpecialchars4Atom($maintitle);
            $mainsubtitle = escapeSpecialchars4Atom($mainsubtitle);
            $feedupdated = empty($info[$_infokey]['article_status']) ? time() : date("Y-m-d\TH:i:s\Z", strtotime($info[$_infokey]['cp_updated']));
            $feedupdated = date("Y-m-d\TH:i:s\Z", $feedupdated);
        } else {
            $maintitle = "Copypress Batch";
            $mainsubtitle = "Batch-1";
            $feedupdated = date("Y-m-d\TH:i:s\Z");
        }

        $xml  = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom">';
        $xml .= '<title>'.$maintitle.'</title>';
        $xml .= '<subtitle>'.$mainsubtitle.'</subtitle>';
        $xml .= '<id>urn:timestamp:'.date("Y-m-d-H-i").'</id>';
        $xml .= '<updated>'.$feedupdated.'</updated>';
        //print_r($info);

        foreach($info as $key => $val) {

            $ar_arr['article_id'][$val['article_id']] = $val['article_id'];
            $xml .= '<entry>';
            if ($title == 1) {
                $val['title'] = escapeSpecialchars4Atom($val['title']);
                $xml .= "<title>{$val['title']}</title>";
            }
            if (!empty($val['optional7'])) {
                $xml .= '<link href="'.$val['optional7'].'" rel="alternate" type="text/html"/>';
                $val['optional7'] = escapeSpecialchars4Atom($val['optional7']);
                $xml .= '<id>'.$val['optional7'].'</id>';
            }

            $article_status = $val['article_status'];
            $cp_updated = ($article_status == '0' || $article_status== '') ? 'n/a' : date("Y-m-d\TH:i:s\Z", strtotime($val['cp_updated']));
            $xml .= "<updated>" . $cp_updated . "</updated>";

            if ($md == 1) {
                $description_meta = '';
                if ($campaign_info['meta_param'] == 1)
                {
                    if (!empty($val['description_meta']))
                    {
                        $description_meta = $val['description_meta'];
                    }
                }

                if (empty($description_meta))
                {
                    $body_arr = explode(".", $val['body']);
                    $i = 0;
                    while($i < count($body_arr)) {
                        if ($body_arr[$i] != '') {
                            $body_arr[0] = $body_arr[$i];
                            break;
                        } else {
                            $i++;
                        }
                    }
                    $description_meta = $body_arr[0];
                }

                $description_meta = escapeSpecialchars4Atom($description_meta);
                $xml .= "<summary>{$description_meta}</summary>";
            }

            $xml .= '<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml">';


            // addded by nancy xu 2012-08-02
            //foreach ($val as $kk => $vv) {
            //    if (${$kk} == 1 && substr($kk, 0,8) == 'optional') {
            //        $num = str_replace( 'optional', '', $kk);
            //        $xml .= "<optionalField" . $num ."><![CDATA[{$vv}]]></optionalField" .$num . ">";
            //    }
            //}

            $optfsummary = $val['optional1'];
            $optflink    = $val['optional2'];
            $optfheight  = $val['optional3'];
            $optfwidth   = $val['optional4'];
            $optffigcaption = trim($val['optional5']);
            $optfusername = $val['optional6'];
            if (!empty($optflink)) {
                $xml .= "<figure>";
                $xml .= "<img alt='' height='{$optfheight}' src='{$optflink}' width='{$optfwidth}' />";
                if (!empty($optffigcaption)) {
                    //##$xml .= "<figcaption>{$optffigcaption}";
                    $xml .= "<figcaption>";
                    //##if (!empty($val['optional8'])) {
                        $xml .= 'Image via <a href="'.$optffigcaption.'" target="_blank">'.escapeSpecialchars4Atom($val['optional8']).'</a>';
                    //##}
                    if (!empty($optfusername)) {
                        $xml .= " by " . escapeSpecialchars4Atom($optfusername);
                    }
                    $xml .= "</figcaption>";
                }
                $xml .= "</figure>";
            }

            if ($do_html_entity_decode == true) {
                $val['richtext_body'] = html_entity_decode($val['richtext_body'], ENT_QUOTES);
            }
            $rich_content = covertNamedCharacter2Decimal($val['richtext_body']);
            $rich_content = htmlspecialchars_decode($rich_content);
            $xml .= '<div id="definition">'.$rich_content.'</div>';
            $xml .= '</div></content>';

            $xml .= '<author><name>Copypress</name><email>support@copypress.com</email></author>';
            $xml .= '</entry>';

        }
        $xml .= '</feed>';
        return $xml;
    }

}

function escapeSpecialchars4Atom($str) {
    $str = htmlspecialchars_decode($str);
    $str = htmlspecialchars($str);
    $str = covertNamedCharacter2Decimal($str);
    $str = htmlspecialchars_decode($str);
    return $str;
}


function getAtom($p = array()) {
    $url_part = $p['url_part'];
    $article_ids = $p['article_ids'];
    $username     = trim($p['u']);
    $password     = trim($p['p']);

    ob_start();
    header("Content-type: text/xml; charset=utf-8");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
    
    header("Pragma: no-cache"); 
    // get all keyword info
   
    $suffix = '.xml';

    // get xml file name
    $cid = ($p['cid']); 
    if ( is_array($cid) ) {
        $cid = implode("-", $cid);       
    } 
    $filename = 'CopyPress-' . time() . '-' . $cid;  
    
    //windows valid file name,

    $xml = getAtomDATA($p, $filename, $campaign_info);
    $reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/', '/\s/');
    $filename = preg_replace( $reg_str, '_', $filename ) . $suffix;
    header('Content-Disposition: attachment; filename='. $filename  );
    if(!$xml) {
    	exit;
    } else {
        //output xml info
        echo $xml;
        ob_end_flush();    	
    }
//    if ($url_part == 'client' && client_is_loggedin()) {
//         Article::setDownLoadTime(array('article_id' => $article_ids));
//    }
    Article::setDownLoadTime(array('article_id' => $article_ids));

    if (strlen($username) && strlen($password)) {
        session_start();
        $_SESSION = array();
        session_destroy();
    }
}

function covertNamedCharacter2Decimal($str) {
    $ptarr = array('&quot;','&amp;','&apos;','&lt;','&gt;','&nbsp;','&iexcl;','&cent;','&pound;','&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;','&laquo;','&not;','&shy;','&reg;','&macr;','&deg;','&plusmn;','&sup2;','&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;','&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;','&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;','&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;','&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;','&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;','&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;','&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;','&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;','&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;','&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;','&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;','&ucirc;','&uuml;','&yacute;','&thorn;','&yuml;','&OElig;','&oelig;','&Scaron;','&scaron;','&Yuml;','&fnof;','&circ;','&tilde;','&Alpha;','&Beta;','&Gamma;','&Delta;','&Epsilon;','&Zeta;','&Eta;','&Theta;','&Iota;','&Kappa;','&Lambda;','&Mu;','&Nu;','&Xi;','&Omicron;','&Pi;','&Rho;','&Sigma;','&Tau;','&Upsilon;','&Phi;','&Chi;','&Psi;','&Omega;','&alpha;','&beta;','&gamma;','&delta;','&epsilon;','&zeta;','&eta;','&theta;','&iota;','&kappa;','&lambda;','&mu;','&nu;','&xi;','&omicron;','&pi;','&rho;','&sigmaf;','&sigma;','&tau;','&upsilon;','&phi;','&chi;','&psi;','&omega;','&thetasym;','&upsih;','&piv;','&ensp;','&emsp;','&thinsp;','&zwnj;','&zwj;','&lrm;','&rlm;','&ndash;','&mdash;','&lsquo;','&rsquo;','&sbquo;','&ldquo;','&rdquo;','&bdquo;','&dagger;','&Dagger;','&bull;','&hellip;','&permil;','&prime;','&Prime;','&lsaquo;','&rsaquo;','&oline;','&frasl;','&euro;','&image;','&weierp;','&real;','&trade;','&alefsym;','&larr;','&uarr;','&rarr;','&darr;','&harr;','&crarr;','&lArr;','&uArr;','&rArr;','&dArr;','&hArr;','&forall;','&part;','&exist;','&empty;','&nabla;','&isin;','&notin;','&ni;','&prod;','&sum;','&minus;','&lowast;','&radic;','&prop;','&infin;','&ang;','&and;','&or;','&cap;','&cup;','&int;','&there4;','&sim;','&cong;','&asymp;','&ne;','&equiv;','&le;','&ge;','&sub;','&sup;','&nsub;','&sube;','&supe;','&oplus;','&otimes;','&perp;','&sdot;','&lceil;','&rceil;','&lfloor;','&rfloor;','&lang;','&rang;','&loz;','&spades;','&clubs;','&hearts;','&diams;');
    $regarr = array('&#34;','&#38;','&#39;','&#60;','&#62;','&#160;','&#161;','&#162;','&#163;','&#164;','&#165;','&#166;','&#167;','&#168;','&#169;','&#170;','&#171;','&#172;','&#173;','&#174;','&#175;','&#176;','&#177;','&#178;','&#179;','&#180;','&#181;','&#182;','&#183;','&#184;','&#185;','&#186;','&#187;','&#188;','&#189;','&#190;','&#191;','&#192;','&#193;','&#194;','&#195;','&#196;','&#197;','&#198;','&#199;','&#200;','&#201;','&#202;','&#203;','&#204;','&#205;','&#206;','&#207;','&#208;','&#209;','&#210;','&#211;','&#212;','&#213;','&#214;','&#215;','&#216;','&#217;','&#218;','&#219;','&#220;','&#221;','&#222;','&#223;','&#224;','&#225;','&#226;','&#227;','&#228;','&#229;','&#230;','&#231;','&#232;','&#233;','&#234;','&#235;','&#236;','&#237;','&#238;','&#239;','&#240;','&#241;','&#242;','&#243;','&#244;','&#245;','&#246;','&#247;','&#248;','&#249;','&#250;','&#251;','&#252;','&#253;','&#254;','&#255;','&#338;','&#339;','&#352;','&#353;','&#376;','&#402;','&#710;','&#732;','&#913;','&#914;','&#915;','&#916;','&#917;','&#918;','&#919;','&#920;','&#921;','&#922;','&#923;','&#924;','&#925;','&#926;','&#927;','&#928;','&#929;','&#931;','&#932;','&#933;','&#934;','&#935;','&#936;','&#937;','&#945;','&#946;','&#947;','&#948;','&#949;','&#950;','&#951;','&#952;','&#953;','&#954;','&#955;','&#956;','&#957;','&#958;','&#959;','&#960;','&#961;','&#962;','&#963;','&#964;','&#965;','&#966;','&#967;','&#968;','&#969;','&#977;','&#978;','&#982;','&#8194;','&#8195;','&#8201;','&#8204;','&#8205;','&#8206;','&#8207;','&#8211;','&#8212;','&#8216;','&#8217;','&#8218;','&#8220;','&#8221;','&#8222;','&#8224;','&#8225;','&#8226;','&#8230;','&#8240;','&#8242;','&#8243;','&#8249;','&#8250;','&#8254;','&#8260;','&#8364;','&#8465;','&#8472;','&#8476;','&#8482;','&#8501;','&#8592;','&#8593;','&#8594;','&#8595;','&#8596;','&#8629;','&#8656;','&#8657;','&#8658;','&#8659;','&#8660;','&#8704;','&#8706;','&#8707;','&#8709;','&#8711;','&#8712;','&#8713;','&#8715;','&#8719;','&#8721;','&#8722;','&#8727;','&#8730;','&#8733;','&#8734;','&#8736;','&#8743;','&#8744;','&#8745;','&#8746;','&#8747;','&#8756;','&#8764;','&#8773;','&#8776;','&#8800;','&#8801;','&#8804;','&#8805;','&#8834;','&#8835;','&#8836;','&#8838;','&#8839;','&#8853;','&#8855;','&#8869;','&#8901;','&#8968;','&#8969;','&#8970;','&#8971;','&#9001;','&#9002;','&#9674;','&#9824;','&#9827;','&#9829;','&#9830;');

    return str_replace($ptarr, $regarr, $str);
}


class OtherUtilsForArticle {
    function updateArticleByParam($data, $p = array())
    {
        global $conn;
        $sql = 'UPDATE articles SET ';
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = $k . '=\'' . addslashes(htmlspecialchars(trim($v))) . '\'';
        }
        $sql .= implode(',', $sets);
        $sql .= ' WHERE ' . implode(' AND ', $p);
        return $conn->Execute($sql);
    }
}
?>
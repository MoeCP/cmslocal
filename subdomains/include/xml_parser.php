<?php
class XMLParser {
    public $user;
    public $apisignature;
    public $apikey;
    public $data;
    public $sssreply;

    function XMLParser()
    {
        $this->__construct();
    }

	function __construct()
	{
	}

    function parse($xml)
    {
        $data = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!empty($data)) {
            $data =objtoarr($data);
            $this->apisignature = $data['apisignature'];
            $this->apikey = $data['apikey'];
            unset($data['user']);
            unset($data['apikey']);
            unset($data['apisignature']);
            $this->data = $data;
        }
        return $data;
    }



    function dataDispose($obj, $api_info)
    {
        $data = $this->data;
        $result = array();
        $campaign_id = $api_info['campaign_id'];
        $method = $data['method'];
        $item_name = 'articlestatus';
        $keys = array_keys($data);
        $total = count($data);
        $xml_data = array();
        //###if (empty($method) && $total <= 2 && isset($data['campaignid'])) {
        if (empty($method) && $total <= 3 && isset($data['campaignid'])) {
            $campaign_id = $data['campaignid'];
            $result = $obj->downloadArticlesByCampaignId($campaign_id, $api_info, $data);
            
            if (!empty($result)) {
                header("Content-type: text/xml; charset=utf-8");
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
                header("Cache-Control: no-cache, must-revalidate"); 
                header("Cache-Control: post-check=0, pre-check=0", false);
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
                header("Pragma: no-cache");
                $suffix = '.xml';
                $filename = 'CopyPress-' . $campaign_id . '-' . time();  
                $reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/', '/\s/');
                $filename = preg_replace( $reg_str, '_', $filename ) . $suffix;
                header('Content-Disposition: attachment; filename='. $filename  );
            } else {
                global $feedback;
                if (empty($feedback)) {
                    $feedback = 'No articles, please to check you campaign';
                }
                $result = array(array('memo' => $feedback, 'campaign_id' => $campaign_id));
            }
            if (!empty($result)) {
                $xml_data = generateXmlArray($xml_data, $result);
            }
        } else {
            $transaction = isset($data['transaction']) && $data['transaction'] ==1 ? true: false;
            $mtype = strtolower($method);
            if (!empty($mtype) && $mtype != 'getallcampaigns') {
                $param = isset($data['param']) && !empty($data['param'])? $data['param'] : array();
                $result = $obj->{$method}($param, $api_info);
                if (substr($mtype, 0,3) == 'get') {
                    if ($mtype == 'getuserprofile') {
                        $item_name = substr($mtype, 3,1) . substr($method, 4). 'Status';
                    } else {
                        $item_name = substr($mtype, 3). 'status';
                    }
                }
                $xml_data = generateXmlArray($xml_data, $result, $item_name);
            } elseif ($mtype == 'getallcampaigns' || $keys[0] == 'getallcampaigns') {
                if (isset($data['getallcampaigns']['param'])) {
                    $param = $data['getallcampaigns']['param'];
                } else {
                    $param = array();
                }
                $result = $obj->getAllCampaigns($api_info, $data['getallcampaigns']['field'], $param);
                $xml_data = generateXmlArray($xml_data, $result, 'campaignstatus');
            } else {
                
                foreach ($data as $k => $row) {
                    $item_name = 'articlestatus';
                    if (isset($row['articleid']) || isset($row['keyword'])) {
                        $row = array($row);
                    }
                    $campaign_id = isset($row['campaignid']) ? $row['campaignid'] : 0;
                    $row_keys = array_keys($row);
                    if (!is_numeric($row_keys[0])) $row = array($row);
                    switch ($k) {
                    case 'addArticleTag':
                        $result = $obj->addArticleTag($row, $api_info);
                        break;
                    case 'delArticleTag':
                        $result = $obj->delArticleTag($row, $api_info);
                        break;
                    case 'updateTag':
                    case 'updatetag':
                        $item_name = 'tagstatus';
                        $result = $obj->updateTag($row, $api_info);
                        break;
                    case 'getcomments':
                        $result = $obj->getComments($row, $api_info);
                        break;
                    case 'addcomment':
                        $result = $obj->addComments($row, $api_info, $transaction);
                        break;
                    // added by nancy xu 2013-05-09 9:27
                    case 'createDomain':
                        $result = $obj->addDomains($row, $api_info);
                        $item_name = 'doaminstatus';
                        break;
                    // end
                    case 'createcampaign':
                        $row_keys = array_keys($row);
                        // $result = array_merge($result, $obj->addCampaigns($row, $api_info));
                        $result = $obj->addCampaigns($row, $api_info);
                        $item_name = 'campaignstatus';
                        break;
                    case 'createcampaignorder':
                        $row_keys = array_keys($row);
                        $result = $obj->addCampaignOrders($row, $api_info);
                        $item_name = 'campaignorderstatus';
                        break;
                    case 'confirmcampaignorder':
                        $result = $obj->confirmCampaignOrders($row, $api_info, $transaction);
                        $item_name = 'campaignorderstatus';
                        break;
                    case 'paycampaignorder':
                        $result = $obj->payCampaignOrders($row, $api_info, $transaction);
                        $item_name = 'campaignorderstatus';
                        break;
                    case 'createnewarticle':
                        // $result = array_merge($result, $obj->addArticles($row, $campaign_id, $api_info, $transaction));
                        $result = $obj->addArticles($row, $campaign_id, $api_info, $transaction);
                        break;
                    case 'updatearticle':
                        break;
                    case 'cancelarticle':
                        $result = array_merge($result, $obj->cancelArticles($row, $campaign_id, $result));
                        $result = $obj->cancelArticles($row, $campaign_id, $result);
                        break;
                    case 'approvearticle':
                        // $result = array_merge($result, $obj->setArticleStatus($row, 5, $api_info));
                        $result = $obj->setArticleStatus($row, 5, $api_info);
                        break;
                    case 'rejectarticle':
                        // $result = array_merge($result, $obj->setArticleStatus($row, 3, $api_info));
                        $result = $obj->setArticleStatus($row, 3, $api_info);
                        break;
                    case 'getarticlestatus':
                        //$result = array_merge($result, $obj->getArticleStatus($row, $campaign_id));
                        $result = $obj->getArticleStatus($row, $campaign_id);
                        break;
                    case 'fuzzytitle':
                        $result = $obj->getArticlesByFuzzyTitle($row);
                        break;
                    case 'recentmodifiedarticles':
                        $result = $obj->getRecentModifiedArticles($row, $api_info);
                        break;
                    case 'getcampaignstatus':
                        // $result = array_merge($result, $obj->getArticleStatusByCampaignID($campaign_id, $api_info));
                        $result = $obj->getArticleStatusByCampaignID($campaign_id, $api_info);
                        break;
                    case 'downloadarticle':
                        header("Content-type: text/xml; charset=utf-8");
                        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
                        header("Cache-Control: no-cache, must-revalidate"); 
                        header("Cache-Control: post-check=0, pre-check=0", false);
                        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
                        header("Pragma: no-cache");
                        $suffix = '.xml';
                        $filename = 'CopyPress-' . time();  
                        $reg_str = array('/\//', '/\\\/', '/\*/', '/\?/', '/\:/', '/\"/', '/\</', '/\>/', '/\|/', '/\s/');
                        $filename = preg_replace( $reg_str, '_', $filename ) . $suffix;
                        header('Content-Disposition: attachment; filename='. $filename  );
                        $arr = $obj->downloadArticles($row, $campaign_id, $api_info);
                        // $replydata = $result;
                        // $result = array_merge($result, $arr);
                        $replydata = array();
                        foreach ($arr as $k => $row) {
                            /*if (isset($row['length'])) unset($row['length']);
                            if (isset($row['htmlBody'])) unset($row['htmlBody']);
                            if (isset($row['textBody'])) unset($row['textBody']);*/
                            $replydata[] = $row;
                        }
                        break;
                    }
                    if (isset($replydata) && !empty($replydata)) {
                        $xml_data = generateXmlArray($xml_data, $replydata, $item_name);
                    } else {
                        $xml_data = generateXmlArray($xml_data, $result, $item_name);
                    }
                }
            }
        }
        return  $this->generateXMLAdvance($xml_data);
    }

    function _itemTag($k)
    {
        return $k = str_replace('_', '', $k);
    }

    function generateXML($data, $item_name = 'articlestatus')
    {
        echo $this->generateXMLAdvance(array('articlestatus' => $data));
        //echo $this->_generateXML($data, $item_name);
    }

    function generateXMLAdvance($data)
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<sssReply>';
        if (!empty($data)) {
            $xml .= $this->__generateXMLAdvance($data);
        } else {
            $xml .= "<message>No Data</message>";
        }
        $xml .= '</sssReply>';
        return $xml;
    }

    function __encodeXmlValue($tag, $value, $not_str_fields)
    {
        if (!is_numeric($value) && !in_array($tag, $not_str_fields) && !empty($value)) {
            $xml_str = '<![CDATA[' . $value . ']]>';
        } else {
            $xml_str = $value;
        }
        return $xml_str;
    }

    function __generateXMLAdvance($data)
    {
        $xml = '';
        $not_str_fields = array('status', 'estimateddate', 'timestamp');
        foreach ($data as $k => $rows) {
            if (!empty($rows)) {
                $k = $this->_itemTag($k);
                if (is_array($rows)) { 
                    foreach ($rows as $subk => $item) {
                        if (is_array($item)) {
                            $xml .= '<' . $k.'>';
                            foreach ($item as $field => $value) {
                                $tag = $this->_itemTag($field);
                                if (is_array($value)) {
                                    $rs = $value;
                                    $is_recursive = true;
                                    if (isset($value['results']) && !empty($value['results'])) {
                                        $rs = $value['results'];
                                    } else {
                                        $ks = array_keys($rs);
                                        if (isset($ks[0]) && is_numeric($ks[0]) && !is_array($rs[$ks[0]])) {
                                            $is_recursive = false;
                                        }
                                    } 
                                    if ($is_recursive) {
                                        $xml_str = $this->__generateXMLAdvance($rs);
                                    }
                                } else{
                                    $xml_str = $this->__encodeXmlValue($tag, $value, $not_str_fields);
                                }
                                if (is_array($value) && !$is_recursive) {
                                   foreach ($value as $vsub) {
                                       $xml_str = $this->__encodeXmlValue($tag, $vsub, $not_str_fields);
                                       $xml .= '<' . $tag . '>' . $xml_str . '</' . $tag . '>';
                                   }
                                } else {
                                    $xml .= '<' . $tag . '>' . $xml_str . '</' . $tag . '>';
                                }
                            }
                            $xml .= '</' . $k.'>';
                        } else {
                            $tag = $this->_itemTag($subk);
                            if (!is_numeric($item) && !in_array($tag, $not_str_fields)) {
                                $xml_str = '<![CDATA[' . $item . ']]>';
                            } else {
                                $xml_str = $item;
                            }
                            $xml .= '<' . $tag . '>' . $xml_str . '</' . $tag . '>';
                        }
                    }
                }
            }
        }
        return $xml;
    }

    function _generateXML($data, $item_name = 'articlestatus')
    {
        global $feedback;
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= '<sssReply>';
        if (!empty($data)) {
            foreach ($data as $row) {
                $xml .= '<' . $item_name.'>';
                foreach ($row as $k => $v) {
                    $tag = $this->_itemTag($k);
                    if (!is_numeric($v) && $tag != 'status' && $tag != 'estimateddate') {
                        $v = '<![CDATA[' . $v . ']]>';
                    }
                    $xml .= '<' . $tag . '>' . $v . '</' . $tag . '>';
                }
                $xml .= '</' . $item_name . '>';
            }
        } else {
            $xml .= "<message>No Data</message>";
        }
        $xml .= '</sssReply>';
        return $xml;
    }
}

function generateXmlArray($xml_data, $data, $item_name = 'articlestatus')
{
    if (!isset($xml_data[$item_name])) $xml_data[$item_name]  = array();
    $xml_data[$item_name] = array_merge($xml_data[$item_name], $data);
    return $xml_data;
}
?>
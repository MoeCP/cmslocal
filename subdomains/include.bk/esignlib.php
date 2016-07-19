<?php
class ESignLib {
    private $soap = null;
    private $config = null;
    private $apiKey = null;
    private $title = null;
    private $docs = null;
    private $all_docs = null;
    private $username = null;
    private $pwd = null;
    private $updateInterval = 43200;
    private $isUpdated = false;
    public $fields = null;
    function __construct($config)
    {
        $this->ESignLib($config);
    }

    function ESignLib($config)
    {
        $this->config = $config;
        $this->soap = new SOAPClient($config['esign_url']);
        $this->apiKey = $config['api_key'];
        $this->title = $config['gtitle'];
        $this->username = $config['username'];
        $this->pwd = $config['pwd'];
    }

    function getLibDocs($force = false)
    {
        $time = $this->config['params']['libUpdated'];
        $time = 0;
        if ((time() - $time) > $this->updateInterval || $force) {
            $param = array(
                'apiKey'=> $this->apiKey,
                'userCredentials' => array(
                'email' => $this->username
                )
             );
            $obj = $this->soap->getLibraryDocumentsForUser($param)->getLibraryDocumentsForUserResult;
            if ($obj->success == 1) {
                $docs= $obj->libraryDocuments->DocumentLibraryItem;
                $this->docs = array();
                foreach ($docs as $arr) {
                    $tmp = (array) $arr;
                    if (in_array($arr->name, $this->config['docs'])) {
                        $tmp['checked'] = 1;
                        $this->docs[$arr->scope][] = $tmp;
                    }
                   $this->all_docs[$arr->scope][] = $tmp;
                }
                $this->isUpdated = true;
            }
        }
        if (empty($this->docs)) $this->docs = $this->config['libs'];
        return $this->docs;
    }

    function getAllDoc()
    {
        return $this->all_docs;
    }

    function getIsUpdated()
    {
        return $this->isUpdated;
    }


    function send($p, $keys)
    {
        $param = array(
        'apiKey' => $this->apiKey,
        'documentCreationInfo' => array(
                'fileInfos' => array(
                    'FileInfo' => $keys
                ),
                'message' => $p['message'],
                'name'    => $p['title'],
                'signatureFlow' => "SENDER_SIGNATURE_NOT_REQUIRED",
                'signatureType' => "ESIGN",
                'tos' => array( $p['email'] ),
            ),
        );
        $r = $this->soap->sendDocument(
            $param
        );
        return $r->documentKeys->DocumentKey->documentKey;
    }

    function getLatestDoc($doc_key, $user_id,  $filename)
    {
        global $BASE_PATH;
        $dir = BASE_PATH . DS . 'storage' . DS  . 'esign' . DS;
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        }
        $dir .= $user_id . DS;
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        }

        $param = array(
            'apiKey' => $this->apiKey,
            'documentKey' => $doc_key,
        );
        $r = $this->soap->getLatestDocument($param)->pdf;
        file_put_contents($dir . $filename, $r);
    }

    function getFormData($doc_key)
    {
        $param = array(
            'apiKey' => $this->apiKey,
            'documentKey' => $doc_key,
        );
        $r = $this->soap->getFormData($param)->getFormDataResult;
        $data = array();
        if ($r->success) {
            $result = $r->formDataCsv;
            $rows = explode("\n",  $result);
            $temp = array();
            foreach ($rows as $row) {
                $row = trim($row, '",');
                $temp[] = explode('","', $row);
            }
            foreach ($temp[0] as $k => $v) {
                $data[$v] = $temp[1][$k];
            }
        }
        return $data;
    }

    function getInfo($doc_key, $p)
    {
        global $g_estatuses;
        $param = array(
            'apiKey' => $this->apiKey,
            'documentKey' => $doc_key,
        );
        $r = $this->soap->getDocumentInfo($param)->documentInfo;
        $status = str_replace('_', ' ', $r->status);
        $status = ucwords(strtolower($status));
        $key = array_search($status, $g_estatuses);
        $events = $r->events->DocumentHistoryEvent;
        $latestDocKey = $r->latestDocumentKey;
        $m_created = $p['max_created'];
        $esign = $logs = array();
        $estatus = $p['estatus'];
        $esign_id = $p['esign_id'];
        foreach ($events as $event) {
            $event = (array) $event;
            $date = date("Y-m-d H:i:s", strtotime($event['date']));
            unset($event['date']);
            $event['created'] = $date;
            $docVersionKey = $event['documentVersionKey'];
            if (empty($latestDocKey) || $docVersionKey == $latestDocKey) {
                $event['estatus'] = $key;
                if ($key != $estatus) {
                    if (!empty($latestDocKey)) $esign['latest_doc_key'] = $latestDocKey;
                    switch($key) {
                    case 7:
                        $esign['signed'] = $date;
                        break;
                    case 12:
                        $esign['archived'] = $date;
                        break;
                    case -1:
                        $esign['cancelled'] = $date;
                        break;
                    case 0:
                        $esign['removed'] =$date;
                        break;
                    }
                }
            } else {
                 $event['estatus'] = $estatus;
            }
            if ($m_created < $date) {
                $event['esign_id'] = $esign_id;
                $logs[] = $event;
            }
        }
        if ($key != $estatus) {
            $esign['estatus'] = $key;
        }
        $result = array();
        if (!empty($esign)) {
            $esign['esign_id'] = $esign_id;
            $result['esign'] = $esign;
        }
        if (!empty($logs)) {
            $result['logs'] = $logs;
        }
        return $result;
    }
        
}
/*
$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
// get input
array_shift($_SERVER['argv']);
$Url	= array_shift($_SERVER['argv']);
$ApiKey	= array_shift($_SERVER['argv']);
$cmd	= array_shift($_SERVER['argv']);
$params	= $_SERVER['argv'];

if (!$cmd) print_usage();

$S = new SOAPClient($Url);
call_user_func("cmd_$cmd", $params);

function cmd_test() {
    global $Url, $ApiKey, $S, $dir;

    print "Testing basic connectivity...\n";
    $r = $S->testPing(array('apiKey' => $ApiKey));
    print "Message from server: {$r->documentKey->message}\n";
    print "Testing file transfer...\n";
    $text = file_get_contents($dir . 'W-9.pdf') . file_get_contents($dir . 'test.pdf');
    $r = $S->testEchoFile(array('apiKey' => $ApiKey, 'file' => base64_encode($text)))->outFile;

    if (base64_decode($r) === $text) {
        print "Woohoo! Everything seems to work.\n";
    }
    else {
        die("ERROR: Some kind of problem with file transfer, it seems.\n");
    }
}

function cmd_send() {
    global $Url, $ApiKey, $S, $dir;

    list($filename, $filename2, $recipient) = reset(func_get_args());

    $r = $S->sendDocument(array(
        'apiKey' => $ApiKey,
        'documentCreationInfo' => array(
                'fileInfos' => array(
                    'FileInfo' => array(
                        array('file'     => file_get_contents($dir . $filename),
                        'fileName' => $filename),
                        array(
                        'file'     => file_get_contents($dir . $filename2),
                        'fileName' => $filename2),
                    ),
                ),
                'message' => "This is neat.",
                'name'    => "Test from SOAP-Lite: $filename",
                'signatureFlow' => "SENDER_SIGNATURE_NOT_REQUIRED",
                'signatureType' => "ESIGN",
                'tos' => array( $recipient ),
            ),
        )
    );

    print "Document key is: {$r->documentKeys->DocumentKey->documentKey}\n";
}

function cmd_info() {
    global $Url, $ApiKey, $S;

    list($doc_key) = reset(func_get_args());
    $r = $S->getDocumentInfo(array('apiKey' => $ApiKey, 'documentKey' => $doc_key))->documentInfo;

    print "Document is in status: {$r->status}\n";
    print "Document History: ";
    foreach($r->events->DocumentHistoryEvent as $_) {
        $keytext =
          $_->documentVersionKey
          ? " (versionKey: {$_->documentVersionKey})"
          : '';
        print "{$_->description} on {$_->date}$keytext\n";
    }
    print "Latest versionKey: {$r->latestDocumentKey}\n";
}

function cmd_latest() {
    global $Url, $ApiKey, $S;

    list ($doc_key, $filename) = reset(func_get_args());
    $r = $S->getLatestDocument(array('apiKey' => $ApiKey, 'documentKey' => $doc_key))->pdf;
    //$r = base64_decode($r);
    file_put_contents($filename, $r);
}

function print_usage() {
    die(<<<__USAGE__
Usage:
  demo.php <URL> <API key> <function> [parameters]

where the function is one of:
  test
  send <filename> <recipient_email>
  info <documentKey>
  latest <documentKey> <filename>

test will run basic tests to make sure you can communicate with the web service
send will create a new agreement in the EchoSign system, and returns a documentKey
info returns the current status and all the history events for a given documentKey
latest saves the latest version of the document as a PDF with the given filename

__USAGE__
);
}
*/

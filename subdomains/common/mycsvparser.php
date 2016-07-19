<?php
require_once "parsecsv.lib.php";
/**
 * Base class for other PEAR classes.  Provides rudimentary
 * emulation of destructors.
 *
 * If you want a destructor in your class, inherit PEAR and make a
 * destructor method called _yourclassname (same name as the
 * constructor, but with a "_" prefix).  Also, in your constructor you
 * have to call the PEAR constructor: $this->PEAR();.
 * The destructor method will be called without parameters.  Note that
 * at in some SAPI implementations (such as Apache), any output during
 * the request shutdown (in which destructors are called) seems to be
 * discarded.  If you need to get any debug information from your
 * destructor, use error_log(), syslog() or something similar.
 *
 * IMPORTANT! To use the emulated destructors you need to create the
 * objects by reference: $obj =& new PEAR_child;
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V.V. Cox <cox@idecnet.com>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 1.4.5
 * @link       http://pear.php.net/package/PEAR
 * @see        PEAR_Error
 * @since      Class available since PHP 4.0.2
 * @link        http://pear.php.net/manual/en/core.pear.php#core.pear.pear
 */
class MyCSVParser
{
    // {{{ properties

    /**
     * CSV configurate parameters
     *
     * @var     array
     * @access  public
     */
    var $conf = array();

    /**
     * Parsed file name
     *
     * @var     string
     * @access  public
     */
    var $file = array();
    
    /**
     * Whether write data to file or not
     *
     * @var     bool
     * @access  public
     */
    var $write = false;

    /**
     * Header
     *
     * @var     string
     * @access  public
     */
    var $header = array();
    var $parser = null;
    // }}}

    // {{{ constructor

    /**
     * Constructor.  Registers this object in
     * $_PEAR_destructor_object_list for destructor emulation if a
     * destructor object exists.
     *
     * @param string $error_class  (optional) which class to use for
     *        error objects, defaults to PEAR_Error.
     * @access public
     * @return void
     */
    function MyCSVParser($param = array())
    {
        if (isset($param['file'])) $this->file = $param['file'];
        $this->parser = new parseCSV();
        $this->parser->auto($this->file);
    }

    // }}}

    // {{{ getFirstLine()

    /**
     * get first line
     *
     * @return array
     */
    function getFirstLine()
    {
        return $this->parser->titles;
    }
    // }}}


    // {{{ getAllData()

    /**
     * get all data
     *
     * @return array
     */
    function getAllData()
    {
        $data = array();

        $this->header = $this->getFirstLine();
        $arr = $this->parser->data;
        foreach ($arr as $row) {
            foreach ($row as $k => $v) {
                if (!isset($data[$k])) $data[$k] = array();
                $data[$k][] = $v;
            }
        }
        return $data;
    }
    // }}}


    // {{{ getAllData()

    /**
     * write CSV
     *
     * @return array
     */
    function writeCSV($file, $data)
    {
        $conf = $this->conf;
        if (!$fp = File_CSV::getPointer($file, $conf, FILE_MODE_WRITE)) {
            return false;
        }
        $write = '';
        foreach ($data as $fields) {
            $field_count = count($fields);
            if ($field_count != $conf['fields']) {
                continue;
            }
            for ($i = 0; $i < $field_count; ++$i) {
                // only quote if the field contains a sep
                if (!is_numeric($fields[$i]) && $conf['quote']
                    && isset($conf['sep']) && strpos($fields[$i], $conf['sep'])
                ) {
                    $fields[$i] = str_replace('"', '""', $fields[$i]);
                    $write .= $conf['quote'] . $fields[$i] . $conf['quote'];
                } else {
                    $write .= $fields[$i];
                }

                $write .= ($i < ($field_count - 1)) ? $conf['sep'] : $conf['crlf'];
            }
        }
        if (!fwrite($fp, $write, strlen($write))) {
            return File_CSV::raiseError('Can not write to file');
        }
    }
    // }}}
}

/*
 * Local Variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */
?>

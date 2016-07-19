<?php
//add by liu shu fen 15:25 2007-12-6
//database table : manual_content
Class CpCandidate  {
    private $candidate_id;
    private $first_name;
    private $last_name;
    private $confidentional_info;
    private $birthday;
    private $email;
    private $pwd;
    private $from_where;
    private $country;
    private $address;
    private $opt_address;
    private $zipcode;
    private $state;
    private $city;
    private $writing_sample;
    private $resume_file;
    private $is_active;
    private $is_sent;
    private $created;

    private function _construct() {
        $candidate_id= null;
        $first_name= null;
        $last_name= null;
        $confidentional_info= null;
        $birthday= null;
        $email= null;
        $pwd= null;
        $from_where= null;
        $country= null;
        $address= null;
        $opt_address= null;
        $zipcode= null;
        $state= null;
        $city= null;
        $writing_sample= null;
        $resume_file= null;
        $is_active= null;
        $is_sent= null;
        $created= null;
    }
    function CpCandidate()
    {
        $this->__construct();
    }

    /**
     * Search user info.,
     *
     * @param array $p  the form submited value.
     *
     * @return array
     * @access public
     */
    function search($p = array())
    {
        global $conn, $feedback;
        global $g_pager_params;

        $q = "WHERE 1 ";

        $rs = &$conn->Execute("SELECT COUNT(cpc.candidate_id) AS count FROM `cp_candidates` AS cpc " . $q);
        if ($rs) {
            $count = $rs->fields['count'];
            $rs->Close();
        }

        if ($count == 0 || !isset($count)) {
            return false;
        }

        $perpage = 50;
        if (trim($_GET['perPage']) > 0) {
            $perpage = $_GET['perPage'];
        }

        require_once 'Pager/Pager.php';
        $params = array('perPage'=> $perpage,
                        'totalItems' => $count );
        $pager = &Pager::factory(array_merge($g_pager_params, $params));

        $q = "SELECT cpc.* " . "FROM `cp_candidates` AS cpc " . $q;

        list($from, $to) = $pager->getOffsetByPageId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            while (!$rs->EOF) {
                $result[$i] = $rs->fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }

        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'result' => $result);

    }
    /**
     * Helper method - get whole fields data by candidate id
     * @param $p array
     * @return $info(array)/null
     */
    function getCandidateById($p) {
        global $conn;
        require_once CMS_INC_ROOT . '/Pref.class.php';

        $qw[] = " WHERE 1 ";

        if (isset($p['candidate_id']) && !empty($p['candidate_id'])) {
            if (is_array($p['candidate_id'])) {
                $qw[] = " candidate_id IN (" . implode(",", $p['candidate_id']) . ")";
            } else {
                $qw[] = " candidate_id=" . trim($p['candidate_id']);
            }
        }

        $sql = "SELECT * FROM cp_candidates ";

        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }

        $r = $conn->getAll($sql);

        if ($r) {
            foreach ($r as $cp){
                $info = $cp;
                $from_where = Preference::getPrefById($cp['from_where']);
                $country = Preference::getPrefById($cp['country']);
                $state = Preference::getPrefById($cp['state']);
                $info['from_where'] = $from_where['pref_value'];
                $info['country'] = $country['pref_value'];
                $info['state'] = $state['pref_value'];
            }
            return $info;
        } else {
            return null;
        }
    }
}
?>

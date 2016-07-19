<?php
require_once CMS_INC_ROOT.'/Category.class.php';
class Candidate
{
	function __construct()
	{

	}

    function Candidate()
    {
        $this->__construct();
    }

    function save($bind)
    {
        global $conn, $feedback;

        $user_id = isset($bind['user_id']) ? $bind['user_id'] : 0;
        if (isset($bind['user_id'])) unset($bind['user_id']);
        if (empty($bind['first_name'])) {
            $feedback = 'Please specify first name';
            return false;
        }

        if (empty($bind['last_name'])) {
            $feedback = 'Please specify last name';
            return false;
        }
        if (empty($bind['email'])) {
            $feedback = 'Please specify email';
            return false;
        }

        if (isset($bind['sex']) &&empty($bind['sex'])) {
            $feedback = 'Please specify sex';
            return false;
        }

        if (isset($bind['country']) && empty($bind['country'])) {
            $feedback = 'Please specify country';
            return false;
        } else if (!isset($bind['country'])) {
            $bind['country'] = '';
        }
        if (isset($bind['dob']) && empty($bind['dob'])) {
            $feedback = 'Please specify birthday';
            return false;
        } else if (!isset($bind['dob'])) {
            $bind['dob'] = '';
        }

        if (isset($bind['hear_from']) && empty($bind['hear_from'])) {
            $feedback = 'Please specify How did you hear about us?';
            return false;
        } else if (!isset($bind['hear_from'])) {
            $bind['hear_from'] = '';
        }

      foreach ($bind as $k => $row) {
            switch($k) {
            case 'education':
            case 'experience':
            case 'writing_background':
            case 'categories':
            case 'samples':
            case 'plinks':
                $bind[$k] = self::disposeArr($row, $k);
                break;
            default:
                $bind[$k] = $row;
                break;
           }
        }

        foreach($bind as $k => $value) {
            if (is_array($value)) {
                $bind[$k] = addslashes(serialize($value));
            } else {
                $bind[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }

        $candidate_id = $bind['candidate_id'];
        $result = self::__getResult(array('email' => $bind['email']));
        if (!empty($result)) {
            $result = $result[0];
            if ($candidate_id > 0 && $candidate_id == $result['candidate_id']) {
            } else {
                $feedback = 'Duplicated email, please to check';
                return false;
            }
        }
        
        
        $conn->StartTrans();
        if (empty($candidate_id)) {
            $id = $conn->GenID('seq_candidates_candidate_id');
            $bind['candidate_id'] = $id;
            $bind['date_applied'] = date("Y-m-d H:i:s");
            $values   = "'". implode("', '", $bind) . "'";
            $bind_keys = array_keys($bind);
            $fields    = "`" . implode("`, `", $bind_keys) . "`";
            $sql  = "INSERT INTO `candidates` ({$fields}) VALUES ({$values}) ";            
        } else {
            $id = $candidate_id;
            unset($bind['candidate_id']);
            $sql = "UPDATE `candidates` set ";
            $sets = array();
            foreach ($bind as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE candidate_id=' . $id;
        }
        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? $id : false;
        if ($result > 0 && $user_id > 0) {
            $sql = "UPDATE users SET candidate_id=" . $id . ' WHERE user_id=' . $user_id;
            $conn->Execute($sql);
        }
        $feedback = $result ? 'Success' : 'Failure, Please try agian';
        return $result;
    }

    public function saveBasic($bind)
    {
        global $conn, $feedback;
        $user_id = isset($bind['user_id']) ? $bind['user_id'] : 0;
        if (isset($bind['user_id'])) unset($bind['user_id']);
        if (empty($bind['first_name'])) {
            $feedback = 'Please specify first name';
            return false;
        }

        if (empty($bind['last_name'])) {
            $feedback = 'Please specify last name';
            return false;
        }
        if (empty($bind['email'])) {
            $feedback = 'Please specify email';
            return false;
        }

        if (isset($bind['sex']) &&empty($bind['sex'])) {
            $feedback = 'Please specify sex';
            return false;
        }

        if (isset($bind['country']) && empty($bind['country'])) {
            $feedback = 'Please specify country';
            return false;
        } else if (!isset($bind['country'])) {
            $bind['country'] = '';
        }
        if (isset($bind['dob']) && empty($bind['dob'])) {
            $feedback = 'Please specify birthday';
            return false;
        } else if (!isset($bind['dob'])) {
            $bind['dob'] = '';
        }

        if (isset($bind['hear_from']) && empty($bind['hear_from'])) {
            $feedback = 'Please specify How did you hear about us?';
            return false;
        } else if (!isset($bind['hear_from'])) {
            $bind['hear_from'] = '';
        }
/*        $plinks = array();
        if (isset($bind['plinks']) &&  !empty($bind['plinks'])) {
            $types = $bind['plinks']['type'];
            $values = $bind['plinks']['value'];
            foreach ($types as $k => $t) {
                $t = trim($t);
                $v = trim($values[$k]);
                if (empty($v) && empty($t)) {
                    unset( $bind['plinks']['type'][$k]);
                    unset( $bind['plinks']['value'][$k]);
                } else if (empty($t)) {
                    $feedback = 'Please choose personal link type';
                    return false;
                } else if (empty($t)) {
                    $feedback = 'Please specify personal link';
                    return false;
                } else {
                    $plinks[$k] = array('type' => $t, 'value' => $v);
                }
            }
            $bind['plinks'] = $plinks;
        }*/

        foreach ($bind as $k => $row) {
            switch($k) {
            case 'education':
            case 'experience':
            case 'writing_background':
            case 'categories':
            case 'samples':
            case 'plinks':
                $bind[$k] = self::disposeArr($row, $k);
                break;
            default:
                $bind[$k] = $row;
                break;
           }
        }

        foreach($bind as $k => $value) {
            if (is_array($value)) {
                $bind[$k] = addslashes(serialize($value));
            } else {
                $bind[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }
        $candidate_id = $bind['candidate_id'];
        $result = self::__getResult(array('email' => $bind['email']));
        if (!empty($result)) {
            $result = $result[0];
            if ($candidate_id > 0 && $candidate_id == $result['candidate_id']) {
            } else {
                $feedback = 'Duplicated email, please to check';
                return false;
            }
        }
        
        
        $conn->StartTrans();
        if (empty($candidate_id)) {
            $id = $conn->GenID('seq_candidates_candidate_id');
            $bind['candidate_id'] = $id;
            $bind['date_applied'] = date("Y-m-d H:i:s");
            $values   = "'". implode("', '", $bind) . "'";
            $bind_keys = array_keys($bind);
            $fields    = "`" . implode("`, `", $bind_keys) . "`";
            $sql  = "INSERT INTO `candidates` ({$fields}) VALUES ({$values}) ";            
        } else {
            $id = $candidate_id;
            unset($bind['candidate_id']);
            $sql = "UPDATE `candidates` set ";
            $sets = array();
            foreach ($bind as $k => $v) {
                $sets[] = "{$k}='{$v}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE candidate_id=' . $id;
        }
        $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? $id : false;
        if ($result > 0 && $user_id > 0) {
            $sql = "UPDATE users SET candidate_id=" . $id . ' WHERE user_id=' . $user_id;
            $conn->Execute($sql);
        }
        $feedback = $result ? 'Success' : 'Failure, Please try agian';
        return $result;
    }

    function saveInfo($bind)
    {
        global $feedback,$conn;
        $feedback = 'Success';
        $data = array();
        $cid = $bind['candidate_id'];
        unset($bind['candidate_id']);
        foreach ($bind as $k => $row) {
            switch($k) {
            case 'education':
            case 'experience':
            case 'writing_background':
            case 'categories':
            case 'samples':
            case 'plinks':
                $data[$k] = self::disposeArr($row, $k);
                break;
            default:
                $data[$k] = $row;
                break;
           }
        }
        foreach($data as $k => $value) {
            if (is_array($value)) {
                $data[$k] = addslashes(serialize($value));
            } else {
                $data[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }

        $sql = "UPDATE `candidates` ";
        $sets = array();
        foreach ($data as $k => $v) {
            $sets[] = "{$k}='{$v}'";
        }
        if (!empty($sets)) {
            if ($cid > 0) {
                $sql .= ' SET ' . implode(",", $sets);
                $sql .= ' WHERE candidate_id=' . $cid;
                // echo $sql;
                $conn->Execute($sql);
                return $cid;
            } else {
                $feedback = 'Please specify candidate';
                return false;
            }
        } else {
            $feedback = 'Failure, please try again';
            return false;
        }
    }

    function disposeArr($p, $field)
    {
        extract($p);
        $arr = array();
        $hint = str_replace('_', ' ', $field);
        $fields = array_keys($p);
        foreach ($p[$fields[0]] as $k => $v) {
            if (!empty($v) && $v != 'Select' || $field  == 'samples') {
                if (empty($p[$fields[1]])) {
                    if ($field == 'categories'){
                        $hint = 'Sub category ';
                    } 
                    $feedback = 'Please specify ' . $hint .' for ' . $v;
                    return false;
                } else if ($field == 'categories' && (empty($p[$fields[2]]) || empty($p[$fields[3]]) || empty($p[$fields[4]]) )) {
                    $feedback = 'Please choose specialty level for ' . $fields[1];
                    return false;
                } else {
                   $data = array();
                   foreach ($fields as $fk => $fv) {
                       $hv =  $p[$fv][$k];
                       if (is_string($hv))  {
                           $hv = trim($hv);
                           if (($fv == 'link' || $field == 'plinks' && $fv == 'value')) {
                               $hv = (strcmp($hv, 'http://www.') == 0) ? '' : generateValidateUrl($hv);
                           }
                       } 
                       $data[$fv] = $hv;
                   }
                   $arr[$k] = $data;
                }
            }
        }
        return $arr;
    }

    public function add($bind)
    {    
        global $conn, $feedback;
        if (empty($bind['first_name'])) {
            $feedback = 'Please specify first name';
            return false;
        }
        if (empty($bind['last_name'])) {
            $feedback = 'Please specify last name';
            return false;
        }
        if (empty($bind['email'])) {
            $feedback = 'Please specify email';
            return false;
        }
        if (empty($bind['country'])) {
            $feedback = 'Please specify country';
            return false;
        }
        if (empty($bind['dob'])) {
            $feedback = 'Please specify birthday';
            return false;
        }
        if (empty($bind['education'])) {
            $feedback = 'Please specify education';
            return false;
        }
        if (empty($bind['field_of_study'])) {
            $feedback = 'Please specify area of expertise';
            return false;
        }
        if (empty($bind['experience'])) {
            $feedback = 'Please specify experience';
            return false;
        }
        if (empty($bind['writing_background'])) {
            $feedback = 'Please specify writing background';
            return false;
        }
        /*if (empty($bind['productivity'])) {
            $feedback = 'Please specify Weekly Productivity';
            return false;
        }*/
        if (empty($bind['first_language'])) {
            $feedback = 'Please specify first language';
            return false;
        }
        if (empty($bind['weekly_hours'])) {
            $feedback = 'Please specify Weekly Hours';
            return false;
        }
        if (empty($bind['hear_from'])) {
            $feedback = 'Please specify How did you hear about us?';
            return false;
        }
        if (!empty($bind['published_work'])) {
            $links = explode("\n", trim($bind['published_work']));
            if (count($links) > 5) {
                $feedback = 'There are more than 5 links to published work, please to check.';
                return false;
            }
        }
        if (empty($bind['writing_sample'])) {
            $feedback = 'Please specify Writing Sample';
            return false;
        } else {
             // remove this requirement.
            /*$total = str_word_count(trim($bind['writing_sample']));
            if ($total > 500) {
                $feedback = 'There are more than 500 words of Writing Sample, please to check.';
                return false;
            }*/
        }

        if (isset($bind['categories']) && !empty($bind['categories'])) {
            $categories = $bind['categories'];
            foreach ($categories as $k => $arr) {
                if ($arr['level'] == 2 && empty($arr['description'])) {
                    $feedback = "Please add descripton for " . $arr['category'];
                    return false;
                }
            }
        }
        foreach($bind as $k => $value) {
            if (is_array($value)) {
                $bind[$k] = addslashes(serialize($value));
            } else {
                $bind[$k] = addslashes(htmlspecialchars(trim($value)));
            }
        }
        $result = self::__getResult(array('email' => $bind['email']));
        if (!empty($result)) {
            $feedback = 'Duplicated email, please to check';
            return false;
        }
        $id = $conn->GenID('seq_candidates_candidate_id');
        $bind['candidate_id'] = $id;
        $bind['date_applied'] = date("Y-m-d H:i:s");
	    $values   = "'". implode("', '", $bind) . "'";
		$bind_keys = array_keys($bind);
		$fields    = "`" . implode("`, `", $bind_keys) . "`";
        $conn->StartTrans();
        $sql  = "INSERT INTO `candidates` ({$fields}) VALUES ({$values}) ";
	    $conn->Execute($sql);
        $ok = $conn->CompleteTrans();
        $result = $ok > 0 ? true : false;
        $feedback = $result ? 'Success' : 'Failure, Please try agian';
        if ($result) {
            self::sentEmail($bind, 16);
        }
        return $result;
    }

    function sentEmail($bind, $event_id = 16)
    {
        $info = Email::getInfoByEventId($event_id);
        $subject = html_entity_decode($info['subject']);
        $body = nl2br($info['body']);
        return send_smtp_mail($bind['email'], $subject, $body);
    }

    public function update($bind)
    {
        global $conn, $feedback;

        if (isset($bind['first_name']) && empty($bind['first_name'])) {
            $feedback = 'Please specify first name';
            return false;
        }
        if (isset($bind['last_name']) && empty($bind['last_name'])) {
            $feedback = 'Please specify last name';
            return false;
        }
        if (isset($bind['email']) && empty($bind['email'])) {
            $feedback = 'Please specify email';
            return false;
        }
        if (isset($bind['country']) && empty($bind['country'])) {
            $feedback = 'Please specify country';
            return false;
        }
        if (isset($bind['dob']) && empty($bind['dob'])) {
            $feedback = 'Please specify birthday';
            return false;
        }
        if (isset($bind['education']) && empty($bind['education'])) {
            $feedback = 'Please specify education';
            return false;
        }
        if (isset($bind['field_of_study']) && empty($bind['field_of_study'])) {
            $feedback = 'Please specify field of study';
            return false;
        }
        if (isset($bind['experience']) && empty($bind['experience'])) {
            $feedback = 'Please specify experience';
            return false;
        }
        if (isset($bind['writing_background']) && empty($bind['writing_background'])) {
            $feedback = 'Please specify writing background';
            return false;
        }
        if (isset($bind['published_work'])) {
            if (!empty($bind['published_work'])) {
                $links = explode("\n", trim($bind['published_work']));
                if (count($links) > 5) {
                    $feedback = 'There are more than 5 links to published work, please to check.';
                    return false;
                }
            }
        }
        if (isset($bind['writing_sample'])) {
            if (empty($bind['writing_sample'])) {
                $feedback = 'Please specify Writing Sample';
                return false;
            } else {
                $total = str_word_count(trim($bind['writing_sample']));
                if ($total > 500) {
                    $feedback = 'There are more than 500 words of Writing Sample, please to check.';
                    return false;
                }
            }
        }
        
        if (isset($bind['candidate_id']) && $bind['candidate_id']) {
            if (isset( $bind['email'])) {
                $result = self::duplicatedEmail(array('email' => $bind['email'], 'candidate_id' => $bind['candidate_id']));
                if (!empty($result)) {
                    $feedback = 'Duplicated email, please to check';
                    return false;
                }
            }

            $candidate_id = $bind['candidate_id'];
            unset($bind['candidate_id']);
            $sql = ' UPDATE `candidates` set ';
            $sets = array();
            foreach ($bind as $field => $value) {
                $value = addslashes(htmlspecialchars(trim($value)));
                $sets[] = "{$field}='{$value}'";
            }
            $sql .= implode(",", $sets);
            $sql .= ' WHERE candidate_id=' . $candidate_id;
            $rs = &$conn->Execute($sql);
            
            $feedback = 'Success';
            if ($bind['status'] == 'rejected') {
                $data = self::__getResult(array('candidate_id' => $candidate_id));
                $data = $data[0];
                self::sentEmail($data, 17);
            }
            return true;
        }
        return false;
    }

    function updateResumeFile($id, $resume_file)
    {
        global $conn;
        $sql = ' UPDATE `candidates` SET resume_file=\'' . $resume_file. '\' WHERE candidate_id = ' . $id;
        $rs = &$conn->Execute($sql);
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
        if (isset($p['keyword']) && !empty($p['keyword'])) {
            $q .= ' AND CONCAT(cpc.first_name, cpc.last_name, cpc.email) LIKE \'%' .  trim($p['keyword']) . '%\'';
        }
        if (isset($p['status']) && !empty($p['status'])) {
            $q .= ' AND cpc.status=\'' .$p['status'] . '\'';
        }
        if (isset($p['education']) && !empty($p['education'])) {
            $str = getSerializeSearch('degree', $p['education']);
            $q .= ' AND cpc.education LIKE \'%' .$str . '%\'';
        }
        if (isset($p['country']) && !empty($p['country'])) {
            $q .= ' AND cpc.country=\'' .$p['country'] . '\'';
        }
        if (isset($p['cpermission']) && !empty($p['cpermission'])) {
            $q .= ' AND cpc.cpermission=\'' .$p['cpermission'] . '\'';
        }
        if (isset($p['experience']) && !empty($p['experience'])) {
            $str = getSerializeSearch('year', $p['experience']);
            $q .= ' AND cpc.experience LIKE \'%' .$str . '%\'';
        }

        if (isset($p['categories']) && !empty($p['categories'])) {
            $p_str = getSerializeSearch('parent_id', $p['categories']);
            $str = getSerializeSearch('category_id', $p['categories']);
            $q .= ' AND (cpc.categories LIKE \'%' .$p_str. '%\'  OR  cpc.categories LIKE \'%' .$str. '%\'  )';
        }
        $rs = &$conn->Execute("SELECT COUNT(cpc.candidate_id) AS count FROM `candidates` AS cpc " . $q);
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

        $q = "SELECT cpc.*  FROM `candidates` AS cpc " . $q . ' ORDER BY candidate_id DESC ';

        list($from, $to) = $pager->getOffsetByPageId();
        $cate = new Category();
        $cpids = $cate->getIdsByParentId();
        $rs = &$conn->SelectLimit($q, $params['perPage'], ($from - 1));
        if ($rs) {
            $result = array();
            $i = 0;
            //$in_fields = array('education', 'writing_background', 'experience', 'plinks', 'categories', 'samples');
            $in_fields = array('education', 'writing_background', 'experience', 'plinks', 'categories', 'samples', 'writer_level');

            while (!$rs->EOF) {
                $fields = $rs->fields;
                $result[$i] = $fields;
                $rs->MoveNext();
                $i++;
            }
            $rs->Close();
        }
        if (!empty($result)) { 
            foreach ($result as $i => $fields) {
                foreach ($fields as $k =>  $v) {
                    if (in_array($k, $in_fields)) {
                        $v = self::disposeField($v, $fields, $k, $cate, $cpids);
                    }
                    
                    if ($k == 'samples' || $k == 'categories') {
                        $kField =  'is_' . $k. '_doc';
						$fields[$kField] = false;
						if (is_array($v)) {
							foreach ($v as $subk => $item) {
								if (!empty($item['fileField'])){
									if (!$fields[$kField]) $fields[$kField] = true;
								} else {
                                    $v[$subk]['fileField'] = '';
                                }
							}
						}
                    }
                    $fields[$k] = $v;
                }
                $result[$i] = $fields;
            }
        }
        return array('pager'  => $pager->links,
                     'total'  => $pager->numPages(),
                     'count'  => $count,
                     'result' => $result);

    }

    function getAllByParam($p = array()) 
    {
        global $conn, $feedback;
        global $g_pager_params, $g_user_levels;

        $q = "WHERE 1 ";
        if (isset($p['keyword']) && !empty($p['keyword'])) {
            $q .= ' AND CONCAT(cpc.first_name, cpc.last_name) LIKE \'%' .  trim($p['keyword']) . '%\'';
        }
        if (isset($p['status']) && !empty($p['status'])) {
            $q .= ' AND cpc.status=\'' .$p['status'] . '\'';
        }
        if (isset($p['education']) && !empty($p['education'])) {
            $str = getSerializeSearch('degree', $p['education']);
            $q .= ' AND cpc.education LIKE \'%' .$str . '%\'';
        }
        if (isset($p['country']) && !empty($p['country'])) {
            $q .= ' AND cpc.country=\'' .$p['country'] . '\'';
        }
        if (isset($p['cpermission']) && !empty($p['cpermission'])) {
            $q .= ' AND cpc.cpermission=\'' .$p['cpermission'] . '\'';
        }
        if (isset($p['experience']) && !empty($p['experience'])) {
            $str = getSerializeSearch('year', $p['experience']);
            $q .= ' AND cpc.experience LIKE \'%' .$str . '%\'';
        }

        if (isset($p['categories']) && !empty($p['categories'])) {
            $p_str = getSerializeSearch('parent_id', $p['categories']);
            $str = getSerializeSearch('category_id', $p['categories']);
            $q .= ' AND (cpc.categories LIKE \'%' .$p_str. '%\'  OR  cpc.categories LIKE \'%' .$str. '%\'  )';
        }
        $q = "SELECT cpc.*  FROM `candidates` AS cpc " . $q;
        $result = $conn->GetAll($q);
        $in_fields = array('education', 'writing_background', 'experience', 'plinks', 'categories');
        $data_fields = array('first_name', 'last_name', 'categories', 'writing_background');
        $data = array();
        if (!empty($result)) {
            $cate = new Category();
            $cpids = $cate->getIdsByParentId();
            if (!empty($result)) { 
                foreach ($result as $i => $fields) {
                    $tmp = array();
                    foreach ($data_fields as $v) {
                        $tmp[$v] = '';
                    }
                    foreach ($fields as $k =>  $v) {
                        if (in_array($k, $data_fields)) {
                            if (in_array($k, $in_fields)) {
                                $v = self::disposeField($v, $fields, $k, $cate, $cpids);
                                $str = '';
                                if ($k == 'categories') {
                                    foreach ($v as $row) {
                                        $str .= $row['category'] .' - ' . $g_user_levels[$row['level']] . "\n";
                                    }
                                } else {
                                    foreach ($v as $row) {
                                        $str .= implode(" - ", $row) . "\n";
                                    }
                                }
                                $v = $str;
                            }
                            $tmp[$k] = $v;
                        }
                    }
                    $data[] = $tmp;
                }
            }
        }
        return $data;
    }

    function disposeField($v, $result,  $k, $cate, $cpids)
    {
        if (!empty($v)) {
            $row = unserialize($v);
            if (is_array($row)) {
                if ($k == 'categories') {
                    foreach ($row as $ck => $cv) {
                        $category_id = $cv['category_id'];
                        if (!isset($cv['parent_id']) || empty($cv['parent_id'])) {
                            if ($category_id == 8 || $category_id == 14 || in_array($category_id, $cpids)) {
                                  // combine  Arts & Entertainment	 and "Entertainment and Arts" as One category named "Arts & Entertainment"
                                  if ($category_id == 8) $category_id = 1;
                                  // combine Gaming and Hobbies as One category named "Gaming"
                                  else if ($category_id == 14) $category_id = 12;
                                  $row[$ck]['parent_id'] = $category_id;
                                  $row[$ck]['category_id'] = 0;
                            } else {
                                // combine  "Food and Beverages" and Culinary as One category named "Food"
                                if ($category_id == 20) $category_id = 11; 
                                $tmp = $cate->getInfo($category_id);
                                $row[$ck]['category_id'] = $category_id;
                                $row[$ck]['parent_id'] = $tmp['parent_id'];
                            }
                        }
                    }
                }
            } else if ($k == 'experience') {
                $row = array(array('year' => $v));
            } else if ($k == 'education') {
                $row = array(array('degree' => $v, 'major' => $result['field_of_study']));
            } else if ($k == 'writing_background') {
                $row = array();
                $arr = explode("|", $v);
                foreach ($arr  as $v) {
                    $row[] = array('type' => $v);
                }
            }
        } else {
            $row = '';
        }
        return $row;
    }

    public function getInfo($candidate_id)
    {
       $p = array('candidate_id'=>$candidate_id);
       $list = self::__getResult($p);
       $result = $list[0];
       if (!empty($result)) {
            $cate = new Category();
            $cpids = $cate->getIdsByParentId();
       }
       foreach ($result as  $k => $v) {
           switch($k) {
            case 'education':
            case 'experience':
            case 'plinks':
            case 'writing_background':
            case 'categories':
            case 'samples':
            case 'writer_level':
                $result[$k] = self::disposeField($v, $result[$k] ,$k, $cate, $cpids);
                break;
            case 'writing_sample':
                $result[$k] = html_entity_decode($v);
                break;
            default:
                break;
           }
       }
        if (!empty($result['published_work'])) {
            $published_work = explode("\n", $result['published_work']);
            foreach ($published_work as $k => $v) {
                if (!empty($v)) {
                    $result['writing_background'][] = array('type' => 'Published online', 'source' => $v);
                }
            }
        }
		
        $result['is_samples_doc'] = false;
		foreach ($result['samples'] as $item) {
			if (!empty($item['fileField'])) {
				$result['is_samples_doc'] = true;
				break;
			}
		}
        $result['is_categories_doc'] = false;
		foreach ($result['categories'] as $item) {
			if (!empty($item['fileField'])) {
				$result['is_categories_doc'] = true;
				break;
			}
		}
       return $result;
    }
    
    public function getCandidateInfo($candidate_id) 
    {
        if ($candidate_id > 0) {
           $p = array('candidate_id'=>$candidate_id);
           $list = self::__getResult($p);
           if (!empty($list)) {
                $info = $list[0];
                foreach ($info as $k => $v) {
                    switch($k) {
                    case 'education':
                    case 'experience':
                    case 'plinks':
                    case 'writing_background':
                    case 'categories':
                    case 'samples':
                    case 'writer_level':
                        $row = unserialize($v);
                       // pr($row);
                        if (is_array($row)) {
                            $tmp = array();
                            foreach ($row as $subk => $subv) {
                                if (is_array($subv)) {
                                    foreach ($subv as $ssk => $ssv) {
                                        $tmp[$ssk][$subk] = $ssv;
                                    }
                                } else {
                                    $tmp[$subk] = $subv;
                                }
                            }
                            $info[$k] = $tmp;
                        }
                        break;
                    }
                }
                return $info;
            }
        }
        return false;
    }

    function writerLevelChecked($arr, $candidateWriters)
    {
        $hash = array();
        if (!empty($arr)) {
            foreach ($candidateWriters as $k => $v) {
                if (in_array($v, $arr)) {
                    $hash[$k] = $v;
                }
            }
        }
        return $hash;
    }
    
    
    public function getCandidateIdByEmail($email)
    {
       $p = array('email'=>$email);
       $list = self::__getResult($p);
       if (!empty($list)) $candidate_id = $list[0]['candidate_id'];
       else $candidate_id = null;
       return $candidate_id;
    }

    public function store($data)
    {
        if (isset($data['candidate_id']) && !empty($data['candidate_id'])) {
            self::update($data);
        } else {
            self::add($data);
        }
    }

    function duplicatedEmail($p)
    {
        global $conn;
        if (isset($p['candidate_id'])) {
            $candidate_id = addslashes(htmlspecialchars(trim($p['candidate_id'])));
            if(is_numeric($candidate_id) && $candidate_id > 0)
                $condition[] = "candidate_id!={$candidate_id}";
        }
        if (isset($p['email'])) {
            $email = addslashes(htmlspecialchars(trim($p['email'])));
            if(!empty($email))
                $condition[] = "email='{$email}'";
        }

        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        else 
            $qw .= " ORDER BY candidate_id DESC ";
        $sql = " SELECT {$query} FROM `candidates` ";
        $sql .= " WHERE {$qw} ";
        $rs = &$conn->Execute($sql);
		$result = array();
        if ($rs)
		{
            while (!$rs->EOF) 
			{
                $fields = strlen($single_column) ? $rs->fields[$single_column] : $rs->fields;
                if(strlen($index))
                    $result[$index] = $fields;
                else 
                    $result[] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
			return $result;
        }
        else
        {
            return false;
        }
    }

    private function __getResult($p = array())
    {
        global $conn;
        $qw = '';
        if (isset($p['candidate_id'])) {
            $candidate_id = addslashes(htmlspecialchars(trim($p['candidate_id'])));
            if(is_numeric($candidate_id) && $candidate_id > 0)
                $condition[] = "candidate_id={$candidate_id}";
        }
        if (isset($p['email'])) {
            $email = addslashes(htmlspecialchars(trim($p['email'])));
            if(!empty($email))
                $condition[] = "email='{$email}'";
        }

        if(is_array($condition))
            $qw .= implode(" AND ", $condition);
        else 
            $qw .= " 1=1 ";
        $index = addslashes(htmlspecialchars(trim($p['index'])));
        $columns = htmlspecialchars(trim($p['columns']));
        $query = strlen($columns) ? $columns : '*';
        $single_column = addslashes(htmlspecialchars(trim($p['single_column'])));
        $orderby = addslashes(htmlspecialchars(trim($p['orderby'])));
        if(strlen($orderby))
            $qw .= " ORDER BY {$orderby} ";
        else 
            $qw .= " ORDER BY candidate_id DESC ";
        $sql = " SELECT {$query} FROM `candidates` ";
        $sql .= " WHERE {$qw} ";
        $rs = &$conn->Execute($sql);
		$result = array();
        if ($rs)
		{
            while (!$rs->EOF) 
			{
                $fields = strlen($single_column) ? $rs->fields[$single_column] : $rs->fields;
                if(strlen($index))
                    $result[$index] = $fields;
                else 
                    $result[] = $fields;
                $rs->MoveNext();
            }
            $rs->Close();
			return $result;
        }
        else
        {
            return false;
        }
    }
}
?>

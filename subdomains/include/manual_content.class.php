<?php
//add by liu shu fen 15:25 2007-12-6
//database table : manual_content
Class ManualContent {
    private $content_id;
    private $title;
    private $introtext;
    private $full_text;
    private $cat_id;
    private $created;
    private $created_by;
    private $modified;
    private $modified_by;
    private $version;
    private $state;

    private function _construct() {
        $content_id  = 0;
        $title       = null;
        $introtext   = null;
        $full_text   = null;
        $cat_id      = 0;
        $created     = null;
        $created_by  = 0;
        $modified    = null;
        $modified_by = 0;
        $version     = 0;
        $state       = 0;
    }
    function ManualContent()
    {
        $this->__construct();
    }

    function getManualContent($p = array()) {
        global $conn;
        $qw[] = 'WHERE  1 ';

        if (!empty($p['content_id']) && isset($p['content_id'])) {
            if (is_array($p['content_id'])) {
                $qw[] = " content_id IN (" . implode(",", trim($p['content_id'])) . ") ";
            } else {
                $qw[] = " content_id=" . trim($p['content_id']);
            }
        }

        if (!empty($p['title']) && isset($p['title'])) {
            $qw[] = " title='" . htmlspecialchars(addslashes(trim($p['title']))) ."' ";
        }

        if (!empty($p['keyword']) && isset($p['keyword'])) {
            $qw[] = " (title LIKE '%" . htmlspecialchars(addslashes(trim($p['keyword']))) ."%' ".
                    " OR full_text LIKE '%" . htmlspecialchars(addslashes(trim($p['keyword']))) ."%') ";
        } 

        if (!empty($p['category']) && isset($p['category']) && $p['category'] != -1) {
            $qw[] = " category=" . trim($p['category']);
        }
        
        $sql = " SELECT * FROM manual_content ";
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw );
        }
        
        $sql .= " ORDER BY content_id ASC";
        $result = $conn->getAll($sql);
        
        if ($result) {
            foreach ($result as $key => $res) {
                $q = "SELECT first_name, last_name FROM users WHERE user_id=" . $res['created_by'];
                $get = $conn->getAll($q);
                
                if ($get) {
                    foreach ($get as $rs) 
                        $author = $rs['last_name'] . " ". $rs['first_name'];
                    $result[$key]['author'] = $author;
                }

                if ($res['state'] == 0)
                   $str = "Unpublish";
                else if ($res['state'] == 1)
                   $str = "Publish";
                else if ($res['state'] == 2)
                   $str = "Delete";
                else 
                    $str = "Unknown";
                $result[$key]['publish'] = $str;
            }
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } else {
            return null;
        }

    }

    function addManualContent($p) {
        global $conn, $feedback;
        $content = array();
        $sql = '';
        $qw = '';
        if (!empty($p['title'])) {
            $title = htmlspecialchars(addslashes(trim($p['title'])));
            $set[] = " title='" . $title . "' ";
        }
        if (!empty($p['category'])) {
            $category = htmlspecialchars(addslashes(trim($p['category'])));
            $set[] = " category='" . $category . "'";
        }
        if (!empty($p['introtext'])) {
            $introtext = htmlspecialchars(addslashes(trim($p['introtext'])));
            $set[] = " introtext=" . $introtext;
        }
        if (!empty($p['full_text'])) {
            $full_text = htmlspecialchars(addslashes($p['full_text']));
            $set[] = " full_text='" . $full_text ."'";
        }
        $created = empty($p['created']) ? date("Y-m-d H:i:s") : $p['created'];
        
        $set[] = " state=" . trim($p['state']);
        $sql = "";
        if (isset($p['content_id']) && !empty($p['content_id']) && trim($p['content_id']) != 0) {
            $sql .= " UPDATE manual_content SET \n";
            $qw .=  "\n WHERE content_id=" . trim($p['content_id']);
            $set[] = " modified='" . $created . "'";
            if (!empty($p['created_by'])) {
                $set[] = " modified_by=" . trim($p['created_by']);
            }
        }else {
            $sql .= " INSERT INTO manual_content SET \n";
            $set[] = " created='" . $created . "'";
            if (!empty($p['created_by'])) {
                $set[] = " created_by=" . trim($p['created_by']);
            }
        }
        
        if (!empty($set)) {
            $sql .= implode(",", $set);
        }
        if (!empty($qw)) {
            $sql .= $qw;
        }
        
        $result = $conn->Execute($sql);
        if ($result) {
            $feedback = "Succeed!";
            return true;
        }
        else {
            $feedback = "Failed!";
            return false;
        }
    }

    function delManualByContentId($p) {
        global $conn;
        $qw = "";
        if (!empty($p['content_id'])) {
            if (is_array($p['content_id'])) {
                $qw .= " content_id IN ( ". implode(",", trim($p['content_id'])) ." )";
            } else $qw .= " content_id=" . trim($p['content_id']);
            $sql = " DELETE FROM manual_content WHERE " . $qw;
        }
        $result = $conn->Execute($sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function addContentCategory($p) {
        global $conn, $feedback;
        $check = self::getContentCategory($p);
        if ($check) {
            //the category does exist already
            $feedback = "The category already exists";
            return false;
        }else {
            if (isset($p['pref_id']) && !empty($p['content_id']) && trim($p['content_id']) != 0) {
                $sql .= " UPDATE preference SET \n";
                $qw .=  "\n WHERE pref_id=" . trim($p['pref_id']);
            } else {
                $sql = "INSERT INTO preference SET \n";
                $pref_id = $conn->GenID('seq_preference_pref_id');
                $content[] = " pref_id=" . trim($pref_id);
            }
            if (!empty($p['pref_table'])){
                $content[] = "pref_table='" . htmlspecialchars(addslashes(trim($p['pref_table']))) . "'";
            }
            if (!empty($p['pref_field'])){
                $content[] = "pref_field='" . htmlspecialchars(addslashes(trim($p['pref_field']))) . "'";
            }
            if (!empty($p['pref_value'])){
                $content[] = "pref_value='" . htmlspecialchars(addslashes(trim($p['pref_value']))) . "'";
            }
            if (!empty($content)) {
                $sql .= implode(",", $content);
            }
            if (!empty($qw)) {
                $sql .= $qw;
            }

            $result = $conn->Execute($sql);
            if ($result) {
                $feedback = "Succeed";
                return true;
            }else {
                $feedback = "Failed";
                return false;
            }
        }
    }

    function getContentCategory($p) {
        global $conn;
        $sql = "SELECT * FROM preference ";
        $qw[] = " WHERE 1 ";
        if (isset($p['pref_id']) && !empty($p['content_id']) && trim($p['content_id']) != 0) {
            $qw[] =  " pref_id=" . trim($p['pref_id']) . " ";
        } 
        if (!empty($p['pref_table'])){
            $qw[] = " pref_table='" . htmlspecialchars(addslashes(trim($p['pref_table']))) . "' ";
        }
        if (!empty($p['pref_field'])){
            $qw[] = " pref_field='" . htmlspecialchars(addslashes(trim($p['pref_field']))) . "' ";
        }
        if (!empty($p['pref_value'])){
            $qw[] = " pref_value='" . htmlspecialchars(addslashes(trim($p['pref_value']))) . "' ";
        }
        if (!empty($qw)) {
            $sql .= implode(" AND ", $qw);
        }
        $res = $conn->getAll($sql);
        if ($res) {
            return $res;
        } else {
            return null;
        }
    }
}
?>
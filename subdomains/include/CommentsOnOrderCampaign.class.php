<?php
class CommentsOnOrderCampaign {


    function getCommentsByOrderId($order_id)
    {
        global $conn;
        $q = "SELECT cooc.*, u.user_name AS creator, c.user_name AS ccreator  " . 
             "FROM comments_on_order_campaign AS cooc ".
             "LEFT JOIN users AS u ON (u.user_id = cooc.creation_user_id) ".
             "LEFT JOIN client AS c ON (c. client_id  = cooc.creation_user_id) ".
             "LEFT JOIN order_campaigns AS oc ON (oc.order_campaign_id = cooc.order_id) ".
             "WHERE cooc.order_id = '".$order_id."' AND oc.status >=0  ORDER BY cooc.creation_date";
        return $conn->GetAll($q);
    }

    function addComments($comment, $language='en', $order_id) 
    {
        global $feeback, $conn;
        $qcw = "";
        $creator = 0;
        $create_role = 'admin';
        if (user_is_loggedin()) {
            $creator = User::getID();
            $create_role = User::getRole();
        } elseif (client_is_loggedin()) {
            $creator = Client::getID();
            $create_role = 'client';;
        } else {
            $feedback = "Please sign in this system";
            return false;
        }
        $qcw .= "AND creation_user_id = '".$creator."' AND creation_role = '" . $create_role . "' ";

        $q = "SELECT COUNT(*) AS count FROM comments_on_order_campaign ".
             "WHERE order_id = '".$order_id."' AND comment = '".$comment."' ". $qcw;
        $rs = &$conn->Execute($q);
        $do_comment = true;
        if ($rs) {
            if ($rs->fields['count'] > 0) {
                $do_comment = false;
            }
            $rs->Close();
        }

        //add comments
        $conn->StartTrans();
        if ($comment != '' && $do_comment) {//do comment
            $data = array(
                'creation_date' => date("Y-m-d H:i:s"),
                'order_id' => $order_id,
                'language' => 'en',
                'comment' => $comment,
                'creation_user_id' => $creator,
                'creation_role' => $create_role,
            );
            
            $comment_id = $conn->GenID('seq_comments_on_order_campaign_comment_id');
            $data['comment_id'] = $comment_id;
            $fields = array_keys($data);
            $q = "INSERT INTO comments_on_order_campaign (`" . implode("`,`", $fields). "`) ".
                 "VALUES ('". implode("','", $data)."')";
            $conn->Execute($q);
        }
        $ok = $conn->CompleteTrans();
        if ($ok) {
            $feedback = 'Successful';
            return true;
        } else {
            $feedback = "Failure, please try again";
            return false;
        }
    }
}
?>

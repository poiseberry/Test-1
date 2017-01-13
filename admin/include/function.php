<?php

function get_ads($type, $cat_id)
{
    global $table;
    $database = new database();

    $today = date('Y-m-d H:i:s');

    $query = "select * from " . $table['ads'] . " where status=1 and date(start_date) <= date('$today') and date(end_date) >= date('$today')
    and type='$type'";
    if ($cat_id != 0) {
        $query .= " and cat_id in ($cat_id)";
    }
    $query.=" order by rand()";
    $result = $database->query($query);
    $row=$result->numRows();
    $rs_array = $result->fetchRow();

    if($row>0) {
        $database->query("update " . $table['ads'] . " set view=view+1 where pkid=" . $rs_array['pkid']);
    }

    if ($rs_array['img_url'] != "") {
        if($rs_array['link']!="") {
            return '<a href="click.php?id='.$rs_array['pkid'].'&url='.urlencode($rs_array['link']).'" target="_blank"><img src="files/impression/' . $rs_array['img_url'] . '"></a>';
        }else{
            return '<img src="files/impression/' . $rs_array['img_url'] . '">';
        }
    } else if ($rs_array['script'] != "") {
        return $rs_array['script'];
    }
}

function get_country_code($id)
{
    global $table;
    $database = new database();

    $query = 'select * from ' . $table['country'] . " where pkid=$id";
    $result = $database->query($query);
    $rs_array = $result->fetchRow();

    return $rs_array['iso_code'];
}

function get_query_insert($table, $db_data)
{
    $db_field = implode(',', array_keys($db_data));
    $db_value = implode("','", array_values($db_data));

    $query = "insert into " . $table . " ($db_field) values ('$db_value')";

    return $query;
}

function get_query_update($table, $pkid, $db_data)
{
    $query = "update " . $table . " set ";
    foreach ($db_data as $k => $v) {
        $v = mysql_real_escape_string($v);
        $query .= $k . "='" . $v . "',";
    }
    $query = substr($query, 0, -1);
    $query .= " where pkid=$pkid";
    return $query;
}

function get_query_delete($table, $pkid)
{
    $query = "delete from " . $table . " where pkid=$pkid";
    return $query;
}

function get_button($folder, $type, $pkid)
{
    switch ($type) {
        case 'new':
            return '<a href="' . $folder . '/new.php"><button type="button" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i> New</button></a>';
            break;

        case 'edit':
            return '<a href="' . $folder . '/edit.php?id=' . $pkid . '"><button type="button" class="btn btn-default btn-xs"><i class="fa fa-pencil-square-o"></i></button></a>';
            break;

        case 'view':
            return '<a href="' . $folder . '/view.php?id=' . $pkid . '"><button type="button" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></button></a>';
            break;

        case 'delete':
            return '<button type="button" class="btn btn-danger btn-xs" onclick="modal_delete_listing(\'' . $folder . '\',\'' . $pkid . '\')"><i class="fa fa-trash"></i></button>';
            break;

        case 'save':
            return '<button type="submit" name="submit_save" value="true" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>';
            break;

        case 'submit':
            return '<button type="submit" name="submit_save" value="true" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Submit</button>';
            break;

        case 'clear':
            return '<button type="submit" name="submit_clear" value="true" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Clear</button>';
            break;

        case 'next':
            return '<button type="submit" name="submit_save" value="true" class="btn btn-primary btn-xs"><i class="fa fa-arrow-right"></i> Next</button>';
            break;

        case 'cancel':
            return '<a href="' . $folder . '/listing.php"><button type="button" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Cancel</button></a>';
            break;

        case 'export':
            return '<a href="' . $folder . '/export.php"><button type="button" class="btn btn-warning btn-xs"><i class="fa fa-file-excel-o"></i> Export</button></a>';
            break;
    }
}

function protect($action, $string)
{
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'b557c005cb757674879e9d0aefb13d6f';
    $secret_iv = 'decubic4410';

    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

function randomString($length = 15)
{
    $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function get_device_id()
{
    $cookie = $_COOKIE[protect('encrypt', 'deviceid')];

    if (preg_match('/www/', $_SERVER['HTTP_HOST'])) {
        $domain = str_replace("www", '', $_SERVER['HTTP_HOST']);
    } else {
        $domain = '.' . $_SERVER['HTTP_HOST'];
    }

    if ($cookie != "") {
        return $cookie;
    } else {
        //setcookie(protect('encrypt', 'deviceid'), uniqid(), time() + 12441600, '/', $domain);
        setcookie(protect('encrypt', 'deviceid'), uniqid(), time() + 12441600, '/');
        return $_COOKIE[protect('encrypt', 'deviceid')];
    }
}

function get_balance()
{
    $xml_array['sClientUserName'] = $_SESSION['fe']['username'];
    $xml_array['sClientPassword'] = protect('decrypt', $_SESSION['fe']['password']);
    $xml_array['dBalance'] = 0.00;

    $client = new SoapClient($_SESSION['fe']['api_url'], array('trace' => true, 'exceptions' => true));
    $result = $client->CheckBalance($xml_array);

    return $result->dBalance;
}

function get_headline()
{
    $xml_array['sClientUserName'] = $_SESSION['fe']['username'];
    $xml_array['sClientPassword'] = protect('decrypt', $_SESSION['fe']['password']);

    $client = new SoapClient($_SESSION['fe']['api_url'], array('trace' => true, 'exceptions' => true));
    $result = $client->GetMessageInfo($xml_array);

    return $result->sResponseMessage;
}

function do_tracking($user_username, $action)
{
    $ip_address = get_ipaddress();
    global $table;
    $database = new database();
    $query = "insert into " . $table['tracking'] . " (username,action,ip,created_date) values ('$user_username','$action','$ip_address',now())";
    $database->query($query);
}

function is_between_date($start, $end)
{
    $date = date('Y-m-d');
    $today = date('Y-m-d', strtotime($date));
    $DateBegin = date('Y-m-d', strtotime($start));
    $DateEnd = date('Y-m-d', strtotime($end));

    if ($today >= $DateBegin && $today <= $DateEnd) {
        return true;
    } else {
        return false;
    }
}

function is_mobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function get_ipaddress()
{
    global $_SERVER;

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        // check for internal ips by looking at the first octet
        foreach ($matches[0] AS $ip) {
            if (!preg_match("#^(10|172\.16|192\.168)\.#", $ip)) {
                $clientIP = $ip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_FROM'])) {
        $clientIP = $_SERVER['HTTP_FROM'];
    } else {
        $clientIP = $_SERVER['REMOTE_ADDR'];
    }
    return $clientIP;
}

function do_badwords_filter($text)
{
    $bad_words = 'ass,asshole,anus,anal,arse,arsehole,bodoh,blow job,boobs,bobbie,boops,buntut,butt,' .
        'bugger,bastard,bustard,busterd,bitch,b!tch,b1tch,biatch,ceebai,cibai,cibet,cock,' .
        'cheebye,cocksuck,crap,cum,cunt,cilaka,damade,dick,dickhead,dumbass,dildo,diu,' .
        'fuck,fucker,f u c k,fark,faggard,faggot,fagtart,farhai,fu2k,uckin,fag,fatass,' .
        'gay,hooker,h00ker,idiot,jackoff,jackass,jesus christ,konkek,kongket,kanasai,lancau,' .
        'motherfucker,mothersucker,nigga,nigger,niamah,pecker,pig,piss,pussy,pukimak,porn,p0rn,' .
        'racist,sohai,sorhai,s0hai,s0rhai,sh!at,sh1at,shit,sh!t,sh1t,slut,stupid,sex,suck,tit,' .
        'tui nia mah,tiu,whore,wh0re';

    $white_words = 'assumption,assume,assassin,assignment,assure,assurance,assert,asset';

    $arr_bad_words = explode(',', $bad_words);
    $arr_white_words = explode(',', $white_words);

    foreach ($arr_white_words as $white_word) {
        $text = preg_replace("/\b$white_word/i", '_' . $white_word, $text);
    }

    foreach ($arr_bad_words as $bad_word) {
        $text = preg_replace("/\b$bad_word/i", do_mask_text($bad_word), $text);
    }

    foreach ($arr_white_words as $white_word) {
        $text = preg_replace('/\b_' . $white_word . '/', $white_word, $text);
    }


    return $text;
}

function do_mask_text($text)
{
    for ($i = 0; $i < strlen($text); $i++) {
        $return .= "*";
    }
    return $return;
}

function compt($query)
{
    $database = new database();

    $result = $database->query($query);
    $row = $result->numRows();

    return $row;
}

?>
<?php
class comn_funcs{
    private $host; private $username;
    private $pwd; private $database;
    private function connect (){
       $oopconn=new mysqli( $this->host="localhost",
        $this->username="root",$this->pwd="",
        $this->database="tech talks");
        return $oopconn;
    }
 
public function extract_Clg_Mail($email) {
    // Regular expression to match the email pattern
    $pattern = '/(\d{2})4g(1a|5a)(\d{2})(\d{2})@srit\.ac\.in/';

    // Perform the regular expression match
    if (preg_match($pattern, $email, $matches)) {
        // Extracting the components
        $yearOfJoining = $matches[1];
        $entryType = $matches[2] == '1a' ? 'Regular' : 'Lateral Entry';
        $branchCode = $matches[3];
        $rollno=$matches[4];

        // Returning the extracted information
        return [
            'year' => $yearOfJoining,
            'type' => $entryType,
            'branch' => $branchCode,
            'rollno'=>$rollno
        ];
    } else {
        return false;
    }
}
    // below are the keys for encryption
    protected $iky="rmdA3S0Cquwosown,z;osn%caqoqnfczdnuvaksdnfakjn";
    protected $mky="rmdA3S0Cquwoown,z;osn%caqoqnfcdniuvaksdnfakjn";
    protected $strec="rmdA3S0Cquwoown,z;osn%caqoqncqdniuvstraksdnfakjn";

    protected function enc($data,$key,$typ){ // normal encryption
        $tp=htmlspecialchars($typ);
        $ciphering="AES-128-CTR";
        if($tp=='idx'){$iv = '1234567891011121';}
        if($tp=='strix'){$iv = '1232567391041121';}
        elseif($tp=='mtr'){$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($ciphering));}
        $encryption = openssl_encrypt($data,$ciphering,$key,0, $iv);
        return base64_encode($encryption.'::'.$iv);
    }
    protected function dec($data,$key){ // normal decryption
        list($encrypted_data,$iv)=array_pad(explode('::',base64_decode($data),2),2,null);
        $decryption=openssl_decrypt ($encrypted_data, "AES-128-CTR",$key, 0, $iv);
        return $decryption;
    }
    protected function sblen($data,$key,$typ){ // searchable encryption
        $data=str_split($data);
        $s="";
        foreach($data as $l){
            $s.=substr($this->enc($l,$key,$typ),0,3);
        }
        return $s;
    }
    protected function sbldc($data,$key){ // searchable decryption
        $data=str_split($data,3);
        $s="";$othf="";
        if($key==$this->strec){
            $othf="9PTo6MTIzMjU2NzM5MTA0MTEyMQ==";
        }elseif($key==$this->iky){
        $othf="9PTo6MTIzNDU2Nzg5MTAxMTEyMQ==";
        }
        foreach($data as $l){
            $d=$l.$othf;
            $s.=$this->dec($d,$key);
        }
        return $s;
    }

    // timefrendly function converts normal timestamp into something like 1hr ago or 1day ago
    protected function timefrendly($datetime,$tz, $full = false) {
        // $tmz=(isset($_COOKIE['_utg_']))?$this->dec(htmlentities($_COOKIE['_utg_']),$this->iky):"Asia/kolkata";
        $tmz="Asia/kolkata";
        date_default_timezone_set($tmz);
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => $v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'now';
    
    }

    public function chckatmtavble($atm){
        $conn=$this->connect();
        $satment=explode(" ",strtolower($atm));$atmt="";
        foreach($satment as $at){$atmt.=$at;}
        $vatm=htmlentities(mysqli_real_escape_string($conn,$atmt));
        $satm=$this->dec(htmlentities(mysqli_real_escape_string($conn,$atm)),$this->strec);
        $satmt=$this->sblen(htmlentities(mysqli_real_escape_string($conn,$atmt)),$this->strec,'strix');
        $uatmt=$this->sblen(htmlentities(mysqli_real_escape_string($conn,$atmt)),$this->iky,'idx');
        $sql="SELECT usid AS atmtn FROM roupldls WHERE usid='$vatm' OR runm='$uatmt' UNION SELECT stnmr AS atmtn FROM stsinmtplc WHERE stratmnt='$satmt' OR stnmr='$satm' LIMIT 1; ";
        $query=$conn->query($sql);
        if($query){
            if($query->num_rows>0){
                $row=$query->fetch_assoc();
                return $row['atmtn'];
            }else{return 0;}
        }
    }

    // notify is a function which sends notifications automatically
    public function notify($uf,$ut,$typ,$msg){
        $conn=$this->connect();
        $tmz=(isset($_COOKIE['_utg_']))?$this->dec($_COOKIE['_utg_'],$this->iky):"Asia/kolkata";
        date_default_timezone_set($tmz);
        
        $time=$this->enc(date("Y-m-d H:i:s"),$this->strec,'mtr');
        $send=htmlentities(mysqli_real_escape_string($conn,$uf));
        $recv=htmlentities(mysqli_real_escape_string($conn,$ut));
        $notyp=$this->enc(htmlentities(mysqli_real_escape_string($conn,$typ)),$this->strec,"strix");
        $ntfmsg=$this->enc(htmlentities(mysqli_real_escape_string($conn,$msg)),$this->strec,"mtr");
        $sqlInsrt="INSERT INTO alntfcns(sndr,rcvr,ntftyp,ntfymg,ntfysnddt,ntfopnd,ntfysen)VALUES('$send','$recv','$notyp','$ntfmsg','$time','RkE9PTo6MTIzMjU2NzM5MTA0MTEyMQ==','RkE9PTo6MTIzMjU2NzM5MTA0MTEyMQ==');";
        $query=$conn->query($sqlInsrt);
        if($query){return 1;}else{return 0;}
    }

    // mail to is a mail function it sends mail for a particular user automatically.
    public function mailto($uid,$from,$msg){
        $conn=$this->connect();
        $user=htmlentities(mysqli_real_escape_string($conn,$uid));
        $frm=htmlentities(mysqli_real_escape_string($conn,$from));
        $txtmsg=htmlentities(mysqli_real_escape_string($conn,$msg));
        $sql="SELECT rmuflm,usml,rualmlsnfy FROM roupldls WHERE usid='$user';";
        $query=$conn->query($sql);
        if($query){
            if($query->num_rows>0){
                $row=$query->fetch_assoc();
                $alwemail=$this->dec($row['rualmlsnfy'],$this->iky);
        if($alwemail==1){
                $f_name=$this->sbldc($row['rmuflm'],$this->iky);
                $email=$this->dec($row['usml'],$this->iky);
        $sqls="SELECT strnm,stratmnt,strrctgre FROM stsinmtplc WHERE stnmr='$frm';";
        $querys=$conn->query($sqls);
        if($querys){
            if($querys->num_rows>0){
                $rows=$querys->fetch_assoc();
                $strnm=$this->sbldc($rows['strnm'],$this->strec);
                $strat=$this->sbldc($rows['stratmnt'],$this->strec);
                $strctgre=$this->dec($rows['strrctgre'],$this->strec);
                    $mailmsg=$txtmsg;
                    if($txtmsg=="apmtr"){
                        $mailmsg="Your payment recieved by the $strctgre '$strnm' (@$strat). And your order placed successfully!";
                    }
                    if($txtmsg=="abodrpkng"){
                        $mailmsg="Your order is packing!... From the $strctgre '$strnm' (@$strat)";
                    }
                    if($txtmsg=="abodrpkd"){
                        $mailmsg="Your order is packed. You can pick your order from the $strctgre '$strnm' (@$strat) now.";
                    }
                    $to_email=$email;
                    $subject="Notifications";
                    $header ="From: Remindo <mythreya.fn@gmail.com>\r\n";
                    $header .="MIME-Version: 1.0\r\n";
                    $header .="Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $body="<!DOCTYPE html>
                        <html>
                        <body>
                        <center>
                        <h1>Hi! $f_name</h1><br>
                        <h3>$mailmsg</h3>
                        <a href='https://remindo.in'><div style='padding: 8px;color: white;background-color: #f4511e;margin: 9px;border-radius: 6px;font-weight: 600;cursor: pointer;text-align: center;box-shadow: 0 0 14px -7px #6a5454;'>Go to Remindo</div></a>
                        </center>
                        </body>
                        </html>";
                    mail($to_email,$subject,$body,$header);
            }
        }       
        }
        }
        }
    }
    public function updtusrpshsrvce($pshid){
        $conn=$this->connect();
        $pshid=$this->enc(htmlentities(mysqli_real_escape_string($conn,$pshid)),$this->iky,'mtr');
        $usrid=htmlentities($_SESSION['ssndi']);
        $sql="UPDATE roupldls SET pshud='$pshid' WHERE usid='$usrid';";
        if($conn->query($sql)){return 1;}else{return 0;}
    }

    // the below function is used for push notifications.
    public function pshnfmsngr($typ,$frm,$tousr,$msgtyp){
        $conn=$this->connect();
        $btns=array();
        $hdngs=array();
        $cntnt=array();
        $to="psn";$uid="";$user="";$ig="";$sig="http://localhost/remindo/includes/fn_img/alarmclock256.png";
        if($typ=="cstm"){$tousr=$tousr;
        $sql="SELECT pshud,rmuflm FROM roupldls WHERE usid='$tousr';";
        $query=$conn->query($sql);
        if($query){
            if($query->num_rows>0){
                $row=$query->fetch_assoc();
                $user=$this->sbldc($row['rmuflm'],$this->iky);
                $uid=$this->dec($row['pshud'],$this->iky);
            }else{return;}
        }else{$uid="";return;}
        }
        $sqls="SELECT strnm,stratmnt,strrctgre,strbsnonr FROM stsinmtplc WHERE stnmr='$frm';";
        $querys=$conn->query($sqls);
        if($querys){
            if($querys->num_rows>0){
                $rows=$querys->fetch_assoc();
                $strnm=$this->sbldc($rows['strnm'],$this->strec);
                $strat=$this->sbldc($rows['stratmnt'],$this->strec);
                $strctgre=$this->dec($rows['strrctgre'],$this->strec);
                // $sig=($spc!=""&&file_exists("../fhupuppts".$spc))?$spc:$sig;
                $sig="";
                $hdngs=array("en"=>"Remindo ($strnm)");
            if($typ=="str"){$to="str";
                $strbsnonr=$this->dec($rows['strbsnonr'],$this->iky);
                $uid=[];
                $sql="SELECT pshud FROM roupldls WHERE usid='$strbsnonr';";
                $query=$conn->query($sql);
                if($query){
                    if($query->num_rows>0){
                        $row=$query->fetch_assoc();
                        // $uid=$this->dec($row['pshud'],$this->iky);
                        array_push($uid,$this->dec($row['pshud'],$this->iky));
                    }
                }
                $sids=$this->enc($frm,$this->strec,'strix');
                $sql="SELECT psnidassrol,strolofpsn FROM strtemrls WHERE sridtoasngrl='$sids'; ";
                $query=$conn->query($sql);
                if($query){
                    if($query->num_rows>0){
                        $usrs=[];
                        while($row=$query->fetch_assoc()){
                            if($this->dec($row['strolofpsn'],$this->iky)!="Embedder"){
                            $usr=$this->dec($row['psnidassrol'],$this->iky);
                            array_push($usrs,$usr);
                            }
                        }
                        if(count($usrs)>0){
                            foreach($usrs as $usr){
                            $query=$conn->query("SELECT pshud FROM roupldls WHERE usid='$usr';");
                            if($query&&$query->num_rows>0){
                                $row=$query->fetch_assoc();
                                $plid=$this->dec($row['pshud'],$this->iky);
                                if($plid!=""&&$plid!=0){array_push($uid,$plid);}
                            }
                            }
                        }
                    }
                }
                $pcrs2="Rmc9PTo6MTIzMjU2NzM5MTA0MTEyMQ==";//2
                $pckodr1="RlE9PTo6MTIzMjU2NzM5MTA0MTEyMQ==";//1 
                $pmtvfcns="";
                $ttlodrs=0;$strid=$this->enc($frm,$this->strec,'strix');
                $sql="SELECT count(odrmrnmr) AS ttlodrs FROM pplstsodrmrrs WHERE odrstnmr='$strid' AND (odrsts='$pckodr1' OR odrsts='$pcrs2');";
                $query=$conn->query($sql);
                if($query){
                    if($query->num_rows>0){
                        $rows=$query->fetch_object();
                        $ttlodrs=$rows->ttlodrs;
                    }
                }
                $ttlpmts=0;
                // $notyp="Vjhnb09TZUI6OjEyMzI1NjczOTEwNDExMjE=";//spmtak
                // $nfond="RkE9PTo6MTIzMjU2NzM5MTA0MTEyMQ==";//0
                // $sql="SELECT COUNT(nid) AS ttlpmts FROM alntfcns WHERE rcvr='$strid' AND ntftyp='$notyp' AND ntfopnd='$nfond';";
                // $query=$conn->query($sql);
                // if($query){
                //     if($query->num_rows>0){
                //         $rows=$query->fetch_object();
                //         $ttlpmts=$rows->ttlpmts;
                //     }
                // }
                // if($ttlpmts>0){
                    // $pmtvfcns="And $ttlpmts payment verifications.";
                // }
                if($msgtyp=="todrs"){
                    $cntnt=array("en"=>"You have recieved $ttlodrs new orders to pack! $pmtvfcns");
                }
                array_push($btns, array(
                    "id" => "Go-to-store",
                    "text" => "Go to store",
                    "url" => "http://localhost/remindo/stores/store?s=$strat"
                ));
            }
            elseif($typ=="cstm"){
                if($msgtyp=="apmtr"){
                    $cntnt=array("en"=>"Hey! $user your payment recieved by the store. And your order will be delivered!");
                }
                if($msgtyp=="npmtgt"){
                    $cntnt=array("en"=>"Hey! $user your payment not recieved by the store. Please check your payment method name and payment method profile name!");
                }
                if($msgtyp=="abodrpkng"){
                    $cntnt=array("en"=>"Hey! $user your order is packing!... By the $strctgre.");
                }
                if($msgtyp=="abodrpkd"){
                    $cntnt=array("en"=>"Hey! $user your order is packed. You can pay now.");
                }
                array_push($btns, array(
                    "id" => "Go-to-store",
                    "text" => "Go to store",
                    "url" => "http://localhost/remindo/stores/store?s=$strat"
                ));
                $msgtyp=explode("/r/",$msgtyp);
                if($msgtyp[0]=="srols"){
                $btns=[];$roltyp=$msgtyp[1];
                $cntnt=array("en"=>"Hey! $user. $strnm has invited you as $roltp of the team.");
                array_push($btns, array(
                    "id" => "ignore",
                    "text" => "Ignore",
                    "url"=>"http://localhost/remindo?_osp=do_not_open"
                ));
                array_push($btns, array(
                    "id" => "Go-to-store",
                    "text" => "Join",
                    "url" => "http://localhost/remindo/notifications"
                ));
                }else if($msgtyp[0]=="odstdlv"){
                    $msg=str_replace("br/br",",",$msgtyp[1]);
                    $cntnt=array("en"=>"Hey! $user your order sent to delivery. $msg");
                }
            }elseif($typ=="gpcsmrs"){
                $to="gpcsmrs";$uid=array();
                $sid=$this->enc($frm,$this->strec,'strix');
                $sql="SELECT stscmnm FROM stspdbycstms WHERE stsnm='$sid' ORDER BY strttlitrcns DESC;";
                $query=$conn->query($sql);
                if($query){
                    if($query->num_rows>0){
                        $usrs=[];
                        while($row=$query->fetch_assoc()){
                            echo $usr=$this->dec($row['stscmnm'],$this->iky)."<br>";
                            array_push($usrs,$usr);
                        }
                        if(count($usrs)>0){
                            foreach($usrs as $usr){
                            $query=$conn->query("SELECT pshud FROM roupldls WHERE usid='$usr';");
                            if($query&&$query->num_rows>0){
                                $row=$query->fetch_assoc();
                                echo $plid=$this->dec($row['pshud'],$this->iky);
                                if($plid!=""&&$plid!=0){array_push($uid,$plid);}
                            }
                            }
                        }else{return;}
                    }else{return;}
                }else{return;}
                $pid=$tousr;
                $sql="SELECT prdtnm,prdtpto FROM prdcsinstr WHERE strspdtnum='$pid' LIMIT 1;";
                $query=$conn->query($sql);
                if($query){
                    if($query->num_rows>0){
                        $row=$query->fetch_assoc();
                        $pdtnm=$this->sbldc($row['prdtnm'],$this->strec);
                        $ig=explode("/,",$this->dec($row['prdtpto'],$this->strec))[0];
                        $ig="http://localhost/remindo/strpdtspcs/".$ig;
                        $cntnt=array("en"=>"Added product: $pdtnm");
                        array_push($btns, array(
                            "id" => "Go-to-store",
                            "text" => "Go to store",
                            "url" => "http://localhost/remindo/stores/store?s=$strat"
                        ));
                        $sbls=$this->sblen($frm,$this->strec,'strix');
                        $sblp=$this->sblen($pid,$this->strec,'strix');
                        array_push($btns, array(
                            "id" => "view-product",
                            "text" => "View Product",
                            "url" => "http://localhost/remindo/shared?tp=pt&s=$sbls&p=$sblp"
                        ));
                    }else{return;}
                }else{return;}
            }
            $this->pushntfsndr($to,$uid,$hdngs,$cntnt,$btns,$sig,$ig);
            }else{return;}
            }else{return;}
    }


    private function pushntfsndr($to,$uid,$hdngs,$cntnt,$btns,$sig,$ig) {
        $conn=$this->connect();
        $hashes_array = $btns;$content=$cntnt;$headings=$hdngs;
        if($uid!=""||$uid!=0){
        if($to=="gps"){
            $fields = array(
                'app_id' => "ba568c8a-1a3b-4e7a-b12e-e49d5c12fd63",
                'included_segments' => array(
                    'Subscribed Users'
                ),
                'data' => array(
                    "foo" => "bar"
                ),
                'contents' => $content,
                'headings'=>$headings,
                'web_buttons' => $hashes_array
            );
        }elseif($to=="psn"){
            $fields = array(
                'app_id' => "ba568c8a-1a3b-4e7a-b12e-e49d5c12fd63",
                'include_player_ids' => array($uid),
                'data' => array("foo" => "bar"),
                'contents' => $content,
                'headings'=>$headings,
                'web_buttons' => $hashes_array,
                'chrome_web_badge'=>'',
            );
        }elseif($to=="str"){
            $fields = array(
                'app_id' => "ba568c8a-1a3b-4e7a-b12e-e49d5c12fd63",
                'include_player_ids' => $uid,
                'data' => array("foo" => "bar"),
                'contents' => $content,
                'headings'=>$headings,
                'web_buttons' => $hashes_array,
                'chrome_web_badge'=>'',
            );
        }elseif($to=="gpcsmrs"){
            $wpt=rand().time();
            $fields = array(
                'app_id' => "ba568c8a-1a3b-4e7a-b12e-e49d5c12fd63",
                'include_player_ids' => $uid,
                'data' => array("foo" => "bar"),
                'contents' => $content,
                'headings'=>$headings,
                // 'chrome_web_icon'=>$sig,
                'chrome_web_image' => $ig,
                'web_push_topic'=>$wpt,
                'web_buttons' => $hashes_array
            );
        }
        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NzkyZjZlZmItMjQyMC00N2ExLWEwZGMtMTlhOTk3YWE1YjAy'
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }else{return;}
    }
}
?>
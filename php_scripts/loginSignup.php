<?php
include 'db_conn.php';
class User extends dbconnect{

    private function identifyEmailType($email) {
        // Pattern for student email IDs
        $studentPattern = '/^\d{2}4g(1a|5a)\d{2}\d+@srit\.ac\.in$/';
    
        // Pattern for faculty email IDs
        $facultyPattern = '/^[a-z]+\.([a-z]+)@srit\.ac\.in$/';
    
        if (preg_match($studentPattern, $email)) {
            return 1;
        } elseif (preg_match($facultyPattern, $email)) {
            return 2;
        } else {
            return 0;
        }
    }
    private function userExists($mail_id) {
        $conn=$this->connect();
        $mail = mysqli_real_escape_string($conn,$mail_id);
        $mail_id=$this->enc($mail,$this->iky,'idx');
        $stmt = $conn->prepare("SELECT * FROM users WHERE mail_id = ?");
        $stmt->bind_param("s", $mail_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    public function login($mail_id, $pwd) {
        $conn=$this->connect();
        $mail = mysqli_real_escape_string($conn,$mail_id);
        $mail_id=$this->enc($mail,$this->iky,'idx');
        $pwd=mysqli_real_escape_string($conn,$pwd);
        $stmt = $conn->prepare("SELECT uid, user_name, pwd FROM users WHERE mail_id = ?");
        $stmt->bind_param("s", $mail_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pwd, $row['pwd'])) {
                $id=$this->enc($row['uid'],$this->iky,'mtr');
                session_start();
                $fac_Stu=$this->identifyEmailType($mail);
                $_SESSION['ses_id'] = id;
                $_SESSION['us_tp'] = $fac_Stu;
                if(!isset($_COOKIE['_uid_'],$_COOKIE['_us_tp_'])){
                setcookie("_uid_", $id, time() + (3600 * 24 * 365 ), "/"); 
                setcookie("_us_tp_", $fac_Stu, time() + (3600 * 24 * 365 ), "/"); 
                // header("Location: home");
                }
                return "Login successful";
            } else {
                return "Invalid password.";
            }
        } else {
            return "No user found with that email address.";
        }
    }


    public function register($user_name, $mail_id, $pwd) {
        $conn=$this->connect();
        $utype=$this->identifyEmailType($mail_id);

        if($utype!=0){
        if($this->userExists($mail_id)==0){
        $user_name=$this->enc(mysqli_real_escape_string($conn,$user_name),$this->iky,'mtr');
        $mail_id=$this->enc(mysqli_real_escape_string($conn,$mail_id),$this->iky,'idx');
        $pwd=mysqli_real_escape_string($conn,$pwd);

        $extractor=$this->extract_Clg_Mail($mail_id);
        $branch=$extractor["branch"];

        $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (user_name, mail_id, pwd, branch, u_type) VALUES (?, ?, ?, ?,?)");
        $stmt->bind_param("sssss", $user_name, $mail_id, $hashedPwd,$branch,$utype);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
        }else{
            return "Already Registered. Please login.";
        }
        }else{
            return "Enter Valid College Email Id.";
        }
    }

    
}


$user = new User();

if(isset($_POST['LoS']) && $_POST['LoS']=="l"){
if (isset($_POST['mail_id']) && isset($_POST['pwd'])) {
    $email = $_POST['mail_id'];
    $password = $_POST['pwd'];
    echo $user->login($email, $password);
} else {
    echo "Email and password are required.";
}
}

if(isset($_POST['LoS']) && $_POST['LoS']=="s"){
if (isset($_POST['user_name'], $_POST['mail_id'], $_POST['pwd'])) {
    $user_name = $_POST['user_name'];
    $mail_id = $_POST['mail_id'];
    $pwd = $_POST['pwd'];

    echo $user->register($user_name, $mail_id, $pwd);
} else {
    echo "All fields are required.";
}
}
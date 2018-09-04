<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
      header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Max-Age: 86400');    // cache for 1 day
  }

  // Access-Control headers are received during OPTIONS requests
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
  }

// add extra
/*if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
  $_POST = json_decode(file_get_contents('php://input'), true);*/

require_once("Rest.inc.php");
require_once("gcm.php");
date_default_timezone_set('Asia/Kolkata');

class API extends REST {
  public $data="";
  // const SERVER_IMAGE_PATH = "http://dreamanimators.com/swavlamban/admin/";
  // const WEB_SERVER_IMAGE_PATH = "";

  const DB_SERVER =  "localhost";
  const DB_USER = "root";
  const DB_PASSWORD = "";
  const DB = "undigit";

  private $db = NULL;

  public function __construct(){
    parent::__construct();				// Init parent contructor
    $this->dbConnect();					// Initiate Database connection
}
//mysql_real_escape_string

/*
   *  Database connection
  */
  private function dbConnect(){
    $this->db = @mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
    if($this->db)
      mysql_select_db(self::DB,$this->db) or die(mysql_error());
  }

/*
   * Public method for access api.
   * This method dynmically call the method based on the query string
   *
   */
  public function processApi(){
    $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
    if((int)method_exists($this,$func) > 0)
      $this->$func();
    else
      $this->response('',404);	// If the method not exist with in this class, response would be "Page not found".

  }

  private function signUp()
  {
    if($this->get_request_method()!="POST")
    {
        $this->response('',406);
    }
    else {
      $postdata = file_get_contents("php://input");
      $request = json_decode($postdata);

      $name = $request->name;
      $phone = $request->phone;
      $username = $request->username;
      $pass = $request->password;
      $password = md5($pass);
      $email = $request->email;
      $dob = $request->dob;
      $address = $request->address;
      /*$name = $_POST['name'];
      $phone = $_POST['phone'];
      $username= $_POST['username'];
      $email= $_POST['email'];
      $dob= $_POST['dob'];
      $address= $_POST['address'];
      $password = $_POST['password'];*/
    }


    if(!$name)
    {
      $success = array('status'=>'Error', 'msg'=>'Name not found');
      $this->response($this->json($success),200);
    }

    else if(!$phone)
    {
      $success = array('status'=>'Error', 'msg'=>'Phone Number not found');
      $this->response($this->json($success),200);
    }

    else if(!$username)
    {
      $success = array('status'=>'Error', 'msg'=>'username not found');
      $this->response($this->json($success),200);
    }

    else if(!$dob)
    {
      $success = array('status'=>'Error', 'msg'=>'date of birth not found');
      $this->response($this->json($success),200);
    }

    else if(!$name)
    {
      $success = array('status'=>'Error', 'msg'=>'Name not found');
      $this->response($this->json($success),200);
    }

    else if(!$email)
    {
      $success = array('status'=>'Error', 'msg'=>'Email not found');
      $this->response($this->json($success),200);
    }

    else if(!$address)
    {
      $success = array('status'=>'Error', 'msg'=>'address not found');
      $this->response($this->json($success),200);
    }

    else{
      $datetime = date('Y-m-d H:i:s');
      $check = mysql_query("select * from signup where email = '".$email."' or userName = '".$username."' or phone = '".$phone."' ") or die(mysql_error());

      $n = mysql_num_rows($check);
      if($n >= 1)
      {
        $success = array('status'=>"Error", "msg"=>'User Already Exists.');
        $this->response($this->json($success),200);
      }
      else{

        $insert = mysql_query("insert into signup(name, userName, email, password, phone, DOB, address, createdDate, modifiedDate) values('".$name."', '".$username."' ,'".$email."', '".$password."' , '".$phone."', '".$dob."', '".$address."', '".$datetime."', '".$datetime."')") or die(mysql_error());
        if($insert)
        {
          $success = array('status' => "Success", "msg" =>'Registered Successfully.', "id" => $id);
        }

        else {
          $success = array('status'=>"Error", "msg" => "MySQL error ".mysql_error() );
        }
        $this->response($this->json($success),200);
      }


    }

  }


  private function login()
  {
    if($this->get_request_method()!="POST")
    {
        $this->response('',406);
    }
    else {
      $postdata = file_get_contents("php://input");
      $request = json_decode($postdata);

      /*$username = $_POST['username'];
      $password = $_POST['password'];*/
      $username = $request->username;
      $temp_password = $request->password;
      $password = md5($temp_password);

      if(!$username)
      {
        $success = array('status'=>'Error', 'msg'=>'Username not found');
        $this->response($this->json($success),200);
      }

      else if(!$password)
      {
        $success = array('status'=>'Error', 'msg' => 'Password not found');
        $this->response($this->json($success), 200);
      }

      else {
        $select = mysql_query("select * from signup where userName = '".$username."' and password = '".$password."' " ) or die(mysql_error());
        $numRow = mysql_num_rows($select);
        if($numRow == 1)
        {
          $fetch = mysql_fetch_array($select);
          $id = $fetch['id'];
          $success = array('status'=>'Success', 'msg' => 'User logged in successfull', 'id' => $id);
        }
        else {
          $success = array('status'=>'Error', 'msg' => 'Username or Password might be wrong');
        }
        $this->response($this->json($success), 200);
      }
    }
  }

  private function profilePage()
  {
    if($this->get_request_method()!="POST")
    {
        $this->response('',406);
    }
    else {
      $postdata = file_get_contents("php://input");
      $request = json_decode($postdata);
      $id = $request->id;
      //$id = $_POST['id'];

      if(!$id)
      {
        $success = array('status'=>'Error', 'msg'=>'userid not found');
        $this->response($this->json($success),200);
      }
      else {
        $select = mysql_query("select * from signup where id = '".$id."'");
        $fetch = mysql_fetch_array($select);
        $success = array('status'=>'Success', 'profile' => $fetch);
        $this->response($this->json($success),200);
      }
    }
  }


  private function json($data){
    if(is_array($data)){
    return json_encode($data);
      }
    }
  }
  // Initiiate Library
  $api = new API;
  $api->processApi();


?>

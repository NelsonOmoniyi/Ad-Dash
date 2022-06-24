<?php
// error_reporting(E_ERROR | E_PARSE);
Class Customers extends dbobject{
    

      // table list
      public function list($data)
      {
          $table_name    = "customers_table";
          $primary_key   = "customers_id";
          $columner = array(
              array( 'db' => 'customers_id', 'dt' => 0 ),
              array( 'db' => 'customer_email', 'dt' => 1 ),
              array( 'db' => 'customer_name',  'dt' => 2 ),
              array( 'db' => 'phone_number',     'dt' => 3),
              array( 'db' => 'customers_id',       'dt' => 4, 'formatter'=> function($d,$row){
                
                return  "<button onclick=\"getModal('setup/products_setup.php?customers_id=".$row['customers_id']."','modal_div')\" class='btn btn-warning' href='javascript:void(0)' data-toggle='modal' data-target='#defaultModalPrimary'>Add Products</button>
                | <button onclick=\"getModal('myproducts.php?customers_id=".$row['customers_id']."','modal_div')\" class='btn btn-success' href='javascript:void(0)' data-toggle='modal' data-target='#defaultModalPrimary'>My Products</button>";
              })
            );
          $filter = "";
          //		$filter = " AND role_id='001'";
           // $filter .= " AND created='$data[start_date]'";
          $datatableEngine = new engine();
      
          echo $datatableEngine->generic_table($data,$table_name,$columner,$filter="",$primary_key);
  
      }


  
  public function getNextRiderId()
  {
      $sql    = "select CONCAT('00',max(riders_id) +1) as rolee FROM riders";
      $result = $this->db_query($sql);
      return $result[0]['rolee'];
      
  }
  public function saveUser($data)
  {
      $role_id = $data['role_id'];
      // $data['parish_pastor'] = 1;
      $validation = "";
          
          $validation = $this->validate($data,
                  array(
                      'customer_email'    =>'required',
                      'customer_name'    =>'required',
                      'phone_number'   =>'required',
                  ),
                  array('customer_email'=>'Customer Email','customer_name'=>'Customer Name','phone_number'=>'Phone Number')
                 );
                 if(!$validation['error'])
                 {
                     if($data['operation'] == "new")
                     {
                         $sql = "SELECT customer_email,customer_name FROM customers_table WHERE customer_email = '$data[customer_email]' LIMIT 1 ";
                         $resu = $this->db_query($sql);
                         if(count($resu) > 0)
                         {
                             $email = $resu[0]['customer_email'];
                             $name = $resu[0]['customer_name'];
                             $validation['error'] = true;
                             $validation['messages'][0] = $email." have already been used by [".$name."] ";
                         }
                     } 
                 }
                 if(!$validation['error'])
                 {
                    
                     return $this->register($data);
                 }
                 else
                 {
                     return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
                 } 
          
  }
  public function register($data)
{
    
          if($data['operation'] != 'edit')
          {
            
            $validation = $this->validate($data,
            array(
                'customer_email'    =>'required',
                'customer_name'    =>'required',
                'phone_number'   =>'required',
            ),
            array('customer_email'=>'Customer Email','customer_name'=>'Customer Name','phone_number'=>'Phone Number')
           );
          
              if(!$validation['error'])
              {

                $sql = "SELECT customer_email FROM customers_table WHERE customer_email = '$data[customer_email]' LIMIT 1";
file_put_contents("checkAgain.txt", $sql);
                $role_cnt = $this->db_query($sql,false);
                if($role_cnt < 1)
                {
                    $count = $this->doInsert('customers_table',$data,array('op','confirm_password','operation'));
                    if($count == 1)
                    {
                        //   rename('user_passport/'.$temp_pass,'user_passport/'.$data['email'].".".end($array));
                        return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                    }
                    else
                    {
                        return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                    }
                }else
                {
                    $role_name = $this->getitemlabel('customers_table','customer_email',$data['customer_email'],'customer_name');
                    return json_encode(array("response_code"=>20,"response_message"=>$role_name." already exist"));
                }
                  
              }else
              {
                  return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
              }
          }
          else
          {
                  //                EDIT EXISTING USER 
              $data['modified_date'] = date('Y-m-d h:i:s');
              $validation = $this->validate($data,
              array(
                  'customer_email'    =>'required',
                  'customer_name'    =>'required',
                  'phone_number'   =>'required',
              ),
              array('customer_email'=>'Customer Email','customer_name'=>'Customer Name','phone_number'=>'Phone Number')
             );
             
              if(!$validation['error'])
              {
                  $count = $this->doUpdate('customers_table',$data,array('op','operation'),array('customer_email'=>$data['customer_email']));
                  if($count == 1)
                  {
           //                    rename('user_passport/'.$temp_pass,'user_passport/'.$data['email'].".".end($array));
                      return json_encode(array("response_code"=>0,"response_message"=>'Record saved successfully'));
                  } 
                  else
                  {
                      return json_encode(array("response_code"=>78,"response_message"=>'Failed to save record'));
                  }
              }
              else
              {
                  return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
              }
          }
      
}

       
            
}
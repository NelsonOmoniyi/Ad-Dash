<?php
Class Products extends dbobject{

    public function prodList($data)
    {
        $customer_id = $data['customer_id'];
        // $sql = "SELECT customer_name FROM c WHERE customers_id = '$customer_id' LIMIT 1 ";
        // $result = $this->db_query($sql);
        //     if (count($result) > 0 ) {
        //         $name = $result['customer_name'];
        //     }
        

		$table_name    = "products";
		$primary_key   = "product_id";
		$columner = array(
            
			array( 'db' => 'product_id', 'dt' => 0 ),
			array( 'db' => 'product_name', 'dt' => 1 ),
			array( 'db' => 'product_description', 'dt' => 2 ),
			array( 'db' => 'customers_id', 'dt' => 3, 'formatter'=> function($d,$row){
                $name = $this->getitemlabel("customers_table","customers_id",$d,'customer_name');

                // return $name;
                if($_SESSION['role_id_sess'] == 10){
                return "$name </br> <a class='btn btn-sm btn-primary' onclick=\"getModal('seemore.php?customers_id=".$d."','modal_div')\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" href=\"javascript:void(0)\" id='confirm'>See Details</a>";
                }else{
                   return "$d"; 
                }
            }),
			array( 'db' => 'quantity', 'dt' => 4),
			array( 'db' => 'delivery_initiated', 'dt' => 5 ),
			array( 'db' => 'delivery_date', 'dt' => 6 ),
			array( 'db' => 'delivery_address', 'dt' => 7 ),
            array( 'db' => 'reciever_email', 'dt' => 8),
			array( 'db' => 'status', 'dt' => 9, 'formatter' => function($d,$row){
                        if($d > 0){
                            return "Delivered";
                        }else{
                            return "Pending";
                        }
            }),
            array( 'db' => 'category',   'dt' => 10 ),
            array( 'db' => 'rider',   'dt' => 11),
            array( 'db' => 'product_id',   'dt' => 12, 'formatter' => function($d,$row){

                // require_once('class/users.php');
          
               if($_SESSION['role_id_sess'] == 10){
                    if ($row['status'] > 0) {
                        return "<a class='btn btn-sm btn-warning disabled' onclick=\"getModal('setup/confirm_otp.php?product_id=".$d."','modal_div')\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" href=\"javascript:void(0)\" id='confirm'>Confirm OTP </a>";  
                    }else{
                        return "<a class='btn btn-sm btn-warning' onclick=\"getModal('setup/confirm_otp.php?product_id=".$d."','modal_div')\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" href=\"javascript:void(0)\" id='confirm'>Confirm OTP </a>";
                    }
               }else{
                   return "<button onclick=\"trigUser\" class='btn btn-danger left'>Delete Product</button> | <a class='btn btn-sm btn-warning right' onclick=\"getModal('setup/products_setup.php?op=edit&username=".$d."','modal_div')\"  href=\"javascript:void(0)\" data-toggle=\"modal\" data-target=\"#defaultModalPrimary\" >Edit Product</a>";
               }
              
               
           } ),
           array( 'db' => 'OTP',   'dt' => 13)
		);
		
            
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter = '',$primary_key);

    }
   
    public function saveProd($data)
    {
       
        $validation = "";
        // var_dump($data);
     
            $validation = $this->validate($data,
                    array(
                        'product_name' =>'required',
                        'product_description' =>'required',
                        'quantity' =>'required',
                        'delivery_initiated' =>'required',
                        'delivery_date' =>'required',
                        'delivery_address' =>'required',
                        'reciever_email' =>'required|email',
                        'category' =>'required'
                        
                    ),
                    array(
                    'product_name'=>'Product Name',
                    'product_description'=>'Product Description',
                    'quantity'=>'Quantity',
                    'delivery_initiated'=>'Delivery Initiated',
                    'delivery_date'=>'Delivery Date',
                    'delivery_address'=>'Delivery Address',
                    'reciever_email'=>'Reciever Email',
                    'category' => 'Product Cartegory'
                    )
                );
    
        
            if(!$validation['error'])
            {
                
                $stmt = $this->doInsert('products',$data,array('op','operation'));
                if($stmt > 0):
                    return json_encode(array("response_code"=>20,"response_message"=>'Successfully saved'));
                else:
                    return json_encode(array("response_code"=>20,"response_message"=>'Could not save your product'));
                endif;
            }
            else
            {
                return json_encode(array("response_code"=>20,"response_message"=>$validation['messages'][0]));
            }
            
    }
    public function OTP($data){

    $sql = "SELECT * FROM products WHERE OTP = '$data[otp]' AND product_id = '$data[product_id]' LIMIT 1 ";
    $result = $this->db_query($sql);
    if(count($result) > 0)
    {
        
        $sql = "UPDATE products SET status = '1' WHERE OTP = '$data[otp]' AND product_id = '$data[product_id]' LIMIT 1 ";
       $this->db_query($sql);
       if(count($result) > 0){
        return json_encode(array('response_code'=>0,'response_message'=>'Status Changed Suceessfully'));
       }else{
        return json_encode(array('response_code'=>0,'response_message'=>'Status Not Changed'));
       }
       return json_encode(array('response_code'=>0,'response_message'=>'Delivery Succcessfull'));
    }else{
        return json_encode(array('response_code'=>99,'response_message'=>'Invalid OTP, Contact Admin To Get An OTP!'));
    }
        
    }
 public function CusList($data){
    //  var_dump($data);
    $table_name    = "customers_table";
          $primary_key   = "customers_id";
          $columner = array(
              array( 'db' => 'customers_id', 'dt' => 0 ),
              array( 'db' => 'customer_email', 'dt' => 1 ),
              array( 'db' => 'customer_name',  'dt' => 2 ),
              array( 'db' => 'phone_number',     'dt' => 3)
            );
          $filter = "";
       
          $filter = " AND customers_id='".$data['id']."'";
          $datatableEngine = new engine();
      
          echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
 }
public function myProd($data){

        $table_name    = "products";
		$primary_key   = "customers_id";
		$columner = array(
			array( 'db' => 'product_id', 'dt' => 0 ),
			array( 'db' => 'product_name', 'dt' => 1 ),
			array( 'db' => 'product_description', 'dt' => 2 ),
			array( 'db' => 'customers_id', 'dt' => 3),
			array( 'db' => 'quantity', 'dt' => 4),
			array( 'db' => 'delivery_initiated', 'dt' => 5 ),
			array( 'db' => 'delivery_date', 'dt' => 6 ),
			array( 'db' => 'delivery_address', 'dt' => 7 ),
            array( 'db' => 'reciever_email', 'dt' => 8),
			array( 'db' => 'status', 'dt' => 9),
            array( 'db' => 'category',   'dt' => 10 ),
            array( 'db' => 'rider',   'dt' => 11),
            array( 'db' => 'product_id',   'dt' => 12),
            array( 'db' => 'OTP',   'dt' => 13)
		);
		
        $filter = "";
       
        $filter = " AND customers_id='".$data['id']."'";
            
        $datatableEngine = new engine();
	
		echo $datatableEngine->generic_table($data,$table_name,$columner,$filter,$primary_key);
}
}
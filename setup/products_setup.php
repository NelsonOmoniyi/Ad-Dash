<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();

$sql_role = "SELECT * FROM userdata WHERE role_id = '10'";
$roles = $dbobject->db_query($sql_role);

$customer_id = '';
if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $username  = $_REQUEST['email'];
    $user      = $dbobject->db_query("SELECT * FROM userdata WHERE email='$username' LIMIT 1");
    $operation = 'edit';
}
else
{
    $customer_id .= isset($_REQUEST['customers_id'])?$_REQUEST['customers_id']:'';
    $operation = 'new';
}
?>
 <link rel="stylesheet" href="codebase/dhtmlxcalendar.css" />
<script src="codebase/dhtmlxcalendar.js"></script>
<script>
    doOnLoad();
    var myCalendar;
function doOnLoad()
{
   myCalendar = new dhtmlXCalendarObject(["start_date"]);
   myCalendar.hideTime();
}
</script>
<style>
    #login_days>label{
        margin-right: 10px;
    }
</style>
<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit":""; ?>Add Products<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Products.saveProd">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="customers_id" value="<?php echo $customer_id; ?>">
       <input type="hidden" name="otp" id="otp" >
      
       <div class="row" style="<?php echo ($operation == "edit")?"display:none":""; ?>">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Product Name<span class="asterik">*</span></label>
                    <input type="text" name="product_name" class="form-control" placeholder="Product Name">
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group ">
                    <label class="form-label" style="display:block !important">Product Description<span class="asterik">*</span></label>
                    <input type="text"  autocomplete="on" name="product_description" class="form-control" />
                </div>
           </div>
       </div>
           
         <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Quantity<span class="asterik">*</span></label>
                    <select class="form-control" name="quantity" id="quantity" onchange="pricing()">
                       
                    <option value="">::Select Quantity::</option>
                    <option value="1"> 1 </option>
                    <option value="2"> 2 </option>
                    <option value="3"> 3 </option>
                    <option value="4"> 4 </option>
                    <option value="5"> 5 </option>
                      
                    </select>
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Delivery Submitted On:<span class="asterik">*</span></label>
                    <input type="date" name="delivery_initiated" class="form-control">
                </div>
           </div>
        </div>
        <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Expected Delivery Date<span class="asterik">*</span></label>
                    <input type="date" name="delivery_date" class="form-control">
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Delivery Address<span class="asterik">*</span></label>
                    <input type="text" name="delivery_address" class="form-control">
                </div>
           </div>
        </div>
        <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Reciever's Email<span class="asterik">*</span></label>
                    <input type="email" name="reciever_email" class="form-control">
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Product Category<span class="asterik">*</span></label>
                    <input type="text" name="category" class="form-control">
                </div>
           </div>
        </div>
        <div class="row">
         <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Dispatch Rider<span class="asterik">*</span></label>
                    <select onchange="show_bank_details(this.value)" class="form-control" name="rider" id="role_id">
                       
                        <?php
                        if($operation == "new")
                        {
                            echo '<option value="">::SELECT RIDER ::</option>';
                            foreach($roles as $row)
                            {
                                echo "<option value='".$row[email]."'>".$row['firstname']."</option>";
                            }
                        }else
                        {
                            // echo "<option value='".$user[0]['role_id']."'>".$dbobject->getitemlabel('userdata','role_id',$user[0]['role_id'],'username')."</option>";
                        }
                        
                        ?>
                    </select>
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Price<span class="asterik">*</span></label>
                    <input type="text" class="form-control" name="price" id="price" readonly>
                </div>
           </div>
     
        </div>
        
       
        <div class="row">
            <div class="col-sm-12">
                <div id="server_mssg"></div>
            </div>
        </div>
        <button id="save_facility" onclick="saveRecord()" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
    function saveRecord()
    {
        setTimeout(() => {
            var dd = $("#form1").serialize();
     
            $("#save_facility").text("Loading......");
        
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            $("#save_facility").text("Save");
            if(re.response_code == 0)
                {
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('products_list.php','page');
                    setTimeout(()=>{
                        $('#defaultModalPrimary').modal('hide');
                    },1000)
                }
            else
                {
                    $("#server_mssg").text(re.response_message);
                     $("#server_mssg").css({'color':'red','font-weight':'bold'});
                }
                
        },'json');

        }, 3000);
        
    }

    if($("#sh_display").is(':checked'))
        {
            
        }
  
 
    $("#show").click(function()
    {
        var password = $("#password").attr('type');
        if(password=="password")
            {
                $("#password").attr('type','text');
                $("#show").text("Hide");
            }else{
                $("#password").attr('type','password');
                $("#show").text("Show");
            }
    });
    function show_bank_details(val)
    {
        if(val == 10)
            {
                $("#rider").show();
            }
        else{
            $("#rider").hide();
        }
    }
  
        $("document").ready(function () {
            var OTP = "0" + Math.floor(Math.random() * 101) + 1;
             $("#save_facility").click(function () {
                $("#otp").val(OTP);
                    // window.alert(OTP);
            })
        });

        function pricing(){
            $Qval = $("#quantity").val();
            $("#price").val("$ "+$Qval + ".00");           
        }
    
    
</script>
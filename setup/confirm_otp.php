<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();

$sql = "SELECT * FROM products";


if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $product_id  = $_REQUEST['product_id'];
    $user      = $dbobject->db_query("SELECT * FROM products WHERE product_id='$product_id' LIMIT 1");
    $operation = 'edit';
}
else
{
    $product_id = $_REQUEST['product_id'];
    $operation = 'new';
}
?>

<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold">Confirm OTP</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Products.OTP">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

      
       <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Enter OTP</label>
                    <input type="number" name="otp" class="form-control">
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
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
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
       }, 1000);
    }
 
    
</script>
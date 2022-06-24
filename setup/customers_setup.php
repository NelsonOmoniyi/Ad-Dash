<?php
include_once("../libs/dbfunctions.php");
$dbobject = new dbobject();


if(isset($_REQUEST['op']) && $_REQUEST['op'] == 'edit')
{
    $username  = $_REQUEST['customer_email'];
    $user      = $dbobject->db_query("SELECT * FROM customers_table WHERE customer_email='$username'");
    $operation = 'edit';
}
else
{
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
    .asterik
    {
        color:red;
    }
</style>

<div class="modal-header">
    <h4 class="modal-title" style="font-weight:bold"><?php echo ($operation=="edit")?"Edit ":""; ?>Customer Setup<div><small style="font-size:12px">All asterik fields are compulsory</small></div></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body m-3 ">
    <form id="form1" onsubmit="return false">
       <input type="hidden" name="op" value="Customers.saveUser">
       <input type="hidden" name="operation" value="<?php echo $operation; ?>">
       
       <div class="row" style="<?php echo ($operation == "edit")?"display:none":""; ?>">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Email<span class="asterik">*</span></label><small style="float:right;color:red">This will be used to login</small>
                    <input type="email" name="customer_email" <?php echo ($operation == "edit")?"disabled":""; ?> class="form-control" value="<?php echo $username; ?>" placeholder="">
                    
                </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group ">
                    <label class="form-label" style="display:block !important">Name<span class="asterik">*</span></label>
                    <input type="text" autocomplete="on" name="customer_name" class="form-control" />
                </div>
           </div>
       </div>
        
        <div class="row">
           <div class="col-sm-6">
               <div class="form-group">
                    <label class="form-label">Phone Number<span class="asterik">*</span></label>
                    <input type="number" name="phone_number" class="form-control">
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
                    getpage('customers_list.php','page');
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
    }
    if($("#sh_display").is(':checked'))
        {
            
        }
    function show_bank_details(val)
    {
        if(val == 003)
            {
                $("#parish_pastor_div").show();
            }
        else{
            $("#parish_pastor_div").hide();
        }
    }
    function fetchLga(el)
    {
        $("#lga-fd").html("<option>Loading Lga</option>");
        $.post("utilities.php",{op:'Church.getLga',state:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#lga-fd").html(re.state);
            $("#church_id").html(re.church);
            
        },'json');
    }
    function getUniqueChurch(el)
    {
        $("#church_id").html("<option>Loading Church</option>");
        var ste = $("#church_state").val();
        $.post("utilities.php",{op:'Church.churchByState',state:ste,lga:el},function(re){
//            $("#lga-fd").empty();
            console.log(re);
            $("#church_id").html(re);
            
        });
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
    function check_bank_det(el)
    {
        if($("#yes").is(':checked')){
            $("#bank_details").slideDown()
        }else if($("#no").is(':checked'))
         {
            $("#bank_details").slideUp()
         }
    }
    
    function fetchAccName(acc_no)
    {
        if(acc_no.length == 10)
            {
                var account  = acc_no;
                var bnk_code = $("#bank_name").val();
                $("#acc_name").text("Verifying account number....");
                $("#account_name").val("");
                $.post("utilities.php",{op:"Church.getAccountName",account_no:account,bank_code:bnk_code},function(res){
                    
                    $("#acc_name").text(res);
                    $("#account_name").val(res);
                });
            }else{
                $("#acc_name").text("Account Number must be 10 digits");
            }
        
    }
</script>
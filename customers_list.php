<?php
include_once("libs/dbfunctions.php");
// var_dump($_SESSION);
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
   <div class="card">
     <div class="card-header">
        <h5 class="card-title">Customers List</h5>
        <h6 class="card-subtitle text-muted">The report contains customers that have been setup in the system.</h6>
    </div>
    <div class="card-body">
        <div class="row">
        
            <div class="col-sm-2">
                <a class="btn btn-warning" onclick="getModal('setup/customers_setup.php','modal_div')"  href="javascript:void(0)" data-toggle="modal" data-target="#defaultModalPrimary">Create Customer</a>
                
            </div>
        </div>
        
        <div id="datatables-basic_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-3">
                    <label for=""></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="page_list" class="table table-striped nowrap" style="width:100%" >
                       
                        <thead>
                            <tr role="row" style="background-color: grey; color: white;">
                                <th>S/N</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>

<script>
  var table;
  var editor;
  var op = "Customers.list";
  
  $(document).ready(function() {
    table = $("#page_list").DataTable({
      processing: true,
      columnDefs: [{
            orderable: false,
            targets: 0
          },
         { width: "3100", targets: "3" }
      ],
      serverSide: true,
      paging: true,
      oLanguage: {
        sEmptyTable: "No record was found, please try another query"
      },

      ajax: {
        url: "utilities.php",
        type: "POST",
        data: function(d, l) {
          d.op = op;
          d.li = Math.random();
//          d.start_date = $("#start_date").val();
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
  }
    

    function getModal(url,div)
    {
        $(url,div).html("<h2>Loading....</h2>");
//        $('#'+div).block({ message: null });
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }

    function saveRecord()
    {
        $("#save_facility").text("Loading......");
        var dd = $("#form1").serialize();
        // console.log(dd);
        $.post("utilities.php",dd,function(re)
        {
            console.log(re);
            $("#save_facility").text("Save");
            console.log(dd);
            if(re.response_code == 0)
                {
                    $("#server_mssg").text(re.response_message);
                    $("#server_mssg").css({'color':'green','font-weight':'bold'});
                    getpage('customers_list.php','page');
                    setTimeout(()=>{
                        // $('#defaultModalPrimary').modal('hide');
                        $('#defaultModalPrimary').addClass('d-none');
                    },1000)
                }
            else
                {
                    $("#server_mssg").text(re.response_message);
                     $("#server_mssg").css({'color':'red','font-weight':'bold'});
                }
                
        },'json');
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
   

</script>



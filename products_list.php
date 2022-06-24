<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();
//var_dump($_SESSION);


?>

   <div class="card">
    <div class="card-header">
        <h5 class="card-title">Products List</h5>
        <h6 class="card-subtitle text-muted">The report contains Product that have been setup in the system.</h6>
    </div>
    <div class="card-body">
   
        <div id="datatables-basic_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-3">
                    <label for=""></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    <table id="page_lists" class="table table-striped nowrap" style="width:100%" >
                       
                        <thead>
                            <tr role="row" style="background-color: grey; color: white;">
                                <th>S/N</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Customers Details</th>
                                <th>Quantity</th>
                                <th>Product Submitted On:</th>
                                <th>Delivery Date</th>
                                <th>Delivery Address</th>
                                <th>Reciever Email</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th>Dispatch Rider</th>
                                <th>Action</th>
                                <th>OTP</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
  var table;
  var editor;
  var op = "Products.prodList";
 
  
            
     
  $(document).ready(function() {
    table = $("#page_lists").DataTable({
      processing: true,
      columnDefs: [{
        // autoWidth: true,
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
    
    function trigUser(user,status)
    {
        var r_status = (status == 1)?"Unlock this user":"Lock this user";
        var cnf = confirm("Are you sure you want to "+r_status+" this user ?");
        if(cnf)
           {
                $.blockUI();
               $.post('utilities.php',{op:'Products.changeUserStatus',current_status:status,username:user},function(resp)
               {
                   $.unblockUI();
                   if(resp.response_code == 0)
                       {
                            //  alert(resp.response_message);
                          getpage('products_list.php','page'); 
                       }
                   
               },'json')
           }
    }
    function sackUser(username_1,status_1)
    {
        let tt = confirm("Are you sure you want to perform this action");
        if(tt)
            {
                $.post("utilities.php",{op:"Products.sackUser",username:username_1,status:status_1},function(rr){
                    alert(rr.response_message);
                    getpage('products_list.php','page');
                },'json');
            }
    }
    function getModal(url,div)
    {
        $('#'+div).html("<h2>Loading....</h2>");
//        $('#'+div).block({ message: null });
        $.post(url,{},function(re){
            $('#'+div).html(re);
        })
    }


    
</script>


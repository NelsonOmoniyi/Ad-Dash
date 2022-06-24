<?php
include_once("libs/dbfunctions.php");
$dbobject = new dbobject();

$customers_id = isset($_REQUEST['customers_id'])?$_REQUEST['customers_id']:'';

?>

   <div class="card">
     <div class="card-header">
        <h5 class="card-title">Customer Details</h5>
        <h6 class="card-subtitle text-muted">The report contains customers that have been setup in the system.</h6>
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
                    <table id="page_list" class="table table-striped" style="width:100%" >
                       
                        <thead>
                            <tr role="row" style="background-color: grey; color: white;">
                                <th>S/N</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Phone Number</th>
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
  var op = "Products.CusList";
  
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
            d.id= <?php echo $customers_id?>;
//          d.end_date = $("#end_date").val();
        }
      }
    });
  });

  function do_filter() {
    table.draw();
  }
    

 


  

</script>



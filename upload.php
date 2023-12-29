<?php 
require_once "includes/header.php";
require_once "includes/classes/VideoDetailsFormProvider.php";
; 
?>

    <!-- important note:: we pass connection variable vediodetailsformprovider() class as parameter so that that class access that variable and done categories input -->
    <div class="column">

        <!-- maybe we use later so we use class -->
        <?php
        //create obj
        $formProvider=new VideoDetailsFormProvider($connection);
        //calling fn and print
        echo $formProvider->createUploadForm();
        
        ?>  

    </div>

 
  <script>
    $("form").submit(function(){

      $("#staticBackdrop").modal("show");
    });
    </script>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <h2>Please wait, this might take time 🔄...</h2>
        <img src="./assets/images/loading-spinner.gif" alt="">
      </div>
      
    </div>
  </div>
</div>


<?php 
require_once "includes/footer.php"; 
?>
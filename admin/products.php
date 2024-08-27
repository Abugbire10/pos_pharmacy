<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
         <h4 class="mb-0">Products
            <a href="products-create.php" class="btn btn-primary float-end">Add Product</a>
         </h4>   
        </div>
        <div class="cad-body">
            <?php alertMessage(); ?>

            <?php 
                    $products = getAll('products');
                    if(!$products){
                        echo'<h4>Something went wrong!</h4?';
                        return false;
                    } // if something goes wrong in the function

                    if(mysqli_num_rows($products) > 0)
                    {
                    ?>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                       <th>ID</th> 
                       <th>Image</th> 
                       <th>Name</th> 
                       <th>Status</th> 
                       <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                  
                    <?php foreach($products as $item) : ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td>
                           <img src="../<?= $item['image']; ?>" style="width:50px;height:50px;" alt="img" />
                        </td>
                        <td><?= $item['name'] ?></td>
                        <td>
                            <?php 
                            if($item['status'] == 1){
                                echo '<span class="badge bg-danger">Hidden</span>';
                            }else{
                                echo '<span class="badge bg-success">Visible</span>';
                            }
                            
                            ?>
                        </td>

                        <td>
                            <a href="products-edit.php?id=<?= $item['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                            <a href="products-delete.php?id=<?= $item['id']; ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete this image? This action cannot be undone.')"
                            >
                            Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>


                    
                </tbody>
            </table>
          </div>         
          <?php 
            }
             else
             {
                ?>
                    <h4 class="mb-0">No Record Found</h4> 
                <?php
            }     
            ?>
        </div>
    </div>
</div> 
                                
                                

<?php include('includes/footer.php'); ?>


<?php 
include('includes/header.php'); 

if(isset($_SESSION['loggedIn'])){
    ?>
    <script>window.location.href = 'index.php';</script>
    <?php
}
?>

<div class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow rounded-4">

                <?php alertMessage(); ?>
                
                    <div class="p-5">
                      <h4 class="text-dark mb-3">Sign Up for the POS System</h4>
                      <form action="signup-code.php" method="POST">

                      <div class="mb-3">
                        <label>Select Account Type</label>
                        <select id="accountType" name="accountType" class="form-control" required>
                            <option value="" disabled selected>Select Account Type</option>
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                        </select>
                      </div>

                      <div class="mb-3" id="registrationIdField" style="display: none;">
                        <label>Enter Registration ID</label>
                        <input type="text" name="registration_id" class="form-control" />
                      </div>

                      <div class="mb-3" id="staffIdField" style="display: none;">
                        <label>Enter Staff ID</label>
                        <input type="text" name="staff_id" class="form-control" />
                      </div>

                      <div class="mb-3">
                        <label>Enter Name</label>
                        <input type="text" name="name" class="form-control" required />
                      </div>

                      <div class="mb-3">
                        <label>Enter Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required />
                      </div>

                      <div class="mb-3">
                        <label>Enter Email Id</label>
                        <input type="email" name="email" class="form-control" required />
                      </div>

                      <div class="mb-3">
                        <label>Enter Password</label>
                        <input type="password" name="password" class="form-control" required />
                      </div>

                      <div class="mt-3">
                        <button type="submit" name="signupBtn" class="btn btn-primary w-100 mt-2">
                            Sign Up
                        </button>
                      </div>

                      </form>  
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// JavaScript to toggle the visibility of registration ID and staff ID fields
document.getElementById('accountType').addEventListener('change', function() {
    var registrationIdField = document.getElementById('registrationIdField');
    var staffIdField = document.getElementById('staffIdField');
    if (this.value === 'admin') {
        staffIdField.style.display = 'block';
        registrationIdField.style.display = 'none';
    } else if (this.value === 'user') {
        staffIdField.style.display = 'none';
        registrationIdField.style.display = 'block';
    } else {
        staffIdField.style.display = 'none';
        registrationIdField.style.display = 'none';
    }
});
</script>

<?php include('includes/footer.php'); ?>

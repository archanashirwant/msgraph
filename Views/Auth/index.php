<?php if(isset($_SESSION['userEmail'])) { ?>
<form method="POST" action="/msgraph/Auth/addUser" class="needs-validation">
 <p class="h4 mb-4">User Registration</p>
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">First name</label>
      <input type="text" name="givenName" class="form-control" id="validationDefault01" placeholder="John" required>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationDefault02">Last name</label>
      <input type="text" namme="surname" class="form-control" id="validationDefault02" placeholder="Gates" required>
    </div>
   </div>
   
   <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Username</label>
      <div class="input-group">
        <input type="text" name="displayName" class="form-control" id="validationDefaultUsername"  placeholder="johngates" required>
      </div>
    </div>
	<div class="col-md-4 mb-3">
      <label for="validationDefaultEmail">Email</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">@</span>
        </div>
        <input type="text" name="mail" class="form-control" id="validationDefaultEmail" placeholder="john.gates@multibankfx.com" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>
  </div>
  
  
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefaultJob">Job Title</label>
      <input type="text" name="jobTitle" class="form-control" id="validationDefaultJob" placeholder="Developer"  required>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationDefaultDept">Department</label>
      <input type="text" name="department" class="form-control" id="validationDefaultDept" placeholder="IT" required>
    </div>
   </div>
   
   <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefaultPhone">Phone</label>
      <div class="input-group">
        <input type="text" name="mobilePhone"  class="form-control" id="validationDefaultPhone" placeholder="+971563467347" required>
      </div>
    </div>
	<div class="col-md-4 mb-3">
      <label for="validationDefaultManager">Manager</label>     
        <select name="manager" class="custom-select" required>
		<?php foreach($managers as $manager) {
			echo "<option value=".$manager['id'].">".$manager['displayName']."</option>";
		}?>
		</select>
    </div>
  </div>
  
  
  
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefaultOffLoc">Office Location</label>
      <input type="text" name="officeLocation" class="form-control" id="validationDefaultOffLoc" placeholder="Business Bay" required>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationDefaultCountry">Country</label>
      <input type="text" name="country" class="form-control" id="validationDefaultCountry" placeholder="UAE" required>
    </div>
  </div>
   
   <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefaultCity">City</label>
        <input type="text" name="city" class="form-control" id="validationDefaultCity" placeholder="Dubai"  required>
  	</div> 
   </div>
  
  <button class="btn btn-info" type="submit">Add User</button>
</form>
<?php } else { ?>
<p>To use this application you need to be logged in</p>
            <a href="/msgraph/Auth/sign" class="btn btn-info">Sign In</a>
<?php }?>





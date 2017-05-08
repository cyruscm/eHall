function formhash(form, password) {
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
 
    // Finally submit the form. 
    form.submit();
}

function formForgotHash(form, password, confirmpwd){
  if (password.value == confirmpwd.value){
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    confirmpwd.value= "";
 
    // Finally submit the form. 
    form.submit();
  }
}

function formChangeHash(form, oldPass, newPass, confNewPass) {
    if ((newPass.value == confNewPass.value) && (newPass.length != 0) && (oldPass.length != 0 )){
      var p = document.createElement("input");
      var np = document.createElement("input"); 
      
      form.appendChild(p);
      p.name = "p";
      p.type = "hidden";
      p.value = hex_sha512(oldPass.value);
 
      form.appendChild(np);
      np.name = "np";
      np.type = "hidden";
      np.value = hex_sha512(newPass.value);

      form.oldPass.value=null;
      form.password.value=null;
      form.confirmpwd.value=null;
      form.submit();
    }
    else{alert("New Passwords Don't Match");}
}
 
function regformhash(form, fName, lName, email, password, conf) {
     // Check each field has a value
    if (fName.value == ''         || 
          lName.value == ''         ||
          email.value == ''     || 
          password.value == ''  || 
          conf.value == '') {
 
        alert('You must provide all the requested details. Please try again');
        return false;
    }
 
    // Check the username
 
    re = /^[a-zA-Z]*$/;
    if(!re.test(form.fName.value)) { 
        alert("First and Last name can only contain letters and hyphens."); 
        form.username.focus();
        return false; 
    }
 
    re = /^[a-zA-Z]*$/;
    if(!re.test(form.lName.value)) { 
        alert("First and Last name can only contain letters and hyphens."); 
        form.username.focus();
        return false; 
    }

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 6) {
        alert('Passwords must be at least 6 characters long.  Please try again');
        form.password.focus();
        return false;
    }
 
    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
 
    //var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
    //if (!re.test(password.value)) {
    //    alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
    //    return false;
    //}
 
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match. Please try again');
        form.password.focus();
        return false;
    }
 
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = hex_sha512(password.value);
    alert(p.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}
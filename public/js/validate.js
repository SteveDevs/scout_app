function splChars(ele) {
  var check = true;
  ele.forEach(e => {
    if((e.prop('id') == 'email') || (e.prop('id') == 'mobile') || (e.prop('id') == 'password')){     
    }else{
      if(!(/^[a-zA-Z0-9\s]*$/.test(e.val()))){
        check = false;
      }
    } 
  });
  return check;
}

function validEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

function validPhone(str) {
  console.log(str);
  var regex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
  return regex.test(str);
}

function validPassword(password) 
{ 
  var check =  /^[A-Za-z]\w{6,15}$/;
  if(password.match(check)) 
  { 
    return true;
  }
  else
  { 
    return false;
  }
}
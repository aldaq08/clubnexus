
/*LOGOUT BUTTON CONFIRMATTION*/
/*LOGOUT BUTTON CONFIRMATTION*/
 const modal = document.getElementById('confirmationModal');
 const confirmButton = document.getElementById('confirmButton');
 const confirmYes = document.getElementById('confirmYes');
 const confirmNo = document.getElementById('confirmNo');

  confirmButton.addEventListener('click', function() {
    modal.style.display = 'block';
  });


  confirmYes.addEventListener('click', function() {
    window.location.replace('../reg-user/login_form.php');
  });

  confirmNo.addEventListener('click', function() {
    modal.style.display = 'none';
  });

   window.addEventListener('click', function(event) {
    if (event.target === modal) {
      modal.style.display = 'none';
      }
  });

  window.history.pushState(null, '', window.location.href);
  window.onpopstate = function () {
    window.history.pushState(null, '', window.location.href);
  };

/*END LOGOUT BUTTON CONFIRMATTION*/
/*END LOGOUT BUTTON CONFIRMATTION*/




// Import Bootstrap and Alpine
import 'bootstrap';
import Alpine from 'alpinejs'; 

window.Alpine = Alpine;
Alpine.start();

// Your custom JavaScript (including AJAX)
const myButton = document.querySelector('#myButton'); 

if (myButton) {
  myButton.addEventListener('click', () => {
    // AJAX request using Fetch API
    fetch('/wp-admin/admin-ajax.php', { 
      method: 'POST',
      body: new FormData(document.querySelector('#myForm')), // Example form
    })
    .then(response => response.json())
    .then(data => {
      // Handle the AJAX response
      console.log(data); 
    });
  });
}

console.log(":) " + myAjax.ajax_url);
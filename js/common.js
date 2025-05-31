
function showToast(message, isSuccess=true) {
    $(document).Toasts('create', {
      class: isSuccess ? 'bg-success' : 'bg-danger',
      title: isSuccess ? 'Success' : 'Error',
      position: 'bottomRight',
      body: message,
      autohide: true,
      delay: 3000
    });
}
// $("docuemnt").ready(function(){
//     showToast("Hello World", true);
// });
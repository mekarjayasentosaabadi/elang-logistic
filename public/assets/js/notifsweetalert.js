function notifSweetAlertSuccess(message){
    Swal.fire({
        title: 'Success.!',
        text: message,
        icon: 'success',
        confirmButtonText: 'OK'
    });
}

function notifSweetAlertErrors(message){
    const dataErrors = []
    for(i in message){
        dataErrors.push(message[i]);
    }
    Swal.fire({
        title: 'Error',
        text: dataErrors,
        icon: 'error',
        confirmButtonText: 'OK'
    });
}

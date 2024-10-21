function notifSweetAlertSuccess(message) {
    Swal.fire({
        title: "Success.!",
        text: message,
        icon: "success",
        confirmButtonText: "OK",
    });
}

function notifSweetAlertErrors(message) {
    // if array of errors
    let errors = "";
    if (Array.isArray(message)) {
        errors = "<ul>";
        message.forEach((error) => {
            errors += `<li>${error}</li>`;
        });
        errors += "</ul>";
    } else {
        errors = message;
    }

    Swal.fire({
        title: "Error",
        text: errors,
        icon: "error",
        confirmButtonText: "OK",
    });
}

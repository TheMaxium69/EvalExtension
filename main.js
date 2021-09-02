let message;

document.addEventListener('DOMContentLoaded', function () {
    message = document.querySelector(".tirage-ex-info");
    if (typeof (message) !== undefined && message !== null) {
        message.addEventListener("click", hideInfo);
    }
})

function hideInfo() {
    message.remove();
}




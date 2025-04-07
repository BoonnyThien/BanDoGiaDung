function toast({ title = '', message = '', type = 'success', duration = 1100 }) {
    const main = document.getElementById('toast');
    if (main) {
        const delay = (duration / 1000).toFixed(2);
        main.classList.add('display_flex')
        const toast = document.createElement('div');
        toast.classList.add('toast', `toast--${type}`);
        toast.style.animation = `slideInLeft ease .3s, fadeOut linear 1s ${delay}s forwards`
        toast.innerHTML = `<div class="toast__icon">
                            <i class="fa-brands fa-shopify"></i>
                        </div>
                        <div class="toast__body">
                            <h3 class="toast__title">${title}</h3>
                            <p class="toast__message">${message}</p>
                        </div>`;
        main.appendChild(toast)

        const test = setTimeout(function () {
            main.removeChild(toast)
            main.classList.remove('display_flex')
        }, duration + 1000)
    }
}

function showsuccess(message) {
    toast({
        title: 'SUCCESS !!!',
        message: message,
        type: 'success'
    })
}

function showerror(message) {
    toast({
        title: 'WANNING !!!',
        message: message,
        type: 'error'
    })
}
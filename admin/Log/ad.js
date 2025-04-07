document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        document.querySelector("#intro h1").classList.add("fade-out");
    }, 2);

    setTimeout(function() {
        document.getElementById("intro").style.display = "none";
        document.getElementById("main").style.display = "block";
        document.getElementById("leftmenu").style.display = "flex";

    }, 3);
});
document.querySelector("#trangchu").addEventListener('click',function(){
    window.location.href="../../frontend/index.php";
    
});
function showOverlay(file) {
    var iframe = document.getElementById('iframe-detail');
    iframe.src = file;
    iframe.onload = function() {
        iframe.contentWindow.scrollTo(0, 0);
    };
}
document.addEventListener('DOMContentLoaded', function() {
    showOverlay('./quanli.php'); 
});
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.quanli button');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            buttons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
        });
    });
});







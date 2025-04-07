
document.getElementById('blank').addEventListener('click',function(){
    document.getElementById('user_name').value='';
    document.getElementById('user_phone').value='';
    document.getElementById('user_email').value='';
    document.getElementById('user_address').value='';
    document.getElementById('user_account').value='';
    document.getElementById('user_pass').value='';
    document.getElementById('rule').value='';
});

function submitDeleteForm() {
    if (confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
        document.getElementById('delete_selected').click();
    }
}
function showOverlay(userId) {
    document.getElementById('iframe-detail').src = '../../admin/php/chitietuser.php?id=' + userId;
    document.getElementById('overlay').style.visibility = 'visible';
}

function hideOverlay() {
    document.getElementById('overlay').style.visibility = 'hidden';
}
function previewImage(event, previewId) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById(previewId);
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
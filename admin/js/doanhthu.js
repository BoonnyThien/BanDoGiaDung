function submitDeleteForm() {
    if (confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
        document.getElementById('delete_selected').click();
    }
}
function showOverlay(userId) {
    document.getElementById('iframe-detail').src = '../../admin/php/chitietdonhang.php?id=' + userId;
    document.getElementById('overlay').style.visibility = 'visible';
}

function hideOverlay() {
    document.getElementById('overlay').style.visibility = 'hidden';
}

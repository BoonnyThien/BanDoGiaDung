function submitDeleteForm() {
    if (confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
        document.getElementById('delete_selected').click();
    }
}
function showOverlay(Id) {
    document.getElementById('iframe-detail').src = '../../admin/php/chitietbaocao.php?id=' + Id;
    document.getElementById('overlay').style.visibility = 'visible';
}

function hideOverlay() {
    document.getElementById('overlay').style.visibility = 'hidden';
}
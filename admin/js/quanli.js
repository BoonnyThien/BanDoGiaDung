
document.getElementById('blank').addEventListener('click',function(){
    document.getElementById("name").value = '';
    document.getElementById("price_old").value = '';
    document.getElementById("price_new").value = '';
    document.getElementById("soluong").value = '';
    document.getElementById("hot").value = '';
    document.getElementById("xuatxu").value = '';
    document.getElementById("baohanh_date").value = '';
    document.getElementById("baohanh_option").value = '';
    document.getElementById("tinhnang").value = '';
    document.getElementById("chatlieu").value = '';
    document.getElementById("mota").value = '';
    // Thêm các input khác vào đây nếu cần
});
function submitDeleteForm() {
    if (confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
        document.getElementById('delete_selected').click();
    }
}
function showOverlay(Id) {
    document.getElementById('iframe-detail').src = '../../admin/php/chitietsanpham.php?id=' + Id;
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




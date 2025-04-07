
document.getElementById('blank').addEventListener('click',function(){
    document.getElementById("danhmuc_name").value = '';
    
    // Thêm các input khác vào đây nếu cần
});
function submitDeleteForm() {
    if (confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
        document.getElementById('delete_selected').click();
    }
}
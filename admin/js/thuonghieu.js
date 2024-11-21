
document.getElementById('blank').addEventListener('click',function(){
    document.getElementById("thuonghieu_name").value = '';
});
function submitDeleteForm() {
    if (confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
        document.getElementById('delete_selected').click();
    }
}
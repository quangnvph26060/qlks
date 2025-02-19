document.addEventListener('input', function (e) {
    if (e.target.classList.contains('money-input')) {
        let value = e.target.value.replace(/\D/g, ""); // Xóa ký tự không phải số
        value = Number(value).toLocaleString('vi-VN'); // Định dạng theo chuẩn Việt Nam
        e.target.value = value;
    }
});



function formatCurrency(amount) {
    if (!amount || isNaN(amount)) {
        return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
    }

    const parts = parseFloat(amount).toFixed(2).toString().split('.');
    const integerPart = parts[0];
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return formattedInteger + ' VND';
}

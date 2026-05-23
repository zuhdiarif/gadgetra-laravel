document.addEventListener('DOMContentLoaded', function () {
    const rentalStart = localStorage.getItem('rentalStartDate') || '22/11/26';
    const rentalEnd = localStorage.getItem('rentalEndDate') || '23/11/26';
    const rentalQty = localStorage.getItem('rentalQty') || 'X';
    const totalFormatted = localStorage.getItem('totalTagihanFormatted') || 'RpXXX.000';
    const productName = localStorage.getItem('productName') || 'Sony Alpha IV';
    const productImage = localStorage.getItem('productImage') || '/assets/products/Sony Alpha A7 IV Camera.png';

    const bcTanggalSewa = document.getElementById('bcTanggalSewa');
    if (bcTanggalSewa) {
        bcTanggalSewa.textContent = 'Tanggal sewa: ' + rentalStart + ' - ' + rentalEnd;
    }

    const bcJumlah = document.getElementById('bcJumlah');
    if (bcJumlah) {
        bcJumlah.textContent = 'Jumlah : ' + rentalQty + ' buah';
    }

    const bcTotalBiaya = document.getElementById('bcTotalBiaya');
    if (bcTotalBiaya) {
        bcTotalBiaya.textContent = totalFormatted;
    }

    const bcProductName = document.querySelector('.bc-product-name');
    if (bcProductName) {
        bcProductName.textContent = productName;
    }

    const bcProductImg = document.querySelector('.bc-product-img');
    if (bcProductImg) {
        bcProductImg.src = productImage;
        bcProductImg.alt = productName;
    }
});

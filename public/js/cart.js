'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    const selectedCountEl = document.getElementById('selectedCount');
    const totalPriceEl = document.getElementById('totalPrice');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const toast = document.getElementById('notificationToast');
    const toastMessage = document.getElementById('notificationMessage');

    function showToast(message, isSuccess = true) {
        toastMessage.textContent = message;
        const toastIcon = toast.querySelector('i');
        if (isSuccess) {
            toastIcon.className = 'fas fa-check-circle';
            toast.style.background = 'var(--primary-blue)';
        } else {
            toastIcon.className = 'fas fa-exclamation-circle';
            toast.style.background = 'var(--accent-red)';
        }
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    function calculateTotal() {
        let total = 0;
        let count = 0;
        let serviceFee = 0;

        document.querySelectorAll('.item-checkbox:checked').forEach(cb => {
            count++;
            const id = cb.getAttribute('data-id');
            const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
            const price = parseInt(cb.getAttribute('data-price'), 10);
            const qty = parseInt(itemRow.querySelector('.qty-input').value, 10) || 1;
            const startVal = itemRow.querySelector('.start-date-input').value;
            const endVal = itemRow.querySelector('.end-date-input').value;

            if (startVal && endVal) {
                const start = new Date(startVal);
                const end = new Date(endVal);
                let days = Math.round((end - start) / (1000 * 3600 * 24)) + 1;
                days = Math.max(1, days);
                total += price * qty * days;
            }
        });

        if (count > 0) {
            serviceFee = count * 2000;
        }

        const totalTagihan = total + serviceFee;

        selectedCountEl.textContent = `${count} Barang`;
        const serviceFeeEl = document.getElementById('serviceFee');
        if (serviceFeeEl) {
            serviceFeeEl.textContent = 'Rp' + serviceFee.toLocaleString('id-ID');
        }
        totalPriceEl.textContent = 'Rp' + totalTagihan.toLocaleString('id-ID');
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
            calculateTotal();
        });
    }

    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
            selectAllCheckbox.checked = allChecked;
            calculateTotal();
        });
    });

    calculateTotal();

    function updateCartOnServer(id) {
        const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
        const qty = parseInt(itemRow.querySelector('.qty-input').value, 10) || 1;
        const startDate = itemRow.querySelector('.start-date-input').value;
        const endDate = itemRow.querySelector('.end-date-input').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        if (!csrfToken || !startDate || !endDate) return;

        $.ajax({
            url: `/cart/${id}/update`,
            type: 'PUT',
            data: {
                _token: csrfToken.getAttribute('content'),
                qty: qty,
                start_date: startDate,
                end_date: endDate
            },
            success: function (response) {
                if (response && response.success) {
                    calculateTotal();
                }
            },
            error: function (xhr) {
                const errMsg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal memperbarui keranjang.';
                showToast(errMsg, false);
            }
        });
    }

    document.querySelectorAll('.increase-cart-qty').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
            const qtyInput = itemRow.querySelector('.qty-input');
            let qty = parseInt(qtyInput.value, 10) || 1;
            if (qty < 10) {
                qtyInput.value = qty + 1;
                updateCartOnServer(id);
            }
        });
    });

    document.querySelectorAll('.decrease-cart-qty').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
            const qtyInput = itemRow.querySelector('.qty-input');
            let qty = parseInt(qtyInput.value, 10) || 1;
            if (qty > 1) {
                qtyInput.value = qty - 1;
                updateCartOnServer(id);
            }
        });
    });

    document.querySelectorAll('.start-date-input, .end-date-input').forEach(input => {
        input.addEventListener('change', function () {
            const id = this.getAttribute('data-id');
            const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
            const startDate = itemRow.querySelector('.start-date-input').value;
            const endDate = itemRow.querySelector('.end-date-input').value;

            if (startDate && endDate) {
                if (new Date(endDate) <= new Date(startDate)) {
                    showToast('Tanggal selesai sewa harus setelah tanggal mulai sewa.', false);
                    return;
                }
                updateCartOnServer(id);
            }
        });
    });

    document.querySelectorAll('.delete-cart-item').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                return;
            }

            const id = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            if (!csrfToken) return;

            $.ajax({
                url: `/cart/${id}`,
                type: 'DELETE',
                data: {
                    _token: csrfToken.getAttribute('content')
                },
                success: function (response) {
                    if (response && response.success) {
                        showToast(response.message, true);
                        document.querySelectorAll('.cart-count-badge').forEach(badge => {
                            badge.textContent = response.cart_count;
                        });
                        const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
                        if (itemRow) {
                            itemRow.remove();
                        }
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            window.location.reload();
                        } else {
                            calculateTotal();
                        }
                    }
                },
                error: function (xhr) {
                    showToast('Gagal menghapus produk.', false);
                }
            });
        });
    });

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Silakan pilih produk yang ingin disewa terlebih dahulu!');
                return;
            }

            const selectedItems = [];
            let valid = true;

            checkedBoxes.forEach(cb => {
                const id = cb.getAttribute('data-id');
                const itemRow = document.querySelector(`.cart-item[data-id="${id}"]`);
                const qty = parseInt(itemRow.querySelector('.qty-input').value, 10) || 1;
                const startDate = itemRow.querySelector('.start-date-input').value;
                const endDate = itemRow.querySelector('.end-date-input').value;
                const name = cb.getAttribute('data-name');
                const price = parseInt(cb.getAttribute('data-price'), 10);
                const image = cb.getAttribute('data-image');
                const slug = cb.getAttribute('data-slug');

                if (!startDate || !endDate) {
                    valid = false;
                    return;
                }

                const start = new Date(startDate);
                const end = new Date(endDate);
                let durationDays = Math.round((end - start) / (1000 * 3600 * 24)) + 1;
                durationDays = Math.max(1, durationDays);

                selectedItems.push({
                    cart_id: id,
                    product_slug: slug,
                    product_name: name,
                    product_image: image,
                    productPrice: price,
                    qty: qty,
                    start_date: startDate,
                    end_date: endDate,
                    durationDays: durationDays
                });
            });

            if (!valid) {
                alert('Pastikan tanggal mulai dan selesai sewa sudah diisi untuk semua produk terpilih!');
                return;
            }

            localStorage.setItem('isCartCheckout', 'true');
            localStorage.setItem('checkoutItems', JSON.stringify(selectedItems));

            window.location.href = '/payment/method';
        });
    }
});

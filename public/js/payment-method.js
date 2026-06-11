document.addEventListener('DOMContentLoaded', function () {
    const paymentRows = document.querySelectorAll('.payment-method-row');
    const noteTextarea = document.querySelector('.note-textarea');
    const noteCounter = document.querySelector('.note-counter');
    const payNowBtn = document.querySelector('.btn-pay-now');

    const isCart = localStorage.getItem('isCartCheckout') === 'true';
    const SERVICE_FEE = 2000;
    
    let totalSewa = 0;
    let rentalQty = 0;

    if (isCart) {
        const checkoutItems = JSON.parse(localStorage.getItem('checkoutItems') || '[]');
        const breadcrumbProductLink = document.getElementById('breadcrumbProductLink');
        if (breadcrumbProductLink) {
            breadcrumbProductLink.textContent = 'Keranjang';
            breadcrumbProductLink.href = '/cart';
        }

        const listContainer = document.getElementById('checkoutProductsList');
        if (listContainer) {
            listContainer.innerHTML = '';
            checkoutItems.forEach(item => {
                const durationDays = item.durationDays;
                const qty = item.qty;
                const price = item.productPrice;
                rentalQty += qty;
                totalSewa += price * qty * durationDays;

                const productHtml = `
                    <div style="margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px;">
                        <div class="product-item-title" style="font-size: 16px; font-weight: 700; color: #1a1a1a; margin-bottom: 10px;">${item.product_name}</div>
                        <div class="product-item-body" style="display: flex; gap: 15px; align-items: center;">
                            <div class="product-item-image" style="width: 70px; height: 70px; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fff;">
                                <img src="${item.product_image}" alt="${item.product_name}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                            <div class="product-item-details" style="flex: 1;">
                                <div class="product-item-meta" style="font-size: 13px; color: #666; margin-bottom: 4px;">Masa Sewa : ${durationDays} Hari (${item.start_date} - ${item.end_date})</div>
                                <div class="product-item-price" style="font-size: 14px; font-weight: 600; color: #002d72; margin-bottom: 4px;">Rp${price.toLocaleString('id-ID')}/hari</div>
                                <div class="product-item-meta" style="font-size: 13px; color: #666;">Jumlah : ${qty} buah</div>
                            </div>
                        </div>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', productHtml);
            });
        }
    } else {
        const PRICE_PER_DAY = parseInt(localStorage.getItem('productPrice')) || 300000;
        const productName = localStorage.getItem('productName') || 'Sony Alpha IV';
        const productImage = localStorage.getItem('productImage') || '/assets/products/Sony Alpha A7 IV Camera.png';
        const productSlug = localStorage.getItem('productSlug') || 'sony-alpha-iv';

        rentalQty = parseInt(localStorage.getItem('rentalQty')) || 1;
        const rentalDays = parseInt(localStorage.getItem('rentalDurationDays')) || 3;

        const breadcrumbProductLink = document.getElementById('breadcrumbProductLink');
        if (breadcrumbProductLink) {
            breadcrumbProductLink.textContent = productName;
            breadcrumbProductLink.href = '/product/' + productSlug;
        }
        const itemTitleEl = document.querySelector('.product-item-title');
        if (itemTitleEl) {
            itemTitleEl.textContent = productName;
        }
        const itemImageEl = document.querySelector('.product-item-image img');
        if (itemImageEl) {
            itemImageEl.src = productImage;
            itemImageEl.alt = productName;
        }
        const itemPriceEl = document.querySelector('.product-item-price');
        if (itemPriceEl) {
            itemPriceEl.textContent = 'Rp' + PRICE_PER_DAY.toLocaleString('id-ID') + '/hari';
        }

        const displayMasaSewa = document.getElementById('displayMasaSewa');
        if (displayMasaSewa) {
            displayMasaSewa.textContent = 'Masa Sewa : ' + rentalDays + ' Hari';
        }
        const displayJumlah = document.getElementById('displayJumlah');
        if (displayJumlah) {
            displayJumlah.textContent = 'Jumlah : ' + rentalQty + ' buah';
        }

        totalSewa = PRICE_PER_DAY * rentalDays * rentalQty;
    }

    const totalTagihan = totalSewa + SERVICE_FEE;

    function formatRupiah(amount) {
        return 'Rp' + amount.toLocaleString('id-ID');
    }

    const summaryTotalSewaLabel = document.getElementById('summaryTotalSewaLabel');
    const summaryTotalSewaVal = document.getElementById('summaryTotalSewaVal');
    const summaryTotalTagihanVal = document.getElementById('summaryTotalTagihanVal');

    if (summaryTotalSewaLabel) {
        summaryTotalSewaLabel.textContent = 'Total Sewa (' + rentalQty + ' Barang)';
    }
    if (summaryTotalSewaVal) {
        summaryTotalSewaVal.textContent = formatRupiah(totalSewa);
    }
    if (summaryTotalTagihanVal) {
        summaryTotalTagihanVal.textContent = formatRupiah(totalTagihan);
    }

    localStorage.setItem('totalTagihan', totalTagihan);
    localStorage.setItem('totalTagihanFormatted', formatRupiah(totalTagihan));

    paymentRows.forEach(row => {
        row.addEventListener('click', function () {
            paymentRows.forEach(r => r.classList.remove('active'));
            this.classList.add('active');
        });
    });

    if (noteTextarea && noteCounter) {
        noteTextarea.addEventListener('input', function () {
            const currentLength = this.value.length;
            noteCounter.textContent = `${currentLength}/200`;
        });
    }

    if (payNowBtn) {
        payNowBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const activePayment = document.querySelector('.payment-method-row.active');

            if (!activePayment) {
                alert('Silakan pilih metode pembayaran terlebih dahulu!');
                return;
            }

            const selectedMethod = activePayment.querySelector('.payment-method-text').textContent;
            localStorage.setItem('selectedPaymentMethod', selectedMethod);

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) return;

            // Show loading state on button
            payNowBtn.disabled = true;
            payNowBtn.textContent = 'Memproses Pembayaran...';

            const isCart = localStorage.getItem('isCartCheckout') === 'true';

            function handlePaymentResponse(response) {
                if (response && response.success) {
                    const snapToken = response.snap_token;
                    const transactionCode = response.code;
                    const redirectCode = response.codes ? response.codes.join(',') : transactionCode;

                    if (response.codes) {
                        localStorage.setItem('bookingCodes', JSON.stringify(response.codes));
                    }
                    localStorage.setItem('bookingCode', transactionCode);

                    if (snapToken.startsWith('mock-token-')) {
                        const mockModal = document.getElementById('mockMidtransModal');
                        const mockTotal = document.getElementById('mockTotalTagihan');
                        if (mockModal && mockTotal) {
                            mockTotal.textContent = 'Rp' + totalTagihan.toLocaleString('id-ID');
                            mockModal.style.display = 'flex';

                            const btnSuccess = document.getElementById('btnMockSuccess');
                            const btnCancel = document.getElementById('btnMockCancel');

                            btnSuccess.onclick = function() {
                                mockModal.style.display = 'none';
                                window.location.href = '/booking/code?code=' + redirectCode;
                            };

                            btnCancel.onclick = function() {
                                mockModal.style.display = 'none';
                                payNowBtn.disabled = false;
                                payNowBtn.textContent = 'Bayar Sekarang';
                                alert('Simulasi pembayaran dibatalkan.');
                            };
                        }
                    } else {
                        if (typeof snap !== 'undefined') {
                            snap.pay(snapToken, {
                                onSuccess: function (result) {
                                    window.location.href = '/booking/code?code=' + redirectCode;
                                },
                                onPending: function (result) {
                                    window.location.href = '/booking/code?code=' + redirectCode;
                                },
                                onError: function (result) {
                                    alert('Pembayaran gagal, silakan coba lagi.');
                                    payNowBtn.disabled = false;
                                    payNowBtn.textContent = 'Bayar Sekarang';
                                },
                                onClose: function () {
                                    alert('Anda menutup popup pembayaran.');
                                    payNowBtn.disabled = false;
                                    payNowBtn.textContent = 'Bayar Sekarang';
                                }
                            });
                        } else if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            window.location.href = '/booking/code?code=' + redirectCode;
                        }
                    }
                }
            }

            function handlePaymentError(xhr) {
                payNowBtn.disabled = false;
                payNowBtn.textContent = 'Bayar Sekarang';
                var msg = 'Transaksi gagal. Silakan coba lagi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = 'Transaksi gagal: ' + xhr.responseJSON.message;
                }
                alert(msg);
            }

            if (isCart) {
                const checkoutItems = JSON.parse(localStorage.getItem('checkoutItems') || '[]');
                const itemsData = checkoutItems.map(item => ({
                    cart_id: item.cart_id,
                    product_slug: item.product_slug,
                    product_name: item.product_name,
                    product_image: item.product_image.split('/').pop(),
                    qty: item.qty,
                    start_date: item.start_date,
                    end_date: item.end_date,
                }));

                $.ajax({
                    url: '/booking/store',
                    type: 'POST',
                    data: {
                        _token: csrfToken.getAttribute('content'),
                        is_cart: true,
                        items: JSON.stringify(itemsData)
                    },
                    success: handlePaymentResponse,
                    error: handlePaymentError
                });
            } else {
                const rentalStart = localStorage.getItem('rentalStartDate') || '';
                const rentalEnd = localStorage.getItem('rentalEndDate') || '';
                const rentalQty = Math.max(1, Math.min(10, parseInt(localStorage.getItem('rentalQty') || '1', 10)));
                const slug = (localStorage.getItem('productSlug') || '').replace(/[^a-z0-9-]/g, '');
                const productName = localStorage.getItem('productName') || 'Gadget';

                $.ajax({
                    url: '/booking/store',
                    type: 'POST',
                    data: {
                        _token: csrfToken.getAttribute('content'),
                        start_date: rentalStart,
                        end_date: rentalEnd,
                        qty: rentalQty,
                        total_price: totalTagihan - SERVICE_FEE, // ExpectedPrice is sum of details without service fee, service fee gets added inside controller
                        product_slug: slug,
                        product_name: productName,
                        product_image: (localStorage.getItem('productImage') || '').split('/').pop()
                    },
                    success: handlePaymentResponse,
                    error: handlePaymentError
                });
            }
        });
    }
});

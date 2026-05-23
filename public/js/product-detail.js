

document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            button.classList.add('active');
            const activePanel = document.getElementById(`panel-${targetTab}`);
            if (activePanel) {
                activePanel.classList.add('active');
            }
        });
    });

    const monthNames = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    let today = new Date();
    let calendarYear = today.getFullYear();
    let calendarMonth = today.getMonth();

    let rentalStartDate = null;
    let rentalEndDate = null;

    const calendarMonthYearLabel = document.getElementById('calendarMonthYear');
    const calendarDaysGrid = document.getElementById('calendarDays');
    const prevMonthBtn = document.getElementById('prevMonthBtn');
    const nextMonthBtn = document.getElementById('nextMonthBtn');

    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');
    const startCalendarIcon = document.getElementById('startCalendarIcon');
    const endCalendarIcon = document.getElementById('endCalendarIcon');

    function renderCalendar(year, month) {
        calendarDaysGrid.innerHTML = '';
        calendarMonthYearLabel.textContent = `${monthNames[month]} ${year}`;

        let firstDayIndex = new Date(year, month, 1).getDay();
        let adjustedFirstDay = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

        let totalDays = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < adjustedFirstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'calendar-day-cell empty-cell';
            calendarDaysGrid.appendChild(emptyCell);
        }

        for (let day = 1; day <= totalDays; day++) {
            const dayCell = document.createElement('div');
            dayCell.className = 'calendar-day-cell';
            dayCell.textContent = day;

            const thisCellDate = new Date(year, month, day);

            const compareDate = new Date(thisCellDate.getFullYear(), thisCellDate.getMonth(), thisCellDate.getDate());
            const compareToday = new Date(today.getFullYear(), today.getMonth(), today.getDate());

            if (compareDate < compareToday) {
                dayCell.classList.add('disabled-cell');
            } else {
                dayCell.addEventListener('click', () => selectDate(thisCellDate));
            }

            if (rentalStartDate && isSameDate(thisCellDate, rentalStartDate)) {
                dayCell.classList.add('selected-start');
            } else if (rentalEndDate && isSameDate(thisCellDate, rentalEndDate)) {
                dayCell.classList.add('selected-end');
            } else if (rentalStartDate && rentalEndDate && thisCellDate > rentalStartDate && thisCellDate < rentalEndDate) {
                dayCell.classList.add('in-range');
            }

            dayCell.addEventListener('mouseenter', () => {
                if (rentalStartDate && !rentalEndDate && !dayCell.classList.contains('disabled-cell')) {
                    highlightHoverRange(thisCellDate);
                }
            });

            calendarDaysGrid.appendChild(dayCell);
        }
    }

    function isSameDate(date1, date2) {
        return date1.getFullYear() === date2.getFullYear() &&
            date1.getMonth() === date2.getMonth() &&
            date1.getDate() === date2.getDate();
    }

    function selectDate(date) {
        if (!rentalStartDate || (rentalStartDate && rentalEndDate)) {
            rentalStartDate = date;
            rentalEndDate = null;
            startDateInput.value = formatDateDisplay(date);
            endDateInput.value = '';
        } else if (rentalStartDate && !rentalEndDate) {
            if (date < rentalStartDate) {
                rentalStartDate = date;
                startDateInput.value = formatDateDisplay(date);
            } else {
                rentalEndDate = date;
                endDateInput.value = formatDateDisplay(date);
            }
        }

        renderCalendar(calendarYear, calendarMonth);
    }

    function highlightHoverRange(hoveredDate) {
        const cells = calendarDaysGrid.querySelectorAll('.calendar-day-cell:not(.empty-cell):not(.disabled-cell)');
        cells.forEach(cell => {
            const dayNum = parseInt(cell.textContent);
            const cellDate = new Date(calendarYear, calendarMonth, dayNum);

            cell.classList.remove('in-range');

            if (cellDate > rentalStartDate && cellDate <= hoveredDate) {
                cell.classList.add('in-range');
            }
        });
    }

    function formatDateISO(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function formatDateDisplay(date) {
        const d = String(date.getDate()).padStart(2, '0');
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const y = String(date.getFullYear()).substring(2);
        return `${d}/${m}/${y}`;
    }

    prevMonthBtn.addEventListener('click', () => {
        calendarMonth--;
        if (calendarMonth < 0) {
            calendarMonth = 11;
            calendarYear--;
        }
        renderCalendar(calendarYear, calendarMonth);
    });

    nextMonthBtn.addEventListener('click', () => {
        calendarMonth++;
        if (calendarMonth > 11) {
            calendarMonth = 0;
            calendarYear++;
        }
        renderCalendar(calendarYear, calendarMonth);
    });

    renderCalendar(calendarYear, calendarMonth);

    const dateClickableElements = [startDateInput, endDateInput, startCalendarIcon, endCalendarIcon];
    dateClickableElements.forEach(el => {
        if (el) {
            el.addEventListener('click', () => {
                const calendarContainer = document.querySelector('.calendar-widget-container');
                calendarContainer.style.transform = 'scale(1.03)';
                calendarContainer.style.borderColor = 'var(--primary-blue)';
                calendarContainer.style.boxShadow = '0 10px 25px rgba(0, 45, 114, 0.1)';

                setTimeout(() => {
                    calendarContainer.style.transform = 'none';
                    calendarContainer.style.borderColor = 'var(--border-color)';
                    calendarContainer.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.03)';
                }, 600);

                if (window.innerWidth <= 992) {
                    calendarContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }
    });


    const decreaseQtyBtn = document.getElementById('decreaseQty');
    const increaseQtyBtn = document.getElementById('increaseQty');
    const qtyInput = document.getElementById('qtyInput');

    decreaseQtyBtn.addEventListener('click', () => {
        let currentValue = parseInt(qtyInput.value) || 1;
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    });

    increaseQtyBtn.addEventListener('click', () => {
        let currentValue = parseInt(qtyInput.value) || 1;
        if (currentValue < 10) {
            qtyInput.value = currentValue + 1;
        }
    });


    const addToCartBtn = document.getElementById('addToCartBtn');
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
        }, 3500);
    }

    addToCartBtn.addEventListener('click', () => {
        const qty = qtyInput.value;
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;

        if (!startVal || !endVal) {
            showToast('Silakan pilih tanggal mulai dan selesai sewa pada kalender terlebih dahulu!', false);

            const calendarContainer = document.querySelector('.calendar-widget-container');
            calendarContainer.style.animation = 'shake 0.5s ease-in-out';
            setTimeout(() => {
                calendarContainer.style.animation = '';
            }, 500);
            return;
        }

        const durationDays = Math.round((rentalEndDate - rentalStartDate) / (1000 * 3600 * 24)) + 1;
        const name = addToCartBtn.getAttribute('data-name') || 'Sony Alpha IV';
        const price = addToCartBtn.getAttribute('data-price') || '300000';
        const image = addToCartBtn.getAttribute('data-image') || '/assets/products/Sony Alpha A7 IV Camera.png';
        const slug = addToCartBtn.getAttribute('data-slug') || 'sony-alpha-iv';

        localStorage.setItem('rentalQty', qty);
        localStorage.setItem('rentalStartDate', formatDateISO(rentalStartDate));
        localStorage.setItem('rentalEndDate', formatDateISO(rentalEndDate));
        localStorage.setItem('rentalStartDateDisplay', formatDateDisplay(rentalStartDate));
        localStorage.setItem('rentalEndDateDisplay', formatDateDisplay(rentalEndDate));
        localStorage.setItem('rentalDurationDays', durationDays);
        localStorage.setItem('productName', name);
        localStorage.setItem('productPrice', price);
        localStorage.setItem('productImage', image);
        localStorage.setItem('productSlug', slug);

        window.location.href = '/payment/method';
    });
    const styleSheet = document.createElement("style");
    styleSheet.innerText = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }
    `;
    document.head.appendChild(styleSheet);
});

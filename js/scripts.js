/*!
 * Start Bootstrap - SB Admin v7.0.7
 * https://startbootstrap.com/template/sb-admin
 */


// Toggle sidebar
window.addEventListener('DOMContentLoaded', event => {

    const sidebarToggle =
        document.body.querySelector('#sidebarToggle');

    if (sidebarToggle) {

        sidebarToggle.addEventListener('click', event => {

            event.preventDefault();

            document.body.classList.toggle(
                'sb-sidenav-toggled'
            );

            // Simpan status sidebar
            localStorage.setItem(
                'sb|sidebar-toggle',
                document.body.classList.contains(
                    'sb-sidenav-toggled'
                )
            );
        });
    }

});


// Override SweetAlert z-index
const swalStyle = document.createElement('style');

swalStyle.textContent =
    '.swal2-container { z-index: 100000 !important; }';

document.head.appendChild(swalStyle);


// Override native alert()
window.alert = function(message) {

    let icon = 'info';

    const lowerMsg =
        message.toLowerCase();

    // Deteksi tipe alert
    if (
        lowerMsg.includes('❌') ||
        lowerMsg.includes('gagal') ||
        lowerMsg.includes('salah') ||
        lowerMsg.includes('tidak mencukupi') ||
        lowerMsg.includes('tidak boleh')
    ) {

        icon = 'error';

    } else if (
        lowerMsg.includes('⚠️') ||
        lowerMsg.includes('peringatan') ||
        lowerMsg.includes('harus') ||
        lowerMsg.includes('minimal') ||
        lowerMsg.includes('perlu') ||
        lowerMsg.includes('yakin')
    ) {

        icon = 'warning';

    } else if (
        lowerMsg.includes('✅') ||
        lowerMsg.includes('berhasil') ||
        lowerMsg.includes('sukses')
    ) {

        icon = 'success';
    }

    Swal.fire({

        title: 'Pemberitahuan',

        text: message
            .replace(/[⚠️❌✅]/g, '')
            .trim(),

        icon: icon,

        confirmButtonText: 'OK',

        customClass: {
            confirmButton:
                'btn btn-primary px-4 shadow-sm'
        },

        buttonsStyling: false
    });
};


// Pengganti native confirm()
function confirmAction(event, message) {

    event.preventDefault();

    // Cegah double confirm
    if (window.isActionConfirming) return;

    const target =
        event.currentTarget || event.target;

    let actionType = 'navigate';

    let formElement = null;

    // Deteksi tipe action
    if (target.tagName.toLowerCase() === 'form') {

        actionType = 'submit';

        formElement = target;

    } else if (
        target.closest('form') &&
        (
            target.type === 'submit' ||
            target.tagName.toLowerCase() === 'button'
        )
    ) {

        actionType = 'submit';

        formElement = target.closest('form');

    } else if (
        target.tagName.toLowerCase() === 'a' ||
        target.closest('a')
    ) {

        actionType = 'navigate';
    }

    Swal.fire({

        title: 'Apakah Anda yakin?',

        text: message,

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Ya, Lanjutkan!',

        cancelButtonText: 'Batal',

        customClass: {

            confirmButton:
                'btn btn-danger px-4 me-2 shadow-sm',

            cancelButton:
                'btn btn-secondary px-4 shadow-sm'
        },

        buttonsStyling: false

    }).then((result) => {

        if (result.isConfirmed) {

            window.isActionConfirming = true;

            Swal.showLoading();

            // Submit form
            if (
                actionType === 'submit' &&
                formElement
            ) {

                formElement.submit();

            // Redirect link
            } else if (actionType === 'navigate') {

                const anchor =
                    target.tagName.toLowerCase() === 'a'
                    ? target
                    : target.closest('a');

                if (anchor && anchor.href) {

                    window.location.href =
                        anchor.href;
                }
            }
        }
    });
}


// Cegah double submit form
document.addEventListener('submit', function(e) {

    if (e.defaultPrevented) return;

    const form = e.target;

    // Batalkan submit kedua
    if (form.dataset.submitting === 'true') {

        e.preventDefault();

        return;
    }

    form.dataset.submitting = 'true';

    const submitBtn =
        form.querySelector(
            'button[type="submit"], input[type="submit"]'
        );

    if (submitBtn) {

        // Disable tombol submit
        setTimeout(() => {

            submitBtn.style.pointerEvents = 'none';

            submitBtn.classList.add('disabled');

            if (
                submitBtn.tagName.toLowerCase() === 'button'
            ) {

                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';

            } else {

                submitBtn.value = 'Mengirim...';
            }

        }, 0);
    }
});
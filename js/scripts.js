/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

// ==========================================
// SWEETALERT2 GLOBAL OVERRIDES & HELPERS
// ==========================================

// Global Override for Native alert()
window.alert = function(message) {
    let icon = 'info';
    const lowerMsg = message.toLowerCase();
    
    if (lowerMsg.includes('❌') || lowerMsg.includes('gagal') || lowerMsg.includes('salah') || lowerMsg.includes('tidak mencukupi') || lowerMsg.includes('tidak boleh')) {
        icon = 'error';
    } else if (lowerMsg.includes('⚠️') || lowerMsg.includes('peringatan') || lowerMsg.includes('harus') || lowerMsg.includes('minimal') || lowerMsg.includes('perlu') || lowerMsg.includes('yakin')) {
        icon = 'warning';
    } else if (lowerMsg.includes('✅') || lowerMsg.includes('berhasil') || lowerMsg.includes('sukses')) {
        icon = 'success';
    }
    
    Swal.fire({
        title: 'Pemberitahuan',
        text: message.replace(/[⚠️❌✅]/g, '').trim(), // Clean up styling emojis in text
        icon: icon,
        confirmButtonText: 'OK',
        customClass: {
            confirmButton: 'btn btn-primary px-4 shadow-sm'
        },
        buttonsStyling: false
    });
};

// Global Helper for Replacement of native confirm()
function confirmAction(event, message) {
    event.preventDefault();
    
    // Prevent double confirmation trigger
    if (window.isActionConfirming) return;
    
    const target = event.currentTarget || event.target;
    
    let actionType = 'navigate';
    let formElement = null;
    
    if (target.tagName.toLowerCase() === 'form') {
        actionType = 'submit';
        formElement = target;
    } else if (target.closest('form') && (target.type === 'submit' || target.tagName.toLowerCase() === 'button')) {
        actionType = 'submit';
        formElement = target.closest('form');
    } else if (target.tagName.toLowerCase() === 'a' || target.closest('a')) {
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
            confirmButton: 'btn btn-danger px-4 me-2 shadow-sm',
            cancelButton: 'btn btn-secondary px-4 shadow-sm'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.isActionConfirming = true;
            Swal.showLoading();
            if (actionType === 'submit' && formElement) {
                formElement.submit();
            } else if (actionType === 'navigate') {
                const anchor = target.tagName.toLowerCase() === 'a' ? target : target.closest('a');
                if (anchor && anchor.href) {
                    window.location.href = anchor.href;
                }
            }
        }
    });
}

// Global Double Submission Prevention for All Native Forms
document.addEventListener('submit', function(e) {
    if (e.defaultPrevented) return;
    
    const form = e.target;
    
    // If the form is already submitting, cancel the subsequent submit event synchronously!
    if (form.dataset.submitting === 'true') {
        e.preventDefault();
        return;
    }
    
    form.dataset.submitting = 'true';
    
    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitBtn) {
        // Use setTimeout to allow any custom client-side validation to run and browser events to settle
        setTimeout(() => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengirim...';
        }, 0);
    }
});


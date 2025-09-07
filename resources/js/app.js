import "./bootstrap";

// ================ ADMIN JAVASCRIPT ================
document.addEventListener("DOMContentLoaded", function () {
    // Only run admin scripts if we're on admin layout
    const adminLayout = document.querySelector(".admin-layout");
    if (!adminLayout) return;

    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.querySelector(".admin-sidebar");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("show");

            // Add/remove overlay for mobile
            if (window.innerWidth <= 991) {
                let overlay = document.querySelector(".sidebar-overlay");
                if (!overlay) {
                    overlay = document.createElement("div");
                    overlay.className = "sidebar-overlay";
                    document.body.appendChild(overlay);

                    overlay.addEventListener("click", function () {
                        sidebar.classList.remove("show");
                        overlay.classList.remove("show");
                    });
                }
                overlay.classList.toggle("show");
            }
        });
    }

    // Auto dismiss alerts
    const autoAlerts = document.querySelectorAll("[data-auto-dismiss]");

    autoAlerts.forEach(function (alert) {
        const dismissTime = parseInt(alert.getAttribute("data-auto-dismiss"));

        if (dismissTime > 0) {
            setTimeout(function () {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, dismissTime);
        }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", function (e) {
        if (window.innerWidth <= 991) {
            const sidebar = document.querySelector(".admin-sidebar");
            const toggle = document.getElementById("sidebarToggle");

            if (
                sidebar &&
                toggle &&
                !sidebar.contains(e.target) &&
                !toggle.contains(e.target)
            ) {
                sidebar.classList.remove("show");
                const overlay = document.querySelector(".sidebar-overlay");
                if (overlay) overlay.classList.remove("show");
            }
        }
    });

    // Handle window resize
    window.addEventListener("resize", function () {
        if (window.innerWidth > 991) {
            const sidebar = document.querySelector(".admin-sidebar");
            const overlay = document.querySelector(".sidebar-overlay");

            if (sidebar) sidebar.classList.remove("show");
            if (overlay) overlay.classList.remove("show");
        }
    });

    // Dropdown submenu toggle
    const dropdownToggles = document.querySelectorAll(
        '[data-bs-toggle="collapse"]'
    );
    dropdownToggles.forEach(function (toggle) {
        toggle.addEventListener("click", function (e) {
            e.preventDefault();

            const target = document.querySelector(
                this.getAttribute("data-bs-target")
            );
            const isExpanded = this.getAttribute("aria-expanded") === "true";

            // Close other dropdowns
            dropdownToggles.forEach(function (otherToggle) {
                if (otherToggle !== toggle) {
                    otherToggle.setAttribute("aria-expanded", "false");
                    const otherTarget = document.querySelector(
                        otherToggle.getAttribute("data-bs-target")
                    );
                    if (otherTarget) {
                        otherTarget.classList.remove("show");
                    }
                }
            });

            // Toggle current dropdown
            this.setAttribute("aria-expanded", !isExpanded);
            if (target) {
                target.classList.toggle("show");
            }
        });
    });

    // Toast notification function (Global untuk admin)
    window.showToast = function (type, title, message, duration = 5000) {
        const toastContainer = document.querySelector(".toast-container");
        if (!toastContainer) return;

        const toastId = "toast-" + Date.now();

        const iconMap = {
            success: "fa-check-circle text-success",
            error: "fa-exclamation-circle text-danger",
            warning: "fa-exclamation-triangle text-warning",
            info: "fa-info-circle text-info",
        };

        const icon = iconMap[type] || iconMap["info"];

        const toastHtml = `
            <div id="${toastId}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="fas ${icon} me-2"></i>
                    <strong class="me-auto">${title}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML("beforeend", toastHtml);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            delay: duration,
        });

        toast.show();

        // Remove toast after hidden
        toastElement.addEventListener("hidden.bs.toast", function () {
            toastElement.remove();
        });
    };

    // Admin form enhancements
    const adminForms = document.querySelectorAll(".admin-layout form");
    adminForms.forEach(function (form) {
        // Add loading state to submit buttons
        form.addEventListener("submit", function (e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                // Reset after 10 seconds if still processing
                setTimeout(function () {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 10000);
            }
        });
    });

    // Admin table enhancements
    const adminTables = document.querySelectorAll(".admin-layout table");
    adminTables.forEach(function (table) {
        // Add hover effect to rows
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            row.addEventListener("mouseenter", function () {
                this.style.backgroundColor = "rgba(201, 168, 119, 0.1)";
            });
            row.addEventListener("mouseleave", function () {
                this.style.backgroundColor = "";
            });
        });
    });

    // Confirmation dialogs for delete actions
    const deleteButtons = document.querySelectorAll(
        ".admin-layout [data-confirm-delete]"
    );
    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (e) {
            const message =
                this.getAttribute("data-confirm-delete") ||
                "Apakah Anda yakin ingin menghapus item ini?";
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // Auto-hide alerts after some time
    const alerts = document.querySelectorAll(
        ".admin-layout .alert:not([data-auto-dismiss])"
    );
    alerts.forEach(function (alert) {
        if (!alert.querySelector(".btn-close")) return;

        setTimeout(function () {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    console.log("✅ Admin JavaScript loaded successfully");
});

// ================ USER/FRONTEND JAVASCRIPT ================
// Add any frontend-specific JavaScript here for user-facing pages

// Global utilities that work for both admin and frontend
window.formatCurrency = function (amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
};

window.formatNumber = function (number) {
    return new Intl.NumberFormat("id-ID").format(number);
};

// ================ Global Time and Date Formatting ================
window.formatDateFromUTC = function (utcDateString) {
    if (!utcDateString) return "";
    const date = new Date(utcDateString);

    // Opsi format untuk tanggal dan waktu numerik
    const options = {
        day: "2-digit",
        month: "2-digit",
        year: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        hour12: false, // Pastikan format 24 jam
    };

    // Gunakan Intl.DateTimeFormat untuk format lokal otomatis
    const formatter = new Intl.DateTimeFormat(navigator.language, options);

    // Ambil bagian-bagian yang diformat
    const parts = formatter.formatToParts(date);
    let day = parts.find((part) => part.type === "day").value;
    let month = parts.find((part) => part.type === "month").value;
    let year = parts.find((part) => part.type === "year").value;
    let hour = parts.find((part) => part.type === "hour").value;
    let minute = parts.find((part) => part.type === "minute").value;

    // Gabungkan bagian-bagian tersebut
    return `${day}-${month}-${year} ${hour}:${minute}`;
};

// Terapkan format ke semua elemen dengan data-utc-time
document.addEventListener("DOMContentLoaded", function () {
    const timeElements = document.querySelectorAll("[data-utc-time]");
    timeElements.forEach(function (el) {
        const utcTime = el.getAttribute("data-utc-time");
        el.textContent = window.formatDateFromUTC(utcTime);
    });
});

// Debug helper
window.debugBoomtale = function () {
    console.log("🚀 Boomtale Debug Info:");
    console.log("- Admin Layout:", !!document.querySelector(".admin-layout"));
    console.log("- Bootstrap loaded:", typeof bootstrap !== "undefined");
    console.log("- jQuery loaded:", typeof $ !== "undefined");
    console.log("- Vite HMR:", import.meta.hot ? "Active" : "Inactive");
};

<style>
.custom-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 280px;
    z-index: 9999;
    display: none;
    border-radius: 8px;
    padding: 15px 20px;
    color: white;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    animation: slideIn 0.4s ease-out;
}

.toast-success { background-color: #28a745; }
.toast-error   { background-color: #dc3545; }
.toast-warning { background-color: #ffc107; color: #000; }
.toast-info    { background-color: #17a2b8; }

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<div id="customToast" class="custom-toast"></div>

<script>
function showToast(type, message) {
    const toast = document.getElementById("customToast");
    toast.className = "custom-toast toast-" + type;
    toast.innerHTML = message;
    toast.style.display = "block";

    setTimeout(() => {
        toast.style.display = "none";
    }, 3000);
}
</script>

{{-- Flash messages (Laravel way) --}}
@if (session('success'))
<script>
    showToast('success', @json(session('success')));
</script>
@endif

@if (session('error'))
<script>
    showToast('error', @json(session('error')));
</script>
@endif

@if (session('warning'))
<script>
    showToast('warning', @json(session('warning')));
</script>
@endif

@if (session('info'))
<script>
    showToast('info', @json(session('info')));
</script>
@endif

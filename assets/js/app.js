//===================================
//   Shared UI helpers (loaded on every page)

//   Copy buttons: <button data-copy-target="#inputId">Copy</button>
document.addEventListener('click', async event => {
    const button = event.target.closest('[data-copy-target]');
    if (!button) {
        return;
    }
    const target = document.querySelector(button.dataset.copyTarget);
    if (!target || target.value === '') {
        return;
    }

    try {
        await navigator.clipboard.writeText(target.value);
    } catch (e) {
        target.select();        // clipboard API unavailable: select it so Ctrl+C works
        return;
    }

    const label = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check2 me-1"></i>Copied';
    button.disabled = true;
    setTimeout(() => {
        button.innerHTML = label;
        button.disabled = false;
    }, 1500);
});

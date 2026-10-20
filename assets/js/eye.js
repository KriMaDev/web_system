//===================================
//      Eye controls (login/register): the pupil follows the mouse
document.addEventListener('DOMContentLoaded', function () {
    const eye = document.querySelector('.eye');
    if (!eye) {
        return;
    }
    const pupil = eye.querySelector('.pupil');

    document.addEventListener('mousemove', function (e) {
        const eyeRect  = eye.getBoundingClientRect();
        const eyeX     = eyeRect.left + eyeRect.width / 2;
        const eyeY     = eyeRect.top + eyeRect.height / 2;
        const angle    = Math.atan2(e.clientY - eyeY, e.clientX - eyeX);
        const distance = Math.min(eyeRect.width, eyeRect.height) / 4;
        pupil.style.transform = `translate(-50%, -50%) translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px)`;
    });
});

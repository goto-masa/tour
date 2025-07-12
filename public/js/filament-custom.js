console.log('filament-custom.js loaded');

function forceFlatpickr10minStep() {
    document.querySelectorAll('.flatpickr-input').forEach(function(input) {
        if (typeof flatpickr !== 'undefined') {
            if (input._flatpickr) {
                input._flatpickr.destroy();
            }
            flatpickr(input, {
                enableTime: true,
                noCalendar: false,
                dateFormat: "Y-m-d H:i",
                minuteIncrement: 10,
                time_24hr: true
            });
        }
    });
}

function ensureFlatpickrAndApply() {
    if (typeof flatpickr === 'undefined') {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
        script.onload = function() {
            forceFlatpickr10minStep();
        };
        document.head.appendChild(script);
    } else {
        forceFlatpickr10minStep();
    }
}

document.addEventListener('DOMContentLoaded', ensureFlatpickrAndApply);
document.addEventListener('livewire:navigated', ensureFlatpickrAndApply);
document.addEventListener('livewire:update', ensureFlatpickrAndApply);

const observer = new MutationObserver(ensureFlatpickrAndApply);
observer.observe(document.body, { childList: true, subtree: true }); 

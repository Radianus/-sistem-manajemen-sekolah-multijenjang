import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
window.Alpine = Alpine;
window.Swal = Swal;
Alpine.start();


flatpickr("#start_time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",   // format 24 jam
    time_24hr: true
});

flatpickr("#end_time", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",   // format 24 jam
    time_24hr: true
});





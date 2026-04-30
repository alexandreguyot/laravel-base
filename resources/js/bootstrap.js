import axios from 'axios'
import $ from 'jquery'
import select2 from 'select2'
import Dropzone from 'dropzone'
import flatpickr from 'flatpickr'

// jQuery global
window.$ = window.jQuery = $
select2(window, $)

// Dropzone — désactiver l'autodiscover pour contrôle manuel
Dropzone.autoDiscover = false
window.Dropzone = Dropzone

// Flatpickr global
window.flatpickr = flatpickr

// Axios
window.axios = axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// CSRF token
const token = document.querySelector('meta[name="csrf-token"]')
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

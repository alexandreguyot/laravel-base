import './bootstrap'
import { createIcons, icons } from 'lucide'

// ============================================================
// Lucide Icons — remplace tous les <i data-lucide="...">
// ============================================================
window.lucide = { createIcons, icons }

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons })
})

// Re-render les icônes après chaque navigation Livewire
document.addEventListener('livewire:navigated', () => {
    createIcons({ icons })
})

// ============================================================
// Sidebar mobile — toggle
// ============================================================
window.toggleNavbar = function (collapseID) {
    const el = document.getElementById(collapseID)
    if (!el) return
    el.classList.toggle('hidden')
    el.classList.toggle('bg-base-100')
    el.classList.toggle('m-2')
    el.classList.toggle('py-3')
    el.classList.toggle('px-6')
}

// ============================================================
// Sous-navigation — expand/collapse
// ============================================================
window.openSubNav = function (el) {
    el.nextElementSibling?.classList.toggle('hidden')
}

window.initialSubNavLoad = function () {
    document.querySelectorAll('.has-sub.sidebar-nav-active').forEach((el) => {
        window.openSubNav(el)
    })
}

initialSubNavLoad()

// ============================================================
// Dropdowns (remplace Popper)
// ============================================================
window.openDropdown = function (event, dropdownID) {
    let element = event.target
    while (element.nodeName !== 'A' && element.parentNode) {
        element = element.parentNode
    }
    const dropdown = document.getElementById(dropdownID)
    if (!dropdown) return
    dropdown.classList.toggle('hidden')
    dropdown.classList.toggle('block')

    if (dropdownID === 'nav-notification-dropdown') {
        fetch('/admin/user-alerts/seen')
    }
}

// ============================================================
// Thème DaisyUI — par utilisateur (DB) + localStorage fallback
// ============================================================
window.setTheme = function (theme, scope = 'user') {
    document.documentElement.setAttribute('data-theme', theme)
    localStorage.setItem('theme', theme)

    fetch('/admin/settings/theme', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
        body: JSON.stringify({ theme, scope }),
    })
}

// Au chargement : le data-theme est déjà défini côté serveur (LayoutHelper).
// On se contente de le synchroniser dans localStorage.
;(function () {
    const serverTheme = document.documentElement.getAttribute('data-theme')
    if (serverTheme) localStorage.setItem('theme', serverTheme)
})()

import './bootstrap';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import Alpine from 'alpinejs'

// Register TipTap Editor component globally
import TipTapEditor from './Components/TipTapEditor.vue'

window.TipTapEditor = TipTapEditor

// Start Alpine.js
window.Alpine = Alpine
Alpine.start()

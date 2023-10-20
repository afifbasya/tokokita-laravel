import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask'

window.Alpine = Alpine;

Alpine.plugin(mask)
Alpine.start();

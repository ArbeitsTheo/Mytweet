// assets/bootstrap.js
import { startStimulusApp } from '@symfony/stimulus-bridge';
require('bootstrap');
require('bootstrap/js/dist/popover');

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.(j|t)sx?$/
));

const $ = require('jquery');

const $j = $.noConflict();

$j(document).ready(function () {
    $j('[data-toggle="popover"]').popover();
});

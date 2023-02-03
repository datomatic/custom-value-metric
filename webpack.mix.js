let mix = require('laravel-mix')
const path = require('path')

require('./nova.mix')


mix
    .setPublicPath('dist')
    .js('resources/js/card.js', 'js')
    .vue({ version: 3 })
    .css('resources/css/card.css', 'css')
    .nova('datomatic/custom-value-metric');

let mix = require('laravel-mix')
let tailwindcss = require('tailwindcss')
const path = require('path')
let postcssImport = require('postcss-import')

require('./nova.mix')


mix
    .setPublicPath('dist')
    .js('resources/js/card.js', 'js')
    .vue({ version: 3 })
    .postCss('resources/css/card.css', 'dist/css/', [postcssImport(), tailwindcss('tailwind.config.js'),])
    .nova('datomatic/custom-value-metric');

const path = require('path');
const colors = require('tailwindcss/colors');
const {fontFamily} = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        path.resolve(__dirname, 'resources/**/*.{vue,js,ts,jsx,tsx,scss}'),
    ],
    darkMode: 'class',
    prefix: 'cvm-',
    plugins: [],

    safelist: [
        'cvm-col-span-1',
        'cvm-col-span-2',
        'cvm-col-span-3',
        'cvm-col-span-4',
        'cvm-col-span-5',
        'cvm-col-span-6'
    ]
};
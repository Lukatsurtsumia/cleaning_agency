import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        // The calendar picks its day classes in JS, so they must be scanned too.
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['"Cormorant Garamond"', ...defaultTheme.fontFamily.serif],
            },

            colors: {
                // Sampled from the brush-stroke mark on Tina's visit card.
                azur: {
                    50: '#f0f8f7',
                    100: '#d8ecea',
                    200: '#b1d9d5',
                    300: '#7dbfb9',
                    400: '#4a9f98',
                    500: '#2d827b',
                    600: '#1f6963',
                    700: '#1a5451',
                    800: '#164442',
                    900: '#133836',
                    950: '#0a2322',
                },
                // The card stock itself.
                sand: {
                    50: '#fdfbf6',
                    100: '#f7f1e4',
                    200: '#efe4cf',
                    300: '#e2d0af',
                },
            },

        },
    },

    plugins: [forms],
};

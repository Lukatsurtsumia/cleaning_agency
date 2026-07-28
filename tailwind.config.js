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
                // Balanced azure-teal: shifted toward the blue of the logo wave
                // while keeping some of the original deep-teal calm (a middle
                // ground between the old teal-green and a pure navy blue).
                azur: {
                    50: '#eef5fb',
                    100: '#d4e6f3',
                    200: '#a9cfe4',
                    300: '#73abce',
                    400: '#4388bd',
                    500: '#266ea2',
                    600: '#1d597f',
                    700: '#194a67',
                    800: '#173c56',
                    900: '#143045',
                    950: '#0a1d2b',
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

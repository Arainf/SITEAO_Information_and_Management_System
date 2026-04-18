import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Haas', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', ...defaultTheme.fontFamily.sans],
                display: ['Haas Groot Disp', 'Haas', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                navy: {
                    DEFAULT: '#181d26',
                    light: 'rgba(4,14,32,0.69)',
                },
                sims: {
                    blue: '#1b61c9',
                    'blue-hover': '#1550aa',
                    'blue-mid': '#254fad',
                    border: '#e0e2e6',
                    surface: '#f8fafc',
                    spotlight: 'rgba(249,252,255,0.97)',
                },
            },
            letterSpacing: {
                'sims-xs': '0.07px',
                'sims-sm': '0.08px',
                'sims-md': '0.12px',
                'sims-lg': '0.18px',
                'sims-xl': '0.28px',
            },
            boxShadow: {
                'sims-card': 'rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.08) 0px 0px 2px, rgba(45,127,249,0.28) 0px 1px 3px, rgba(0,0,0,0.06) 0px 0px 0px 0.5px inset',
                'sims-soft': 'rgba(15,48,106,0.05) 0px 0px 20px',
                'sims-hover': 'rgba(0,0,0,0.32) 0px 0px 1px, rgba(0,0,0,0.12) 0px 2px 6px, rgba(45,127,249,0.36) 0px 2px 8px',
            },
            borderRadius: {
                'sims-btn': '12px',
                'sims-card': '16px',
                'sims-section': '24px',
                'sims-lg': '32px',
            },
        },
    },

    plugins: [forms],
};

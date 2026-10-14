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
                sans: ['Figtree', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                cyan: {
                    DEFAULT: '#00D1FF',
                    neon: '#00D1FF',
                    bright: '#00E9FF',
                    glow: '#38E5FF',
                    light: '#A5F3FC',
                    dark: '#0096B8',
                },
                navy: {
                    deep: '#004B87',
                    mid: '#003366',
                    dark: '#001F3F',
                    card: '#081326',
                    border: '#003F73',
                },
                cyber: {
                    black: '#000000',
                    surface: '#030712',
                    card: '#050c18',
                    cardHover: '#0a172c',
                    border: '#004B87',
                    borderGlow: '#00D1FF',
                    muted: '#64748B',
                }
            },
            boxShadow: {
                'neon': '0 0 15px -1px rgba(0, 209, 255, 0.4)',
                'neon-lg': '0 0 25px 0px rgba(0, 209, 255, 0.55)',
                'neon-sm': '0 0 8px rgba(0, 209, 255, 0.35)',
                'neon-inner': 'inset 0 0 15px rgba(0, 209, 255, 0.2)',
                'deep-blue': '0 0 20px rgba(0, 75, 135, 0.5)',
            },
            animation: {
                'pulse-glow': 'pulseGlow 2.5s ease-in-out infinite',
                'border-glow': 'borderGlow 3s ease-in-out infinite',
                'float': 'float 3.5s ease-in-out infinite',
            },
            keyframes: {
                pulseGlow: {
                    '0%, 100%': { opacity: '0.6', transform: 'scale(1)' },
                    '50%': { opacity: '1', transform: 'scale(1.02)' },
                },
                borderGlow: {
                    '0%, 100%': { borderColor: '#004B87' },
                    '50%': { borderColor: '#00D1FF' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                }
            }
        },
    },

    plugins: [forms],
};


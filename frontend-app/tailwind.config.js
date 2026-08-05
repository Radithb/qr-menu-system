/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#B98B6A',    // Medium-light brown / Tan (Buttons, active accents)
        secondary: '#7A4A3A',  // Medium-dark brown (Headers, secondary elements)
        background: '#E9D8C6', // Light beige (Main page background)
        textColor: '#4B2E2A',  // Darkest brown (Main text)
        card: '#F7F2EC',       // Off-white (Cards, Modals, very light background)
        cta: '#ffffff',
      },
      fontFamily: {
        heading: ['"Playfair Display SC"', 'serif'],
        sans: ['Karla', 'sans-serif'],
      },
      keyframes: {
        'fade-up': {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        }
      },
      animation: {
        'fade-up': 'fade-up 0.5s ease-out forwards',
      }
    },
  },
  plugins: [],
}

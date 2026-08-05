/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#C19A6B',    
        secondary: '#E3CAA5',  
        background: '#D6C0A4', // Visibly darker beige/light brown
        textColor: '#594025',  
        cta: '#ffffff',
      },
      fontFamily: {
        heading: ['"Playfair Display SC"', 'serif'],
        sans: ['Karla', 'sans-serif'],
      }
    },
  },
  plugins: [],
}

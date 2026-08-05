/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#92400E',
        secondary: '#B45309',
        cta: '#F8FAFC',
        background: '#FEF3C7',
        textColor: '#78350F',
      },
      fontFamily: {
        heading: ['"Playfair Display SC"', 'serif'],
        sans: ['Karla', 'sans-serif'],
      }
    },
  },
  plugins: [],
}

export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/views/filament/**/*.blade.php',
    './resources/views/components/**/*.blade.php',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
  safelist: [
    // Ensure dynamic classes are included
    {
      pattern: /^(grid|gap|p|px|py|m|mx|my|rounded|border|bg|text|font|shadow|flex|items|justify|w|h|max)/,
      variants: ['md', 'lg', 'dark', 'hover', 'focus'],
    },
  ],
}

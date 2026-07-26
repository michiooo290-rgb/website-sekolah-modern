/* Tailwind v4 diproses lewat PostCSS saat build.
   Sumber kelas didaftarkan secara eksplisit di src/app/globals.css. */
const config = {
	plugins: {
		"@tailwindcss/postcss": {},
	},
};

export default config;

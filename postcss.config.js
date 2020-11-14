module.exports = {
	syntax: 'postcss-scss',
	plugins: [
		require('tailwindcss'),
		require('autoprefixer'),
		require('postcss-nested'),
		require('@fullhuman/postcss-purgecss')({
			content: [
				'**/*.twig'
			],
			defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
		}),
		require('cssnano')({
			preset: 'default',
		})
	]
}
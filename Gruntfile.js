module.exports = function( grunt ) {

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		checkwpversion: {
			options: {
				readme: 'readme.txt',
				plugin: 'pronamic-client.php',
			},
			check: {
				version1: 'plugin',
				version2: 'readme',
				compare: '=='
			},
			check2: {
				version1: 'plugin',
				version2: '<%= pkg.version %>',
				compare: '=='
			}
		},

		makepot: {
			target: {
				options: {
					cwd: '',
					domainPath: 'languages',
					type: 'wp-plugin'
				}
			}
		}
	} );

	grunt.loadNpmTasks( 'grunt-checkwpversion' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	grunt.registerTask( 'default', [ 'checkwpversion', 'makepot' ] );
};

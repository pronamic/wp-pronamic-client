module.exports = function( grunt ) {
	// Config init
	grunt.initConfig( {
		// Package
		pkg: grunt.file.readJSON( 'package.json' ),
		
		// PHPLint
		phplint: {
			options: {
				phpArgs: {
					'-lf': null
				}
			},
			all: [ '**/*.php' ]
		},
		
		// PHP Code Sniffer
		phpcs: {
		    application: {
		        dir: [ '**/*.php' ]
		    },
		    options: {
		        standard: 'project.ruleset.xml'
		    }
		},

		// Check WordPress version
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

		// Make POT
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

	// Load tasks
	grunt.loadNpmTasks( 'grunt-phplint' );
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-checkwpversion' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	// Register tasks
	grunt.registerTask( 'default', [ 'phplint', 'phpcs', 'checkwpversion' ] );
	grunt.registerTask( 'pot', [ 'makepot' ] );
};
